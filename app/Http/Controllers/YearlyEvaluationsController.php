<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\SurveyTimePeriods\CreateSurveyTimePeriodRequest;
use App\Http\Requests\SurveyTimePeriods\UpdateSurveyTimePeriodRequest;
use App\Models\Evaluation;
use App\Models\YearlyEvaluation;
use App\Models\User;
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

        $args   = $request->only(['page', 'per_page', 'sort', 'order_by']);
        $result = $this->yearlyEvaluationItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('YearlyEvaluations/YearlyEvaluations', $this->prepareItemsCollection($result, [
            'filters' => $request->only([]),
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

        $evaluations = Evaluation::whereNotNull('finished_at')->get();

        return Inertia::render('YearlyEvaluations/Partials/ManageYearlyEvaluation', [
            'yearlyEvaluation' => $yearlyEvaluation,
//            'evaluationsWithoutDaz2TotalPerYear' => $yearlyEvaluation,
//            'evaluationsWithoutDaz4TotalPerYear' => $yearlyEvaluation,
//            'evaluationsWithDaz2TotalPerYear'    => $yearlyEvaluation,
//            'evaluationsWithDaz4TotalPerYear'    => $yearlyEvaluation,
        ]);
    }

    /**
     * @param CreateSurveyTimePeriodRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateSurveyTimePeriodRequest $request)
    {
        $this->authorize('authorizeAccessToYearlyEvaluations', User::class);

        $attributes = $request->validated();
        $result     = $this->yearlyEvaluationItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.yearly_evaluations.create_success'))
            : Redirect::back()->withErrors(__('crud.yearly_evaluations.create_error'));
    }

    /**
     * @param UpdateSurveyTimePeriodRequest $request
     * @param YearlyEvaluation              $yearlyEvaluation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSurveyTimePeriodRequest $request, YearlyEvaluation $yearlyEvaluation)
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
