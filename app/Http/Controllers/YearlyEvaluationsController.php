<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\YearlyEvaluations\CreateYearlyEvaluationRequest;
use App\Http\Requests\YearlyEvaluations\UpdateYearlyEvaluationRequest;
use App\Models\Kita;
use App\Models\User;
use App\Models\YearlyEvaluation;
use App\Services\Items\KitaItemService;
use App\Services\Items\SurveyTimePeriodItemService;
use App\Services\Items\YearlyEvaluationItemService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Yearly Evaluations Controller
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

        $kitaItemService             = app(KitaItemService::class);
        $surveyTimePeriodItemService = app(SurveyTimePeriodItemService::class);

        // Get all kitas for select
        $kitas = $kitaItemService->collection(array_merge([
            'with' => 'evaluations',
        ], $currentUser->is_manager ? ['with_users' => [$currentUser->id]] : []))->transform(function (Kita $kita) {
            $kita->append([
                'evaluations_total_per_year_count',
                'evaluations_with_daz2_total_per_year_count',
                'evaluations_with_daz4_total_per_year_count',
                'evaluations_without_daz2_total_per_year_count',
                'evaluations_without_daz4_total_per_year_count',
            ]);

            return $kita;
        });

        if (!empty($kitas) && $kitas->isNotEmpty()) {
            $args = $request->only(['page', 'per_page', 'sort', 'order_by', 'year', 'with_kita_names', 'children_2_born_per_year', 'children_4_born_per_year', 'evaluations_with_daz_2_total_per_year', 'evaluations_with_daz_4_total_per_year']);

            if ($currentUser->is_manager) {
                $args['with_kitas'] = $kitas->pluck('id')->toArray();
            }

            $result = $this->yearlyEvaluationItemService->collection(array_merge($args, [
                'paginated' => true,
            ]));
        } else {
            $result = new LengthAwarePaginator(collect([]), 0, 1);
        }

        return Inertia::render('YearlyEvaluations/YearlyEvaluations', $this->prepareItemsCollection($result, [
            'filters'           => $request->only(['year', 'with_kita_names', 'children_2_born_per_year', 'children_4_born_per_year', 'evaluations_with_daz_2_total_per_year', 'evaluations_with_daz_4_total_per_year']),
            'kitas'             => $kitas,
            'surveyTimePeriods' => $surveyTimePeriodItemService->collection(),
            'usersEmails'       => $kitaItemService->getUsersEmails($kitas->pluck('id')->toArray()),
        ]));
    }

    /**
     * @param Request          $request
     * @param YearlyEvaluation $yearlyEvaluation
     * @return \Inertia\Response
     */
    public function show(Request $request, YearlyEvaluation $yearlyEvaluation)
    {
//        $this->authorize('authorizeAccessToYearlyEvaluations', User::class);
        $this->authorize('authorizeAccessToSingleYearlyEvaluations', [User::class, $yearlyEvaluation->kita_id]);

        $currentUser = $request->user();

        $kitaItemService             = app(KitaItemService::class);
        $surveyTimePeriodItemService = app(SurveyTimePeriodItemService::class);

        // Get all kitas for select
        $kitas = $kitaItemService->collection(array_merge([
            'with' => 'evaluations',
        ], $currentUser->is_manager ? ['with_users' => [$currentUser->id]] : []))
            ->transform(function (Kita $kita) {
                $kita->append([
                    'evaluations_total_per_year_count',
                    'evaluations_with_daz2_total_per_year_count',
                    'evaluations_with_daz4_total_per_year_count',
                    'evaluations_without_daz2_total_per_year_count',
                    'evaluations_without_daz4_total_per_year_count',
                ]);

                return $kita;
            });

        return Inertia::render('YearlyEvaluations/Partials/ManageYearlyEvaluation', [
            'yearlyEvaluation'  => $yearlyEvaluation,
            'kitas'             => $kitas,
            'surveyTimePeriods' => $surveyTimePeriodItemService->collection(),
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
