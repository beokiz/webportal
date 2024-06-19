<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Users\CreateUserFromKitaRequest;
use App\Http\Requests\Users\CreateUserFromOperatorRequest;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use App\Services\Items\RoleItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
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
        $args        = $request->only(['page', 'per_page', 'sort', 'order_by', 'full_name', 'email']);

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
            'filters' => $request->only(['full_name', 'email']),
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

        $currentUser = $request->user();

        if (
            $currentUser->hasRole(config('permission.project_roles.admin')) &&
            $currentUser->id !== $user->id &&
            $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')])
        ) {
            return $this->accessDeniedResponse();
        }

        $rolesFilters = [];

        if ($currentUser->is_admin) {
            $rolesFilters['exclude_name'] = [config('permission.project_roles.super_admin'), config('permission.project_roles.admin')];
        }

        return Inertia::render('Users/Partials/ManageUser', [
            'user'  => $user,
            'roles' => $this->roleItemService->collection($rolesFilters),
            'from'  => $request->input('from'),
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
            'password' => Str::random(20),
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
