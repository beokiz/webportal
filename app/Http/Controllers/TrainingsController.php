<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Trainings\AddKitasToTrainingRequest;
use App\Http\Requests\Trainings\AddKitaToTrainingRequest;
use App\Http\Requests\Trainings\CreateTrainingRequest;
use App\Http\Requests\Trainings\RemoveKitaFromTrainingRequest;
use App\Http\Requests\Trainings\RemoveKitasFromTrainingRequest;
use App\Http\Requests\Trainings\UpdateTrainingRequest;
use App\Models\Kita;
use App\Models\Training;
use App\Models\User;
use App\Services\Items\KitaItemService;
use App\Services\Items\OperatorItemService;
use App\Services\Items\TrainingItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Trainings Controller
 *
 * @package \App\Http\Controllers
 */
class TrainingsController extends BaseController
{
    /**
     * @var TrainingItemService
     */
    protected $trainingItemService;

    /**
     * TrainingsController constructor.
     *
     * @param TrainingItemService $trainingItemService
     * @return void
     */
    public function __construct(TrainingItemService $trainingItemService)
    {
        $this->trainingItemService = $trainingItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $currentUser = $request->user();

        $userItemService = app(UserItemService::class);

        $args = $request->only([
            'page', 'per_page', 'sort', 'order_by', 'first_date', 'second_date', 'location', 'participant_count',
            'max_participant_count', 'type', 'with_multipliers', 'status',
        ]);

        if (!empty($args['order_by']) && $args['order_by'] === 'prepared_participant_count') {
            $args['order_by'] = 'participant_count';
        }

        if ($currentUser->is_user_multiplier) {
            $args['with_multipliers'] = [$currentUser->id];
        }

        $result = $this->trainingItemService->collection(array_merge($args, [
            'paginated' => true,
        ]), ['kitas.users']);

        $preparedResult = $this->prepareItemsCollection($result);

        $preparedResult['items'] = $preparedResult['items']->map(function ($training) {
            $training->email_messages = $training->getEmailMessagesContent();

            return $training;
        });

        return Inertia::render('Trainings/Trainings', array_merge($preparedResult, [
            'filters'     => $request->only([
                'first_date', 'second_date', 'location', 'participant_count', 'max_participant_count', 'type',
                'with_multipliers', 'status',
            ]),
            'multipliers' => $userItemService->collection(['with_roles' => [config('permission.project_roles.user_multiplier')]]),
            'statuses'    => array_map(function ($status) {
                return [
                    'title' => __("validation.attributes.{$status}"),
                    'value' => $status,
                ];
            }, Training::STATUSES),
            'types'       => array_map(function ($type) {
                return [
                    'title' => __("validation.attributes.{$type}"),
                    'value' => $type,
                ];
            }, Training::TYPES),
        ]));
    }

    /**
     * @param Request  $request
     * @param Training $training
     * @return \Inertia\Response
     */
    public function show(Request $request, Training $training)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $training->loadMissing(['multiplier']);

        $currentUser = $request->user();

        $kitaItemService     = app(KitaItemService::class);
        $userItemService     = app(UserItemService::class);
        $operatorItemService = app(OperatorItemService::class);

        // Get params for sorting & filtering Training Kitas
        $trainingKitaArgs = $request->only(['sort', 'order_by', 'search', 'has_yearly_evaluations', 'approved', 'operator_id', 'type', 'zip_code']);

        $trainingKitas = $kitaItemService->collection(array_merge($trainingKitaArgs ?? [], ['paginated' => false, 'with_trainings' => [$training->id], 'with' => ['users', 'currentYearlyEvaluations']]));

        // Get params for sorting & filtering all Kitas
        $allKitaArgs = [];

        if ($currentUser->is_user_multiplier) {
            $currentUser->loadMissing(['operators']);

            $allKitaArgs['with_operators'] = $currentUser->operators->pluck('id')
                ->flatten()
                ->unique()
                ->toArray();
        }

        $allKitas = $kitaItemService->collection(array_merge($allKitaArgs, [
            'paginated' => false,
            'with'      => ['operator', 'currentYearlyEvaluations', 'users.roles'],
        ]));

        // Fetch all zip codes from kitas
        $zipCodesList = $trainingKitas->pluck('zip_code')->unique()->transform(function ($zipCode) {
            return [
                'title' => $zipCode,
                'value' => $zipCode,
            ];
        })->values()->toArray();

        // Prepare operators list
        if ($currentUser->is_user_multiplier) {
            $currentUser->loadMissing(['operators']);

            $operators = $currentUser->operators;
        } else {
            $operators = $operatorItemService->collection();
        }

        return Inertia::render('Trainings/Partials/ManageTraining', [
            'training'      => $training,
            'emailMessages' => $training->getEmailMessagesContent(),
            'filters'       => $request->only(['search', 'has_yearly_evaluations', 'approved', 'operator_id', 'type', 'zip_code']),
            'allKitas'      => $allKitas,
            'trainingKitas' => $trainingKitas,
            'usersEmails'   => (!empty($trainingKitas) && $trainingKitas->isNotEmpty()) ? $kitaItemService->getUsersEmails($trainingKitas->pluck('id')->toArray()) : [],
            'multipliers'   => $userItemService->collection(['with_roles' => [config('permission.project_roles.user_multiplier')]]),
            'operators'     => $operators,
            'statuses'      => array_map(function ($status) {
                return [
                    'title' => __("validation.attributes.{$status}"),
                    'value' => $status,
                ];
            }, Training::STATUSES),
            'types'         => array_map(function ($type) {
                return [
                    'title' => __("validation.attributes.{$type}"),
                    'value' => $type,
                ];
            }, Training::TYPES),
            'kitaTypes'     => array_map(function ($type) {
                return [
                    'title' => __("validation.attributes.{$type}"),
                    'value' => $type,
                ];
            }, Kita::TYPES),
            'zipCodes'      => $zipCodesList,
        ]);
    }

    /**
     * @param CreateTrainingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateTrainingRequest $request)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.trainings.create_success'))
            : Redirect::back()->withErrors(__('crud.trainings.create_error'));
    }

    /**
     * @param UpdateTrainingRequest $request
     * @param Training              $training
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTrainingRequest $request, Training $training)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingItemService->update($training->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.trainings.update_success'))
            : Redirect::back()->withErrors(__('crud.trainings.update_error'));
    }

    /**
     * @param AddKitaToTrainingRequest $request
     * @param Training                 $training
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addKita(AddKitaToTrainingRequest $request, Training $training)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingItemService->updateAttachedKitas($training->id, [$attributes['kita']]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.trainings.update_success'))
            : Redirect::back()->withErrors(__('crud.trainings.update_error'));
    }

    /**
     * @param AddKitasToTrainingRequest $request
     * @param Training                  $training
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addKitas(AddKitasToTrainingRequest $request, Training $training)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingItemService->updateAttachedKitas($training->id, $attributes['kitas']);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.trainings.update_success'))
            : Redirect::back()->withErrors(__('crud.trainings.update_error'));
    }

    /**
     * @param RemoveKitaFromTrainingRequest $request
     * @param Training                      $training
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeKita(RemoveKitaFromTrainingRequest $request, Training $training)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingItemService->updateAttachedKitas($training->id, [$attributes['kita']], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.trainings.update_success'))
            : Redirect::back()->withErrors(__('crud.trainings.update_error'));
    }

    /**
     * @param RemoveKitasFromTrainingRequest $request
     * @param Training                       $training
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeKitas(RemoveKitasFromTrainingRequest $request, Training $training)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingItemService->updateAttachedKitas($training->id, $attributes['kitas'], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.trainings.update_success'))
            : Redirect::back()->withErrors(__('crud.trainings.update_error'));
    }

    /**
     * @param Request  $request
     * @param Training $training
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Training $training)
    {
        $this->authorize('authorizeAccessToTrainings', User::class);

        $result = $this->trainingItemService->delete($training->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.trainings.delete_success'))
            : Redirect::back()->withErrors(__('crud.trainings.delete_error'));
    }
}
