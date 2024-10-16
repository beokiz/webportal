<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Kitas\ConnectUsersToKitaRequest;
use App\Http\Requests\Kitas\ConnectUserToKitaRequest;
use App\Http\Requests\Kitas\CreateKitaRequest;
use App\Http\Requests\Kitas\DisconnectUserFromKitaRequest;
use App\Http\Requests\Kitas\DisconnectUsersFromKitaRequest;
use App\Http\Requests\Kitas\ReorderKitasRequest;
use App\Http\Requests\Kitas\UpdateKitaRequest;
use App\Models\Kita;
use App\Models\Operator;
use App\Models\User;
use App\Services\Items\KitaItemService;
use App\Services\Items\OperatorItemService;
use App\Services\Items\RoleItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

/**
 * Kita Controller
 *
 * @package \App\Http\Controllers
 */
class KitaController extends BaseController
{
    /**
     * @var KitaItemService
     */
    protected $kitaItemService;

    /**
     * KitaController constructor.
     *
     * @param KitaItemService $kitaItemService
     * @return void
     */
    public function __construct(KitaItemService $kitaItemService)
    {
        $this->kitaItemService = $kitaItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToKitas', User::class);

        $operatorItemService = app(OperatorItemService::class);

        $currentUser = $request->user();

        $args = $request->only([
            'page', 'per_page', 'sort', 'order_by', 'search', 'has_yearly_evaluations', 'approved', 'operator_id',
            'other_operator', 'type', 'zip_code',
        ]);

        if ($currentUser->is_manager) {
            $args['with_users'] = $currentUser->id;
        } else if ($currentUser->is_user_multiplier) {
            $currentUser->loadMissing(['operators']);

            $currentUserOperators = $currentUser->operators->pluck('id')
                ->flatten()
                ->unique()
                ->toArray();

            // If there are no operators, we don't return anything
            $args['with_operators'] = !empty($currentUserOperators) ? $currentUserOperators : [-1];
        } else {
            //
        }

        $result = $this->kitaItemService->collection(array_merge($args, [
            'paginated' => true,
            'with'      => ['operator', 'currentYearlyEvaluations', 'users.roles'],
        ]));

        // Fetch all zip codes from kitas
        $zipCodesList = Kita::pluck('zip_code')->unique()->transform(function ($zipCode) {
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

        // Empty model for empty select option
        if ($currentUser->is_super_admin || $currentUser->is_admin || $currentUser->is_manager) {
            $emptyOperator = tap(new Operator(), function ($model) {
                $model->id   = null;
                $model->name = 'Sonstiger Träger';
            });
        }

        return Inertia::render('Kitas/Kitas', $this->prepareItemsCollection($result, [
            'filters'     => $request->only(['search', 'has_yearly_evaluations', 'approved', 'operator_id', 'other_operator', 'type', 'zip_code']),
            'zipCodes'    => $zipCodesList,
            'operators'   => !empty($emptyOperator) ? $operators->prepend($emptyOperator) : $operators,
            'usersEmails' => $this->kitaItemService->getUsersEmails($result->pluck('id')->toArray()),
            'types'       => array_map(function ($type) {
                return [
                    'title' => __("validation.attributes.{$type}"),
                    'value' => $type,
                ];
            }, Kita::TYPES),
        ]));
    }

    /**
     * @param Request $request
     * @param Kita    $kita
     * @return \Inertia\Response
     */
    public function show(Request $request, Kita $kita)
    {
//        $this->authorize('authorizeAccessToKitas', User::class);
        $this->authorize('authorizeAccessToSingleKita', [User::class, $kita->id]);

        $currentUser = $request->user();

        $kita->loadMissing(['users', 'currentYearlyEvaluations']);

        $roleItemService     = app(RoleItemService::class);
        $userItemService     = app(UserItemService::class);
        $operatorItemService = app(OperatorItemService::class);

        // Get params for sorting & filtering Kita users
        $userArgs = $request->only(['sort', 'order_by', 'status', 'first_name', 'last_name', 'email', 'with_roles']);

        // Empty model for empty select option
        if ($currentUser->is_super_admin || $currentUser->is_admin || $currentUser->is_manager) {
            $emptyOperator = tap(new Operator(), function ($model) {
                $model->id   = null;
                $model->name = 'Sonstiger Träger';
            });
        }

        // Prepare operators list
        if ($currentUser->is_user_multiplier) {
            $currentUser->loadMissing(['operators']);

            $operators = $currentUser->operators;
        } else {
            $operators = $operatorItemService->collection();
        }

        //
        if ($currentUser->is_super_admin || $currentUser->is_admin || $currentUser->is_manager) {
            $canBeEdited = true;
        } else {
            $currentUser->loadMissing(['operators.kitas']);

            $canBeEdited = optional($currentUser->operators)->where('self_training', true)
                ->pluck('kitas.*.id')
                ->flatten()
                ->unique()
                ->contains($kita->id);
        }

        return Inertia::render('Kitas/Partials/ManageKita', [
            'filters'     => $request->only(['status', 'first_name', 'last_name', 'email', 'with_roles']),
            'kita'        => $kita,
            'kitaUsers'   => $userItemService->collection(array_merge($userArgs, ['paginated' => false, 'with_kitas' => [$kita->id]])),
            'usersEmails' => $this->kitaItemService->getUsersEmails([$kita->id]),
            'roles'       => $roleItemService->collection(['only_name' => [config('permission.project_roles.manager'), config('permission.project_roles.employer')]]),
            'users'       => $userItemService->collection(['with_roles' => [config('permission.project_roles.manager'), config('permission.project_roles.employer')]]),
            'operators'   => !empty($emptyOperator) ? $operators->prepend($emptyOperator) : $operators,
            'types'       => array_map(function ($type) {
                return [
                    'title' => __("validation.attributes.{$type}"),
                    'value' => $type,
                ];
            }, Kita::TYPES),
            'canBeEdited' => $canBeEdited,
            'from'        => $request->input('from'),
        ]);
    }

    /**
     * @param CreateKitaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateKitaRequest $request)
    {
        $this->authorize('authorizeAccessToKitas', User::class);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.create_success'))
            : Redirect::back()->withErrors(__('crud.kitas.create_error'));
    }

    /**
     * @param UpdateKitaRequest $request
     * @param Kita              $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateKitaRequest $request, Kita $kita)
    {
        $this->authorize('authorizeAccessToSingleKita', [User::class, $kita->id]);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->update($kita->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.update_success'))
            : Redirect::back()->withErrors(__('crud.kitas.update_error'));
    }

    /**
     * @param Request $request
     * @param Kita    $kita
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function sendKitaCertificateNotification(Request $request, Kita $kita)
    {
        $this->authorize('authorizeAccessToSingleKita', [User::class, $kita->id]);

        $pdfFile = $this->kitaItemService->generatePdfCertificate($kita->id);

        if (!empty($pdfFile)) {

        } else {

        }

        return $pdfFile ?
            Response::download($pdfFile, basename($pdfFile), [
                'Content-Type'        => mime_content_type($pdfFile),
                'Content-Disposition' => 'attachment; filename="' . basename($pdfFile) . '"',
            ])
            : Redirect::back()->withErrors(__('crud.evaluations.pdf_error'));

//        $user->sendEmailVerificationNotification();
//
//        return Redirect::back()->withSuccesses(__('crud.users.welcome_notification_success'));
    }

    /**
     * @param ConnectUserToKitaRequest $request
     * @param Kita                     $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectUser(ConnectUserToKitaRequest $request, Kita $kita)
    {
        $this->authorize('authorizeAccessToSingleKita', [User::class, $kita->id]);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->updateAttachedUsers($kita->id, [$attributes['user']]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.update_success'))
            : Redirect::back()->withErrors(__('crud.kitas.update_error'));
    }

    /**
     * @param ConnectUsersToKitaRequest $request
     * @param Kita                      $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectUsers(ConnectUsersToKitaRequest $request, Kita $kita)
    {
        $this->authorize('authorizeAccessToSingleKita', [User::class, $kita->id]);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->updateAttachedUsers($kita->id, $attributes['users']);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.update_success'))
            : Redirect::back()->withErrors(__('crud.kitas.update_error'));
    }

    /**
     * @param DisconnectUserFromKitaRequest $request
     * @param Kita                          $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectUser(DisconnectUserFromKitaRequest $request, Kita $kita)
    {
        $this->authorize('authorizeAccessToSingleKita', [User::class, $kita->id]);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->updateAttachedUsers($kita->id, [$attributes['user']], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.update_success'))
            : Redirect::back()->withErrors(__('crud.kitas.update_error'));
    }

    /**
     * @param DisconnectUsersFromKitaRequest $request
     * @param Kita                           $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectUsers(DisconnectUsersFromKitaRequest $request, Kita $kita)
    {
        $this->authorize('authorizeAccessToSingleKita', [User::class, $kita->id]);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->updateAttachedUsers($kita->id, $attributes['users'], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.update_success'))
            : Redirect::back()->withErrors(__('crud.kitas.update_error'));
    }

    /**
     * @param Request $request
     * @param Kita    $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Kita $kita)
    {
        $this->authorize('authorizeAccessToSingleKita', [User::class, $kita->id]);

        if ($kita->users()->exists()) {
            return Redirect::back()->withErrors(__('crud.kitas.delete_users_denied'));
        }

        $result = $this->kitaItemService->delete($kita->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.delete_success'))
            : Redirect::back()->withErrors(__('crud.kitas.delete_error'));
    }

    /**
     * @param ReorderKitasRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reorder(ReorderKitasRequest $request)
    {
        $this->authorize('authorizeAccessToKitas', User::class);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->reorder($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.reorder_success'))
            : Redirect::back()->withErrors(__('crud.kitas.reorder_error'));
    }
}
