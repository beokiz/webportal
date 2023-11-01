<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Evaluations\CheckEvaluationRequest;
use App\Http\Requests\Evaluations\CreateEvaluationRequest;
use App\Http\Requests\Evaluations\SaveEvaluationRequest;
use App\Http\Requests\Evaluations\UpdateEvaluationRequest;
use App\Models\Domain;
use App\Models\Evaluation;
use App\Models\User;
use App\Services\Items\DomainItemService;
use App\Services\Items\EvaluationItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Check Evaluation Controller
 *
 * @package \App\Http\Controllers
 */
class CheckEvaluationController extends BaseController
{
    /**
     * @var EvaluationItemService
     */
    protected $evaluationItemService;

    /**
     * @var DomainItemService
     */
    protected $domainItemService;

    /**
     * CheckEvaluationController constructor.
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
        $this->authorize('authorizeAccessToEvaluations', User::class);

        return Inertia::render('Evaluations/Evaluations', [
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
        $this->authorize('authorizeAccessToEvaluations', User::class);

        return Inertia::render('Evaluations/Evaluations', [
            'domain' => $domain->loadMissing([
                'subdomains' => function ($query) {
                    $query->orderBy('order')->with(['milestones']);
                },
            ]),
        ]);
    }

    /**
     * @param CheckEvaluationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function make(CheckEvaluationRequest $request)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);

        $attributes = $request->validated();
        $result     = $this->evaluationItemService->calculateRating(
            $attributes['ratings'],
            (!empty($attributes['age']) && $attributes['age'] === '4.5'),
            !empty($attributes['is_daz'])
        );

        return $result
            ? Redirect::back()->withSuccesses(__('crud.evaluations.check_success'))
            : Redirect::back()->withErrors(__('crud.evaluations.check_error'));
    }
}
