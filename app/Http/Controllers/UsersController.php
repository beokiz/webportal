<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\BaseInertiaResourceCollection;
use App\Models\User;
use App\Services\Items\RoleItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();
        $args        = $request->only(['page', 'per_page', 'sort', 'order_by', 'full_name', 'email']);

        $usersFilters = ['paginated' => true];
        $rolesFilters = [];

        if ($currentUser->is_admin) {
            $usersFilters['withoutRoles'] = [config('permission.project_roles.super_admin'), config('permission.project_roles.admin')];

            $rolesFilters['exclude_name'] = [config('permission.project_roles.super_admin'), config('permission.project_roles.admin')];
        }

        $result = $this->userItemService->collection(array_merge($args, $usersFilters));

        return Inertia::render('Users/Users', array_merge(BaseInertiaResourceCollection::make($result)->resolve(), [
            'roles'   => $this->roleItemService->collection($rolesFilters),
            'filters' => $request->only(['full_name', 'email']),
        ]));
    }

    /**
     * @param User $user
     * @return \Inertia\Response
     */
    public function show(User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        return Inertia::render('Users/Partials/ManageUser', [
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return \Inertia\Response
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        if ($currentUser->hasRole(config('permission.project_roles.admin')) && $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')])) {
            throw new AccessDeniedHttpException();
        }

        $rolesFilters = [];

        if ($currentUser->is_admin) {
            $rolesFilters = [
                'exclude_name' => [config('permission.project_roles.super_admin'), config('permission.project_roles.admin')],
            ];
        }

        return Inertia::render('Users/Partials/ManageUser', [
            'user'  => $user,
            'roles' => $this->roleItemService->collection($rolesFilters),
        ]);
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->userItemService->create(array_merge($attributes, [
            'password' => Str::random(20),
        ]));

        return $result
            ? Redirect::back()->withSuccesses(__('crud.user.create_success'))
            : Redirect::back()->withErrors(__('crud.user.create_error'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        if ($currentUser->hasRole(config('permission.project_roles.admin')) && $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')])) {
            throw new AccessDeniedHttpException();
        }

        $attributes = $request->validated();
        $result     = $this->userItemService->update($user->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.user.update_success'))
            : Redirect::back()->withErrors(__('crud.user.update_error'));
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        if ($currentUser->hasRole(config('permission.project_roles.admin')) && $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')])) {
            throw new AccessDeniedHttpException();
        }

        if (
            !$user->is_super_admin &&
            $request->user()->id !== (int) $user->id
        ) {
            $result = $this->userItemService->delete($user->id);

            return $result
                ? Redirect::back()->withSuccesses(__('crud.user.delete_success'))
                : Redirect::back()->withErrors(__('crud.user.delete_error'));
        } else {
            return Redirect::back()->withErrors(__('crud.user.delete_denied'));
        }
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        if ($currentUser->hasRole(config('permission.project_roles.admin')) && $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')])) {
            throw new AccessDeniedHttpException();
        }

        $result = $this->userItemService->update($user->id, [
            'deleted_at' => null,
        ]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.user.restore_success'))
            : Redirect::back()->withErrors(__('crud.user.restore_error'));
    }
}
