<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Evaluations\CreateEvaluationRequest;
use App\Http\Requests\Evaluations\SaveEvaluationRequest;
use App\Http\Requests\Evaluations\UpdateEvaluationRequest;
use App\Models\Evaluation;
use App\Models\User;
use App\Services\Items\DomainItemService;
use App\Services\Items\EvaluationItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Evaluation Controller
 *
 * @package \App\Http\Controllers
 */
class EvaluationController extends BaseController
{
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

        if ($currentUser->is_manager) {
            $args['with_users'] = $currentUser->id;
        }

        $result = $this->evaluationItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('Evaluations/Evaluations', $this->prepareItemsCollection($result, [
            'filters' => $request->only([]),
        ]));
    }

    /**
     * @param Request    $request
     * @param Evaluation $evaluation
     * @return \Inertia\Response
     */
    public function show(Request $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToEvaluations', User::class);
        $this->authorize('authorizeAccessToSingleEvaluation', [User::class, $evaluation->id]);

        $currentUser = $request->user()->loadMissing(['kitas']);

        $domainItemService = app(DomainItemService::class);

        return Inertia::render('Evaluations/Partials/ManageEvaluation', [
            'evaluation' => $evaluation->loadMissing(['user', 'kita']),
            'kitas'      => $currentUser->kitas,
            'domains'    => $domainItemService->collection([], [
                'subdomains' => function ($query) {
                    $query->orderBy('order')->with(['milestones']);
                },
            ]),
        ]);
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        $this->authorize('authorizeAccessToEvaluations', User::class);

        $currentUser = $request->user()->loadMissing(['kitas']);

        $domainItemService = app(DomainItemService::class);

        return Inertia::render('Evaluations/Partials/CreateEvaluation', [
            'kitas'   => $currentUser->kitas,
            'domains' => $domainItemService->collection([], [
                'subdomains' => function ($query) {
                    $query->orderBy('order')->with(['milestones']);
                },
            ]),
        ]);
    }

    /**
     * @param CreateEvaluationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateEvaluationRequest $request)
    {
        $this->authorize('authorizeAccessToEvaluations', User::class);

        $attributes = $request->validated();
        $result     = $this->evaluationItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.evaluations.create_success'))
            : Redirect::back()->withErrors(__('crud.evaluations.create_error'));
    }

    /**
     * @param UpdateEvaluationRequest $request
     * @param Evaluation              $evaluation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToSingleEvaluation', [User::class, $evaluation->id]);

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
            $this->authorize('authorizeAccessToSingleEvaluation', [User::class, $request->input('id')]);
        } else {
            $this->authorize('authorizeAccessToEvaluations', User::class);
        }

        $attributes = $request->validated();
        $result     = $this->evaluationItemService->save($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.evaluations.save_success'))
            : Redirect::back()->withErrors(__('crud.evaluations.save_error'));
    }

    /**
     * @param Request    $request
     * @param Evaluation $evaluation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Evaluation $evaluation)
    {
        $this->authorize('authorizeAccessToSingleEvaluation', [User::class, $evaluation->id]);

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
        $this->authorize('authorizeAccessToSingleEvaluation', [User::class, $evaluation->id]);

        $result = $this->evaluationItemService->update($evaluation->id, [
            'deleted_at' => null,
        ]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.evaluations.restore_success'))
            : Redirect::back()->withErrors(__('crud.evaluations.restore_error'));
    }
}
