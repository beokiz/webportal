<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\Operator;
use App\Models\Role;
use App\Models\Training;
use App\Models\TrainingProposal;
use App\Models\User;
use App\Services\Items\KitaItemService;
use App\Services\Items\OperatorItemService;
use App\Services\Items\TrainingItemService;
use App\Services\Items\TrainingProposalItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Registered User Controller
 *
 * @package \App\Http\Controllers\Auth
 */
class RegisteredUserController extends BaseController
{
    /**
     * RegisteredUserController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Display the registration view.
     *
     * @return Response
     */
    public function create() : Response
    {
        $trainingItemService = app(TrainingItemService::class);
        $operatorItemService = app(OperatorItemService::class);

        $trainings = $trainingItemService->collection([
            'paginated'        => false,
            'status'           => Training::STATUS_PLANNED,
            'after_first_date' => Carbon::today(),
        ]);

        $operators = $operatorItemService->collection([
            'paginated' => false,
        ]);

        // Empty model for empty select option
        $emptyOperator = tap(new Operator(), function ($model) {
            $model->id   = null;
            $model->name = 'Sonstiger Träger';
        });

        return Inertia::render('Auth/Register', [
            'availableTrainings' => $trainings,
            'operators'          => !empty($emptyOperator) ? $operators->prepend($emptyOperator) : $operators,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param RegistrationRequest $request
     * @return RedirectResponse
     */
    public function store(RegistrationRequest $request) : RedirectResponse
    {
        $attributes = $request->validated();

        dd($attributes);
        $userItemService             = app(UserItemService::class);
        $kitaItemService             = app(KitaItemService::class);
        $trainingItemService         = app(TrainingItemService::class);
        $trainingProposalItemService = app(TrainingProposalItemService::class);

        // Disable observer to prevent email notifications from being sent
        User::flushEventListeners();

        // Create kita
        $kita = $kitaItemService->create(array_merge($attributes['kita'], [
            'approved'              => false,
            'num_pedagogical_staff' => $attributes['kita']['participant_count'],
        ]));

        // Create user
        $user = $userItemService->create(array_merge($attributes['user'], [
            'password' => Str::random(15),
            'role'     => optional(Role::where('name', config('permission.project_roles.manager'))->first())->id,
        ]));

        // Connect manager user to the kita
        $kita->users()->sync([$user->id]);

        // Create training proposal for kita or connect kita to existed training
        if (!empty($attributes['kita']['trainings'])) {
            foreach ($attributes['kita']['trainings'] as $trainingData) {
                $trainingProposal = $trainingProposalItemService->create([
                    'first_date'        => $trainingData['first_date'],
                    'second_date'       => $trainingData['second_date'],
                    'participant_count' => $attributes['kita']['participant_count'],
                    'status'            => TrainingProposal::STATUS_EMAIL_NOT_CONFIRMED,
                ]);

                $trainingProposal->kitas()->attach($kita->id);
            }
        }

        if (!empty($attributes['kita']['training_id'])) {
            $trainingItemService->updateAttachedKitas($attributes['kita']['training_id'], [$kita->id]);
        }

        // Authorize user & send email verification notification
        auth()->login($user);

        $user->sendEmailVerificationNotification();

        return !empty($user) && !empty($kita)
            ? Redirect::back()->withSuccesses(__('crud.users.create_success'))
            : Redirect::back()->withErrors(__('crud.users.create_error'));
    }
}
