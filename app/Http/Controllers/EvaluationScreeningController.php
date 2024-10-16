<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Evaluations\EvaluationScreeningRequest;
use App\Http\Traits\ControllerTrait;
use App\Interfaces\FileGenerators\PdfGeneratorServiceInterface;
use App\Models\Domain;
use App\Models\User;
use App\Services\Items\DomainItemService;
use App\Services\Items\EvaluationItemService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Evaluation Screening Controller
 *
 * @package \App\Http\Controllers
 */
class EvaluationScreeningController extends BaseController
{
    use ControllerTrait;

    /**
     * @var EvaluationItemService
     */
    protected $evaluationItemService;

    /**
     * @var DomainItemService
     */
    protected $domainItemService;

    /**
     * EvaluationScreeningController constructor.
     *
     * @param EvaluationItemService $evaluationItemService
     * @param DomainItemService     $domainItemService
     * @return void
     */
    public function __construct(EvaluationItemService $evaluationItemService, DomainItemService $domainItemService)
    {
        $this->evaluationItemService = $evaluationItemService;
        $this->domainItemService     = $domainItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);

        return Inertia::render('Evaluations/EvaluationScreening', [
            'domains' => $this->domainItemService->collection(),
        ]);
    }

    /**
     * @param Request $request
     * @param Domain  $domain
     * @return \Inertia\Response
     */
    public function show(Request $request, Domain $domain)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);

        $domains = Collection::make([$domain->loadMissing([
            'subdomains' => function ($query) {
                $query->orderBy('order')->with(['milestones']);
            },
        ])]);

        return Inertia::render('Evaluations/Partials/MakeEvaluationScreening', [
            'domains'      => $this->prepareDomainsData($domains),
            'dazDependent' => $domain->daz_dependent,
            'from'         => $request->input('from'),
        ]);
    }

    /**
     * @param EvaluationScreeningRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function make(EvaluationScreeningRequest $request)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);

        $attributes = $request->validated();
        $result     = $this->evaluationItemService->calculateRating(
            $attributes['ratings'],
            (!empty($attributes['age']) && $attributes['age'] === '4.5'),
            !empty($attributes['is_daz'])
        );

        return $result
            ? Redirect::back()
                ->withSuccesses(__('crud.evaluations.check_success'))
                ->withData(['evaluation' => $result, 'domain' => $this->domainItemService->find(array_column($attributes['ratings'], 'domain')[0])])
            : Redirect::back()->withErrors(__('crud.evaluations.check_error'));
    }

    /**
     * @param EvaluationScreeningRequest $request
     * @return \Illuminate\Http\RedirectResponse|BinaryFileResponse
     */
    public function pdf(EvaluationScreeningRequest $request)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);

        /*
         * Calculate results
         */
        $attributes = $request->validated();
        $result     = $this->evaluationItemService->calculateRating(
            $attributes['ratings'],
            (!empty($attributes['age']) && $attributes['age'] === '4.5'),
            !empty($attributes['is_daz'])
        );

        /*
         * Generate PDF file
         */
        $pdfGeneratorService = app(PdfGeneratorServiceInterface::class);
        $domainItemService   = app(DomainItemService::class);

        $pdfHeaderFooterData = [
            'header' => [
                'current_time' => Carbon::now()->format('Y-m-d H:i e'),
            ],
            'footer' => [
                'display_document_meta' => true,
            ],
        ];

        $domainsArr = array_column($result, 'milestones', 'domain');

        $domainsList = $domainItemService->collection(['only' => array_keys($domainsArr)], [
            'subdomains' => function ($query) {
                $query->orderBy('order')->with(['milestones']);
            },
        ]);

        $domainNames = implode(', ', $domainsList->pluck('name')->toArray());

        $pdfFile = $pdfGeneratorService->createFromBlade(
            'file-templates.pdf.screening',
            [
                'age'          => $attributes['age'],
                'result'       => $result,
                'domains'      => $domainsList,
                'domain_names' => $domainNames,
            ],
            [
                'file_name'   => 'screening_' . Str::slug($domainNames, '_'),
                'header-html' => view('layouts.pdf-components.pdf-file-spatie-header', ['headerData' => $pdfHeaderFooterData['header']])->render(),
                'footer-html' => view('layouts.pdf-components.pdf-file-spatie-footer', ['footerData' => $pdfHeaderFooterData['footer']])->render(),
            ],
            false
        );

        return $pdfFile ?
            Response::download($pdfFile, basename($pdfFile), [
                'Content-Type'        => mime_content_type($pdfFile),
                'Content-Disposition' => 'attachment; filename="' . basename($pdfFile) . '"',
            ])
            : Redirect::back()->withErrors(__('crud.evaluations.pdf_error'));
    }
}
