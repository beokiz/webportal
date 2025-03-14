<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\TrainingProposals\AddKitasToTrainingProposalRequest;
use App\Http\Requests\TrainingProposals\AddKitaToTrainingProposalRequest;
use App\Http\Requests\TrainingProposals\AddMultiplierToTrainingProposalRequest;
use App\Http\Requests\TrainingProposals\CreateTrainingProposalRequest;
use App\Http\Requests\TrainingProposals\RemoveKitaFromTrainingProposalRequest;
use App\Http\Requests\TrainingProposals\RemoveKitasFromTrainingProposalRequest;
use App\Http\Requests\TrainingProposals\UpdateTrainingProposalRequest;
use App\Models\Kita;
use App\Models\TrainingProposal;
use App\Models\User;
use App\Services\Items\KitaItemService;
use App\Services\Items\OperatorItemService;
use App\Services\Items\TrainingProposalItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * Training Proposals Controller
 *
 * @package \App\Http\Controllers
 */
class TrainingProposalsController extends BaseController
{
    /**
     * @var TrainingProposalItemService
     */
    protected $trainingProposalItemService;

    /**
     * TrainingProposalsController constructor.
     *
     * @param TrainingProposalItemService $trainingProposalItemService
     * @return void
     */
    public function __construct(TrainingProposalItemService $trainingProposalItemService)
    {
        $this->trainingProposalItemService = $trainingProposalItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);

        $currentUser = $request->user();

        if ($currentUser->is_user_multiplier) {
            $currentUser->loadMissing(['operators']);

            // Prüfen, ob ALLE Operatoren explizit nicht selbstschulend sind
            $hasNoSelfTrainingOperator = $currentUser->operators->every(function ($operator) {
                return $operator->has_self_training_operator === false;
            });

            // Zugriff verweigern, wenn ein selbstschulender Träger existiert
            if (!$hasNoSelfTrainingOperator) {
                abort(403, __('Sie haben keinen Zugriff auf diese Seite, da Ihr Betreiber selbstschulend ist.'));
            }
        }

        $userItemService = app(UserItemService::class);
        $kitaItemService = app(KitaItemService::class);

        $args = $request->only([
            'page', 'per_page', 'sort', 'order_by', 'first_date', 'second_date', 'location', 'participant_count',
            'with_multipliers', 'status', 'with_kitas',
        ]);

        if (!empty($args['order_by']) && $args['order_by'] === 'location') {
            $args['order_by'] = 'zip_code';
        }

        if ($currentUser->is_user_multiplier) {
            $args['status'] = TrainingProposal::STATUS_OPEN;

            $currentUser->loadMissing(['trainingProposals.kitas.users']);
        }

        $result = $this->trainingProposalItemService->collection(array_merge($args, [
            'paginated' => true,
        ]), ['kitas.users']);

        return Inertia::render('TrainingProposals/TrainingProposals', array_merge($this->prepareItemsCollection($result), [
            'userTrainingProposals' => $currentUser->is_user_multiplier ? $currentUser->trainingProposals->where('status', '!=', TrainingProposal::STATUS_CONFIRMED)->values() : null,
            'filters'               => $request->only(['first_date', 'second_date', 'location', 'participant_count', 'with_multipliers', 'status', 'with_kitas',]),
            'multipliers'           => $userItemService->collection(['with_roles' => [config('permission.project_roles.user_multiplier')]]),
            'kitas'                 => $kitaItemService->collection(['paginated' => false]),
            'statuses'              => array_map(function ($status) {
                return [
                    'title' => __("validation.attributes.{$status}"),
                    'value' => $status,
                ];
            }, TrainingProposal::STATUSES),
        ]));
    }

    /**
     * @param Request          $request
     * @param TrainingProposal $trainingProposal
     * @return \Inertia\Response
     */
    public function show(Request $request, TrainingProposal $trainingProposal)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);
        $this->authorize('authorizeAccessToSingleTrainingProposal', [User::class, $trainingProposal]);

        $trainingProposal->loadMissing(['multiplier', 'kitas.users']);

        $currentUser = $request->user();

        $kitaItemService     = app(KitaItemService::class);
        $userItemService     = app(UserItemService::class);
        $operatorItemService = app(OperatorItemService::class);

        // Get params for sorting & filtering TrainingProposal Kitas
        $trainingProposalKitaArgs = $request->only([
            'sort', 'order_by', 'search', 'has_yearly_evaluations', 'approved', 'operator_id', 'other_operator', 'type', 'zip_code',
        ]);

        $trainingProposalKitas = $kitaItemService->collection(array_merge($trainingProposalKitaArgs ?? [], [
            'paginated'               => false,
            'with_training_proposals' => [$trainingProposal->id],
            'with'                    => ['operator', 'users', 'currentYearlyEvaluations', 'trainingProposalConfirmations'],
        ]));

        // Get params for sorting & filtering all Kitas
        $allKitaArgs = [];

        if ($currentUser->is_user_multiplier) {
            $currentUser->loadMissing(['operators']);

            $currentUserOperators = $currentUser->operators->pluck('id')
                ->flatten()
                ->unique()
                ->toArray();

            // If there are no operators, we don't return anything
            $allKitaArgs['with_operators'] = !empty($currentUserOperators) ? $currentUserOperators : [-1];
        }

        $allKitas = $kitaItemService->collection(array_merge($allKitaArgs, [
            'paginated' => false,
            'with'      => ['operator', 'currentYearlyEvaluations', 'users.roles'],
        ]));

        // Fetch all zip codes from kitas
        $zipCodesList = $trainingProposalKitas->pluck('zip_code')->unique()->transform(function ($zipCode) {
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

        return Inertia::render('TrainingProposals/Partials/ManageTrainingProposal', [
            'trainingProposal'      => $trainingProposal,
            'notEditable'           => $currentUser->is_user_multiplier ? $trainingProposal->multi_id !== $currentUser->id : false,
            'filters'               => $request->only(['search', 'has_yearly_evaluations', 'approved', 'operator_id', 'type', 'zip_code']),
            'allKitas'              => $allKitas,
            'trainingProposalKitas' => $trainingProposalKitas,
            'usersEmails'           => (!empty($trainingProposalKitas) && $trainingProposalKitas->isNotEmpty()) ? $kitaItemService->getUsersEmails($trainingProposalKitas->pluck('id')->toArray()) : [],
            'multipliers'           => $userItemService->collection(['with_roles' => [config('permission.project_roles.user_multiplier')]]),
            'operators'             => $operators,
            'statuses'              => array_map(function ($status) {
                return [
                    'title' => __("validation.attributes.{$status}"),
                    'value' => $status,
                ];
            }, TrainingProposal::STATUSES),
            'kitaTypes'             => array_map(function ($type) {
                return [
                    'title' => __("validation.attributes.{$type}"),
                    'value' => $type,
                ];
            }, Kita::TYPES),
            'zipCodes'              => $zipCodesList,
            'from'                  => $request->input('from'),
        ]);
    }

    /**
     * @param CreateTrainingProposalRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateTrainingProposalRequest $request)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingProposalItemService->create(array_merge($attributes, [
            'status' => TrainingProposal::STATUS_OPEN,
        ]));

        return $result
            ? Redirect::back()->withSuccesses(__('crud.training_proposals.create_success'))
            : Redirect::back()->withErrors(__('crud.training_proposals.create_error'));
    }

    /**
     * @param UpdateTrainingProposalRequest $request
     * @param TrainingProposal              $trainingProposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTrainingProposalRequest $request, TrainingProposal $trainingProposal)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);
//        $this->authorize('authorizeAccessToSingleTrainingProposal', [User::class, $trainingProposal]);

        $currentUser = $request->user();

        $attributes = $request->validated();
        $result     = $this->trainingProposalItemService->update($trainingProposal->id, $attributes);

        if ($currentUser->is_user_multiplier && !empty($attributes['status']) && $attributes['status'] === TrainingProposal::STATUS_OPEN) {
            // Get the previous URL (the page from which the request came)
            $previousUrl = url()->previous();

            // Parse the URL to get the path component (excluding domain and query parameters)
            $previousPath = parse_url($previousUrl, PHP_URL_PATH);

            // Match the route based on the path to get the corresponding Route object
            $previousRoute = Route::getRoutes()->match(Request::create($previousPath));

            // Now $previousRoute contains the Route object if a match is found
            $previousRouteName = $previousRoute->getName(); // Get the name of the previous route

            if ($previousRouteName === 'training_proposals.index') {
                return $result
                    ? Redirect::back()->withSuccesses(__('crud.training_proposals.update_success'))
                    : Redirect::back()->withErrors(__('crud.training_proposals.update_error'));
            } else {
                return $result
                    ? Redirect::route('training_proposals.index')
                    : Redirect::route('training_proposals.index');
            }
        } else {
            return $result
                ? Redirect::back()->withSuccesses(__('crud.training_proposals.update_success'))
                : Redirect::back()->withErrors(__('crud.training_proposals.update_error'));
        }
    }

    /**
     * @param Request          $request
     * @param TrainingProposal $trainingProposal
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request, TrainingProposal $trainingProposal)
    {
//        $this->authorize('authorizeAccessToTrainingProposals', User::class);
//        $this->authorize('authorizeAccessToSingleTrainingProposal', [User::class, $trainingProposal]);

        $token = $request->input('token', '');

        $result = $this->trainingProposalItemService->confirm($trainingProposal->id, $token);

        return $result
            ? Inertia::render('Auth/ConfirmedTrainingProposal', ['trainingProposal' => $trainingProposal])
            : Redirect::route('profile.edit')->withErrors(__('crud.training_proposals.confirm_error'));
    }

    public function confirmByAdmin(Request $request, TrainingProposal $trainingProposal)
    {
        $kitaId = $request->input('kita_id');

        if (empty($kitaId)) {
            \Log::warning("confirmByAdmin fehlgeeschlagen: Kita-ID ist leer oder fehlt.");
            return Redirect::back()->withErrors(['error' => 'Kita-ID ist erforderlich.']);
        }

        $result = $this->trainingProposalItemService->confirmByAdmin($trainingProposal->id, $kitaId);

        if ($result) {
            return Redirect::back()->with('success', 'Training erfolgreich bestätigt.');
        }

        \Log::error("confirmByAdmin fehlgeeschlagen: Bestätigung des TrainingProposals fehlgeschlagen für Kita-ID: $kitaId");
        return Redirect::back()->withErrors(['error' => 'Die Bestätigung ist fehlgeschlagen.']);
    }

    /**
     * @param AddKitaToTrainingProposalRequest $request
     * @param TrainingProposal                 $trainingProposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addKita(AddKitaToTrainingProposalRequest $request, TrainingProposal $trainingProposal)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingProposalItemService->updateAttachedKitas($trainingProposal->id, [$attributes['kita']]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.training_proposals.update_success'))
            : Redirect::back()->withErrors(__('crud.training_proposals.update_error'));
    }

    /**
     * @param AddMultiplierToTrainingProposalRequest $request
     * @param TrainingProposal                       $trainingProposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMultiplier(AddMultiplierToTrainingProposalRequest $request, TrainingProposal $trainingProposal)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingProposalItemService->update($trainingProposal->id, array_merge($attributes, [
            'status' => TrainingProposal::STATUS_RESERVED,
        ]));

        return $result
            ? Redirect::back()->withSuccesses(__('crud.training_proposals.update_success'))
            : Redirect::back()->withErrors(__('crud.training_proposals.update_error'));
    }

    /**
     * @param AddKitasToTrainingProposalRequest $request
     * @param TrainingProposal                  $trainingProposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addKitas(AddKitasToTrainingProposalRequest $request, TrainingProposal $trainingProposal)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingProposalItemService->updateAttachedKitas($trainingProposal->id, $attributes['kitas']);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.training_proposals.update_success'))
            : Redirect::back()->withErrors(__('crud.training_proposals.update_error'));
    }

    /**
     * @param RemoveKitaFromTrainingProposalRequest $request
     * @param TrainingProposal                      $trainingProposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeKita(RemoveKitaFromTrainingProposalRequest $request, TrainingProposal $trainingProposal)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingProposalItemService->updateAttachedKitas($trainingProposal->id, [$attributes['kita']], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.training_proposals.update_success'))
            : Redirect::back()->withErrors(__('crud.training_proposals.update_error'));
    }

    /**
     * @param RemoveKitasFromTrainingProposalRequest $request
     * @param TrainingProposal                       $trainingProposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeKitas(RemoveKitasFromTrainingProposalRequest $request, TrainingProposal $trainingProposal)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);

        $attributes = $request->validated();
        $result     = $this->trainingProposalItemService->updateAttachedKitas($trainingProposal->id, $attributes['kitas'], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.training_proposals.update_success'))
            : Redirect::back()->withErrors(__('crud.training_proposals.update_error'));
    }

    /**
     * @param Request          $request
     * @param TrainingProposal $trainingProposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, TrainingProposal $trainingProposal)
    {
        $this->authorize('authorizeAccessToTrainingProposals', User::class);
        $this->authorize('authorizeAccessToSingleTrainingProposal', [User::class, $trainingProposal]);

        $result = $this->trainingProposalItemService->delete($trainingProposal->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.training_proposals.delete_success'))
            : Redirect::back()->withErrors(__('crud.training_proposals.delete_error'));
    }
}
