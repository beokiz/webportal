<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\SurveyTimePeriods\CreateSurveyTimePeriodRequest;
use App\Http\Requests\SurveyTimePeriods\UpdateSurveyTimePeriodRequest;
use App\Models\SurveyTimePeriod;
use App\Models\User;
use App\Services\Items\SurveyTimePeriodItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Survey Time Period Controller
 *
 * @package \App\Http\Controllers
 */
class SurveyTimePeriodController extends BaseController
{
    /**
     * @var SurveyTimePeriodItemService
     */
    protected $surveyTimePeriodItemService;

    /**
     * SurveyTimePeriodController constructor.
     *
     * @param SurveyTimePeriodItemService $surveyTimePeriodItemService
     * @return void
     */
    public function __construct(SurveyTimePeriodItemService $surveyTimePeriodItemService)
    {
        $this->surveyTimePeriodItemService = $surveyTimePeriodItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToSurveyTimePeriods', User::class);

        $args   = $request->only(['page', 'per_page', 'sort', 'order_by']);
        $result = $this->surveyTimePeriodItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('SurveyTimePeriods/SurveyTimePeriods', $this->prepareItemsCollection($result, [
            'filters' => $request->only([]),
        ]));
    }

    /**
     * @param Request          $request
     * @param SurveyTimePeriod $surveyTimePeriod
     * @return \Inertia\Response
     */
    public function show(Request $request, SurveyTimePeriod $surveyTimePeriod)
    {
        $this->authorize('authorizeAccessToSurveyTimePeriods', User::class);

        return Inertia::render('SurveyTimePeriods/Partials/ManageSurveyTimePeriod', [
            'surveyTimePeriod' => $surveyTimePeriod,
        ]);
    }

    /**
     * @param CreateSurveyTimePeriodRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateSurveyTimePeriodRequest $request)
    {
        $this->authorize('authorizeAccessToSurveyTimePeriods', User::class);

        $attributes = $request->validated();
        $result     = $this->surveyTimePeriodItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.survey_time_periods.create_success'))
            : Redirect::back()->withErrors(__('crud.survey_time_periods.create_error'));
    }

    /**
     * @param UpdateSurveyTimePeriodRequest $request
     * @param SurveyTimePeriod              $surveyTimePeriod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSurveyTimePeriodRequest $request, SurveyTimePeriod $surveyTimePeriod)
    {
        $this->authorize('authorizeAccessToSurveyTimePeriods', User::class);

        $attributes = $request->validated();
        $result     = $this->surveyTimePeriodItemService->update($surveyTimePeriod->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.survey_time_periods.update_success'))
            : Redirect::back()->withErrors(__('crud.survey_time_periods.update_error'));
    }

    /**
     * @param Request          $request
     * @param SurveyTimePeriod $surveyTimePeriod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, SurveyTimePeriod $surveyTimePeriod)
    {
        $this->authorize('authorizeAccessToSurveyTimePeriods', User::class);

        $result = $this->surveyTimePeriodItemService->delete($surveyTimePeriod->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.survey_time_periods.delete_success'))
            : Redirect::back()->withErrors(__('crud.survey_time_periods.delete_error'));
    }
}
