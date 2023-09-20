<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\BaseInertiaResourceCollection;
use App\Http\Resources\User\UserBaseResource;
use App\Models\User;
use App\Services\Items\RoleItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redirect;
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
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $user = $request->user();
        $args = $request->only(['page', 'per_page', 'sort', 'order_by', 'full_name', 'email']);

        $result = $this->userItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('Users', array_merge(BaseInertiaResourceCollection::make($result)->resolve(), [
            'roles'   => $this->roleItemService->collection(),
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

        return Inertia::render('Users', [
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return JsonResource
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        return UserBaseResource::make($user);
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->userItemService->create($attributes);

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

        // TODO: Refactor this code for better performance.
        if (
            !$user->is_super_admin ||
            ($user->is_super_admin && $request->user()->id === $user->id)
        ) {
            $attributes = $request->validated();
            $result     = $this->userItemService->update($user->id, $attributes);

            return $result
                ? Redirect::back()->withSuccesses(__('crud.user.update_success'))
                : Redirect::back()->withErrors(__('crud.user.update_error'));
        } else {
            return Redirect::back()->withErrors(__('crud.user.update_denied'));
        }
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        // TODO: Refactor this code for better performance.
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

        // TODO: Refactor this code for better performance.
        if (
            !$user->is_super_admin ||
            ($user->is_super_admin && $request->user()->id === $user->id)
        ) {
            $result = $this->userItemService->update($user->id, [
                'deleted_at' => null,
            ]);

            return $result
                ? Redirect::back()->withSuccesses(__('crud.user.restore_success'))
                : Redirect::back()->withErrors(__('crud.user.restore_error'));
        } else {
            return Redirect::back()->withErrors(__('crud.user.restore_denied'));
        }
    }
}
