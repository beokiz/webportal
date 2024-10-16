<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Users\ConnectKitasToUserRequest;
use App\Http\Requests\Users\ConnectKitaToUserRequest;
use App\Http\Requests\Users\ConnectOperatorsToUserRequest;
use App\Http\Requests\Users\ConnectOperatorToUserRequest;
use App\Http\Requests\Users\CreateUserFromKitaRequest;
use App\Http\Requests\Users\CreateUserFromOperatorRequest;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\DisconnectKitaFromUserRequest;
use App\Http\Requests\Users\DisconnectKitasFromUserRequest;
use App\Http\Requests\Users\DisconnectOperatorFromUserRequest;
use App\Http\Requests\Users\DisconnectOperatorsFromUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Kita;
use App\Models\User;
use App\Services\Items\KitaItemService;
use App\Services\Items\OperatorItemService;
use App\Services\Items\RoleItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * Users Controller
 *
 * @package \App\Http\Controllers
 */
class UsersController extends BaseController
{
    /**
     * @var UserItemService
     */
    protected $userItemService;

    /**
     * @var RoleItemService
     */
    protected $roleItemService;

    /**
     * UsersController constructor.
     *
     * @param UserItemService $userItemService
     * @param RoleItemService $roleItemService
     * @return void
     */
    public function __construct(UserItemService $userItemService, RoleItemService $roleItemService)
    {
        $this->userItemService = $userItemService;
        $this->roleItemService = $roleItemService;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function accessDeniedResponse()
    {
        return Redirect::route('users.index')
            ->withErrors(__('exceptions.user_does_not_have_access'));
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToUsers', User::class);

        $currentUser = $request->user()->loadMissing(['kitas']);
        $args        = $request->only(['page', 'per_page', 'sort', 'order_by', 'status', 'full_name', 'email', 'with_roles', 'first_login_at', 'last_seen_at']);

        $usersFilters = ['paginated' => true];
        $rolesFilters = [];

        if ($currentUser->is_admin) {
            $rolesFilters['exclude_name'] = [config('permission.project_roles.super_admin'), config('permission.project_roles.admin')];
//            $usersFilters['without_roles'] = [config('permission.project_roles.super_admin'), config('permission.project_roles.admin')];
        } else if ($currentUser->is_manager) {
            $rolesFilters['only_name']  = [config('permission.project_roles.manager'), config('permission.project_roles.employer')];
            $usersFilters['with_roles'] = [config('permission.project_roles.manager'), config('permission.project_roles.employer')];

            // ID 999999 is used so that if no Kita is associated with the manager, it does not display a list of all users
            $usersFilters['with_kitas'] = $currentUser->kitas->count() > 0 ? $currentUser->kitas->pluck('id') : 999999;
        } else {
            //
        }

        $result = $this->userItemService->collection(array_merge($args, $usersFilters));

        return Inertia::render('Users/Users', $this->prepareItemsCollection($result, [
            'roles'   => $this->roleItemService->collection($rolesFilters),
            'filters' => $request->only(['status', 'full_name', 'email', 'with_roles', 'first_login_at', 'last_seen_at']),
        ]));
    }

    /**
     * @param User $user
     * @return \Inertia\Response
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        return Inertia::render('Users/Partials/ManageUser', [
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        // Check user can be edited
        $currentUser = $request->user();

        if (
            $currentUser->hasRole(config('permission.project_roles.admin')) &&
            $currentUser->id !== $user->id &&
            $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')])
        ) {
            return $this->accessDeniedResponse();
        }

        $kitaItemService     = app(KitaItemService::class);
        $operatorItemService = app(OperatorItemService::class);

        // Get params for sorting & filtering Kitas & Operators
        $kitaArgs     = $request->only(['kita_args']);
        $operatorArgs = $request->only(['operator_args']);

        $userKitas     = $kitaItemService->collection(array_merge($kitaArgs['kita_args'] ?? [], ['paginated' => false, 'with_users' => [$user->id], 'with' => ['users', 'currentYearlyEvaluations']]));
        $userOperators = $operatorItemService->collection(array_merge($operatorArgs['operator_args'] ?? [], ['paginated' => false, 'with_users' => [$user->id]]));

        // Fetch all zip codes from kitas
        $zipCodesList = $userKitas->pluck('zip_code')->unique()->transform(function ($zipCode) {
            return [
                'title' => $zipCode,
                'value' => $zipCode,
            ];
        })->values()->toArray();

        $rolesFilters = [];

        if ($currentUser->is_admin) {
            $rolesFilters['exclude_name'] = [config('permission.project_roles.super_admin'), config('permission.project_roles.admin')];
        }

        return Inertia::render('Users/Partials/ManageUser', [
            'user'            => $user,
            'userKitas'       => $userKitas,
            'userOperators'   => $userOperators,
//            'usersEmails'   => (!empty($operatorKitas) && $operatorKitas->isNotEmpty()) ? $kitaItemService->getUsersEmails($operatorKitas->pluck('id')->toArray()) : [],
            'roles'           => $this->roleItemService->collection($rolesFilters),
            'kitas'           => $kitaItemService->collection(['paginated' => false]),
            'operators'       => $operatorItemService->collection(['paginated' => false]),
            'kitaFilters'     => $kitaArgs['kita_args'] ?? [],
            'operatorFilters' => $operatorArgs['operator_args'] ?? [],
            'kitaTypes'       => array_map(function ($type) {
                return [
                    'title' => __("validation.attributes.{$type}"),
                    'value' => $type,
                ];
            }, Kita::TYPES),
            'zipCodes'        => $zipCodesList,
            'from'            => $request->input('from'),
        ]);
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToUsers', User::class);

        $attributes = $request->validated();
        $result     = $this->userItemService->create(array_merge($attributes, [
            'email_verified_at' => null, // OLD: Carbon::now()
            'password'          => Str::random(20),
        ]));

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.create_success'))
            : Redirect::back()->withErrors(__('crud.users.create_error'));
    }

    /**
     * @param CreateUserFromKitaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFromKita(CreateUserFromKitaRequest $request)
    {
        $this->authorize('authorizeAccessToUsers', User::class);

        $attributes = $request->validated();
        $result     = $this->userItemService->createFromKitaOrOperator($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.create_success'))
            : Redirect::back()->withErrors(__('crud.users.create_error'));
    }

    /**
     * @param CreateUserFromOperatorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFromOperator(CreateUserFromOperatorRequest $request)
    {
        $this->authorize('authorizeAccessToUsers', User::class);

        $attributes = $request->validated();
        $result     = $this->userItemService->createFromKitaOrOperator($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.create_success'))
            : Redirect::back()->withErrors(__('crud.users.create_error'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $currentUser = $request->user();

        if (
            $currentUser->hasRole(config('permission.project_roles.admin')) &&
            $currentUser->id !== $user->id &&
            $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')])
        ) {
            return $this->accessDeniedResponse();
        }

        $attributes = $request->validated();
        $result     = $this->userItemService->update($user->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function sendVerificationLink(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        if (empty($user->email_verified_at)) {
            $user->sendEmailVerificationNotification();

            return Redirect::back()->withSuccesses(__('crud.users.send_verification_link_success'));
        }

        return Redirect::back()->withErrors(__('crud.users.send_verification_link_denied'));
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function sendWelcomeNotification(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $user->sendEmailVerificationNotification();

        return Redirect::back()->withSuccesses(__('crud.users.welcome_notification_success'));
    }

    /**
     * @param ConnectKitaToUserRequest $request
     * @param User                     $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectKita(ConnectKitaToUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $attributes = $request->validated();
        $result     = $this->userItemService->updateAttachedKitas($user->id, [$attributes['kita']]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param ConnectKitasToUserRequest $request
     * @param User                      $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectKitas(ConnectKitasToUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $attributes = $request->validated();
        $result     = $this->userItemService->updateAttachedKitas($user->id, $attributes['kitas']);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param DisconnectKitaFromUserRequest $request
     * @param User                          $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectKita(DisconnectKitaFromUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $attributes = $request->validated();
        $result     = $this->userItemService->updateAttachedKitas($user->id, [$attributes['kita']], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param DisconnectKitasFromUserRequest $request
     * @param User                           $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectKitas(DisconnectKitasFromUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $attributes = $request->validated();
        $result     = $this->userItemService->updateAttachedKitas($user->id, $attributes['kitas'], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param ConnectOperatorToUserRequest $request
     * @param User                         $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectOperator(ConnectOperatorToUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $attributes = $request->validated();
        $result     = $this->userItemService->updateAttachedOperators($user->id, [$attributes['operator']]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param ConnectOperatorsToUserRequest $request
     * @param User                          $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectOperators(ConnectOperatorsToUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $attributes = $request->validated();
        $result     = $this->userItemService->updateAttachedOperators($user->id, $attributes['operators']);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param DisconnectOperatorFromUserRequest $request
     * @param User                              $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectOperator(DisconnectOperatorFromUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $attributes = $request->validated();
        $result     = $this->userItemService->updateAttachedOperators($user->id, [$attributes['operator']], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param DisconnectOperatorsFromUserRequest $request
     * @param User                               $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectOperators(DisconnectOperatorsFromUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $attributes = $request->validated();
        $result     = $this->userItemService->updateAttachedOperators($user->id, $attributes['operators'], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.users.update_success'))
            : Redirect::back()->withErrors(__('crud.users.update_error'));
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);
//        $this->authorize('authorizeAccessToSingleUser', [User::class, $user->id]);

        $currentUser = $request->user();

        if (
            $currentUser->hasRole(config('permission.project_roles.admin')) &&
            $currentUser->id !== $user->id &&
            $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')])
        ) {
            return $this->accessDeniedResponse();
        }

        if (
            !$user->hasRole(config('permission.project_roles.super_admin')) &&
            $request->user()->id !== (int) $user->id
        ) {
            $result = $this->userItemService->delete($user->id);

            return $result
                ? Redirect::back()->withSuccesses(__('crud.users.delete_success'))
                : Redirect::back()->withErrors(__('crud.users.delete_error'));
        } else {
            return Redirect::back()->withErrors(__('crud.users.delete_denied'));
        }
    }
}
