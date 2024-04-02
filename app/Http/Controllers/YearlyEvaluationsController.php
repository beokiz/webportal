<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\YearlyEvaluations\CreateYearlyEvaluationRequest;
use App\Http\Requests\YearlyEvaluations\UpdateYearlyEvaluationRequest;
use App\Models\Evaluation;
use App\Models\YearlyEvaluation;
use App\Models\User;
use App\Services\Items\EvaluationItemService;
use App\Services\Items\KitaItemService;
use App\Services\Items\YearlyEvaluationItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Survey Time Period Controller
 *
 * @package \App\Http\Controllers
 */
class YearlyEvaluationsController extends BaseController
{
    /**
     * @var YearlyEvaluationItemService
     */
    protected $yearlyEvaluationItemService;

    /**
     * YearlyEvaluationsController constructor.
     *
     * @param YearlyEvaluationItemService $yearlyEvaluationItemService
     * @return void
     */
    public function __construct(YearlyEvaluationItemService $yearlyEvaluationItemService)
    {
        $this->yearlyEvaluationItemService = $yearlyEvaluationItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToYearlyEvaluations', User::class);

        $currentUser = $request->user();

        $kitaItemService       = app(KitaItemService::class);
        $evaluationItemService = app(EvaluationItemService::class);

        $args   = $request->only(['page', 'per_page', 'sort', 'order_by']);
        $result = $this->yearlyEvaluationItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        // Get evaluations totals by criterias
        $allEvaluations = $evaluationItemService->collection();

        return Inertia::render('YearlyEvaluations/YearlyEvaluations', $this->prepareItemsCollection($result, [
            'filters' => $request->only([]),
            'kitas'   => $kitaItemService->collection($currentUser->is_manager ? ['with_users' => [$currentUser->id]] : []),
            'evaluationsWithDaz2TotalPerYear'    => $allEvaluations->where('age', Evaluation::CHILD_AGE_GROUP_2)->where('is_daz', true)->count(),
            'evaluationsWithDaz4TotalPerYear'    => $allEvaluations->where('age', Evaluation::CHILD_AGE_GROUP_4)->where('is_daz', true)->count(),
            'evaluationsWithoutDaz2TotalPerYear' => $allEvaluations->where('age', Evaluation::CHILD_AGE_GROUP_2)->where('is_daz', false)->count(),
            'evaluationsWithoutDaz4TotalPerYear' => $allEvaluations->where('age', Evaluation::CHILD_AGE_GROUP_4)->where('is_daz', false)->count(),
        ]));
    }

    /**
     * @param Request          $request
     * @param YearlyEvaluation $yearlyEvaluation
     * @return \Inertia\Response
     */
    public function show(Request $request, YearlyEvaluation $yearlyEvaluation)
    {
        $this->authorize('authorizeAccessToYearlyEvaluations', User::class);

        $currentUser = $request->user();

        $kitaItemService       = app(KitaItemService::class);
        $evaluationItemService = app(EvaluationItemService::class);

        // Get evaluations totals by criterias
        $allEvaluations = $evaluationItemService->collection();

        return Inertia::render('YearlyEvaluations/Partials/ManageYearlyEvaluation', [
            'yearlyEvaluation'                   => $yearlyEvaluation,
            'kitas'                              => $kitaItemService->collection($currentUser->is_manager ? ['with_users' => [$currentUser->id]] : []),
            'evaluationsWithDaz2TotalPerYear'    => $allEvaluations->where('age', Evaluation::CHILD_AGE_GROUP_2)->where('is_daz', true)->count(),
            'evaluationsWithDaz4TotalPerYear'    => $allEvaluations->where('age', Evaluation::CHILD_AGE_GROUP_4)->where('is_daz', true)->count(),
            'evaluationsWithoutDaz2TotalPerYear' => $allEvaluations->where('age', Evaluation::CHILD_AGE_GROUP_2)->where('is_daz', false)->count(),
            'evaluationsWithoutDaz4TotalPerYear' => $allEvaluations->where('age', Evaluation::CHILD_AGE_GROUP_4)->where('is_daz', false)->count(),
        ]);
    }

    /**
     * @param CreateYearlyEvaluationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateYearlyEvaluationRequest $request)
    {
        $this->authorize('authorizeAccessToYearlyEvaluations', User::class);

        $attributes = $request->validated();
        $result     = $this->yearlyEvaluationItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.yearly_evaluations.create_success'))
            : Redirect::back()->withErrors(__('crud.yearly_evaluations.create_error'));
    }

    /**
     * @param UpdateYearlyEvaluationRequest $request
     * @param YearlyEvaluation              $yearlyEvaluation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateYearlyEvaluationRequest $request, YearlyEvaluation $yearlyEvaluation)
    {
        $this->authorize('authorizeAccessToYearlyEvaluations', User::class);

        $attributes = $request->validated();
        $result     = $this->yearlyEvaluationItemService->update($yearlyEvaluation->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.yearly_evaluations.update_success'))
            : Redirect::back()->withErrors(__('crud.yearly_evaluations.update_error'));
    }

    /**
     * @param Request          $request
     * @param YearlyEvaluation $yearlyEvaluation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, YearlyEvaluation $yearlyEvaluation)
    {
        $this->authorize('authorizeAccessToYearlyEvaluations', User::class);

        $result = $this->yearlyEvaluationItemService->delete($yearlyEvaluation->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.yearly_evaluations.delete_success'))
            : Redirect::back()->withErrors(__('crud.yearly_evaluations.delete_error'));
    }
}
