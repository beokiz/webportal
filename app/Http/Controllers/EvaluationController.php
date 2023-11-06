<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Evaluations\CreateEvaluationRequest;
use App\Http\Requests\Evaluations\SaveEvaluationRequest;
use App\Http\Requests\Evaluations\UpdateEvaluationRequest;
use App\Http\Traits\ControllerTrait;
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
 * Evaluation Controller
 *
 * @package \App\Http\Controllers
 */
class EvaluationController extends BaseController
{
    use ControllerTrait;

    /**
     * @var EvaluationItemService
     */
    protected $evaluationItemService;

    /**
     * EvaluationController constructor.
     *
     * @param EvaluationItemService $evaluationItemService
     * @return void
     */
    public function __construct(EvaluationItemService $evaluationItemService)
    {
        $this->evaluationItemService = $evaluationItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToEvaluations', User::class);

        $currentUser = $request->user();

        $args = $request->only(['page', 'per_page', 'sort', 'order_by']);

        if ($currentUser->is_manager || $currentUser->is_employer) {
            $args['with_users'] = $currentUser->id;
        }

        if ($currentUser->is_employer) {
            $now = Carbon::now();

            $args['finished_between'] = [
                'from' => $now->copy()->subMinutes(15),
                'to'   => $now,
            ];
        }

        $result = $this->evaluationItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('Evaluations/Evaluations', $this->prepareItemsCollection($result, [
            'filters' => $request->only([]),
        ]));
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);

        $currentUser = $request->user()->loadMissing(['kitas']);

        $domainItemService = app(DomainItemService::class);

        $domains = $this->prepareDomainsData($domainItemService->collection([], [
            'subdomains' => function ($query) {
                $query->orderBy('order')->with(['milestones']);
            },
        ]));

        return Inertia::render('Evaluations/Partials/ManageEvaluation', [
            'kitas'   => $currentUser->kitas,
            'domains' => $domains,
        ]);
    }

    /**
     * @param Request    $request
     * @param Evaluation $evaluation
     * @return mixed
     */
    public function show(Request $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);
        $this->authorize('authorizeAccessToSingleEvaluation', [User::class, $evaluation->id]);

        $domainItemService = app(DomainItemService::class);

        $domains = $this->prepareDomainsData($domainItemService->collection([], [
            'subdomains' => function ($query) {
                $query->orderBy('order')->with(['milestones']);
            },
        ]));

        return Redirect::back()
            ->withData([
                'item'    => $evaluation->loadMissing(['user', 'kita']),
                'domains' => $domains,
            ]);
    }

    /**
     * @param Request    $request
     * @param Evaluation $evaluation
     * @return \Illuminate\Http\RedirectResponse|BinaryFileResponse
     */
    public function pdf(Request $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToSingleEvaluation', [User::class, $evaluation->id]);

        $data = $this->evaluationItemService->exportInPdf($evaluation->id);

        return $data ?
            Response::download($data, basename($data), [
                'Content-Type'        => mime_content_type($data),
                'Content-Disposition' => 'attachment; filename="' . basename($data) . '"',
            ])
            : Redirect::back()->withErrors(__('crud.evaluations.pdf_error'));
    }

    /**
     * @param CreateEvaluationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateEvaluationRequest $request)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);

        $attributes = $request->validated();
        $result     = $this->evaluationItemService->create(array_merge($attributes, [
            'finished_at' => Carbon::now(),
        ]));

        return $result
            ? Redirect::route('evaluations.edit', ['evaluation' => $result->id])
                ->withSuccesses(__('crud.evaluations.create_success'))
                ->withData(['item' => $result])
            : Redirect::back()->withErrors(__('crud.evaluations.create_error'));
    }

    /**
     * @param Request    $request
     * @param Evaluation $evaluation
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function edit(Request $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToManageEvaluation', User::class);

        if (!$evaluation->editable) {
            return Redirect::back()->withErrors(__('crud.evaluations.update_denied'));
        }

        $currentUser = $request->user()->loadMissing(['kitas']);

        $domainItemService = app(DomainItemService::class);

        $domains = $this->prepareDomainsData($domainItemService->collection([], [
            'subdomains' => function ($query) {
                $query->orderBy('order')->with(['milestones']);
            },
        ]));

        return Inertia::render('Evaluations/Partials/ManageEvaluation', [
            'evaluation' => $evaluation->loadMissing(['user', 'kita']),
            'kitas'      => $currentUser->kitas,
            'domains'    => $domains,
        ]);
    }

    /**
     * @param UpdateEvaluationRequest $request
     * @param Evaluation              $evaluation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToManageSingleEvaluation', [User::class, $evaluation->id]);

        if (!$evaluation->editable) {
            return Redirect::back()->withErrors(__('crud.evaluations.update_denied'));
        }

        $attributes = $request->validated();
        $result     = $this->evaluationItemService->update($evaluation->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.evaluations.update_success'))
            : Redirect::back()->withErrors(__('crud.evaluations.update_error'));
    }

    /**
     * @param SaveEvaluationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(SaveEvaluationRequest $request)
    {
        if ($request->input('id')) {
            $this->authorize('authorizeAccessToManageSingleEvaluation', [User::class, $request->input('id')]);
        } else {
            $this->authorize('authorizeAccessToManageEvaluation', User::class);
        }

        $attributes = $request->validated();
        $result     = $this->evaluationItemService->save($attributes);

        return !empty($result->id)
            ? Redirect::route('evaluations.edit', ['evaluation' => $result->id])
                ->withSuccesses(__('crud.evaluations.save_success'))
            : Redirect::back()->withErrors(__('crud.evaluations.save_error'));
    }

    /**
     * @param Request    $request
     * @param Evaluation $evaluation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToManageSingleEvaluation', [User::class, $evaluation->id]);

        $result = $this->evaluationItemService->delete($evaluation->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.evaluations.delete_success'))
            : Redirect::back()->withErrors(__('crud.evaluations.delete_error'));
    }

    /**
     * @param Request    $request
     * @param Evaluation $evaluation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToManageSingleEvaluation', [User::class, $evaluation->id]);

        $result = $this->evaluationItemService->update($evaluation->id, [
            'deleted_at' => null,
        ]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.evaluations.restore_success'))
            : Redirect::back()->withErrors(__('crud.evaluations.restore_error'));
    }
}
