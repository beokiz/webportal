<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Kitas\ConnectUsersToKitaRequest;
use App\Http\Requests\Kitas\ConnectUserToKitaRequest;
use App\Http\Requests\Kitas\CreateKitaRequest;
use App\Http\Requests\Kitas\DisconnectUsersToKitaRequest;
use App\Http\Requests\Kitas\DisconnectUserToKitaRequest;
use App\Http\Requests\Kitas\ReorderKitasRequest;
use App\Http\Requests\Kitas\UpdateKitaRequest;
use App\Models\Kita;
use App\Services\Items\KitaItemService;
use App\Services\Items\RoleItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
//        $this->authorize('authorizeAdminAccess', User::class);

        $args = $request->only(['page', 'per_page', 'sort', 'order_by', 'search']);

        $result = $this->kitaItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('Kita/Kita', $this->prepareItemsCollection($result, [
            'filters' => $request->only(['search']),
        ]));
    }

    /**
     * @param Request $request
     * @param Kita    $kita
     * @return \Inertia\Response
     */
    public function show(Request $request, Kita $kita)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $roleItemService = app(RoleItemService::class);
        $userItemService = app(UserItemService::class);

        return Inertia::render('Kita/Partials/ManageKita', [
            'kita'  => $kita->loadMissing(['users']),
            'roles' => $roleItemService->collection(['only_name' => [config('permission.project_roles.manager'), config('permission.project_roles.employer')]]),
            'users' => $userItemService->collection(['with_roles' => [config('permission.project_roles.manager'), config('permission.project_roles.employer')]]),
        ]);
    }

    /**
     * @param CreateKitaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateKitaRequest $request)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

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
//        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->update($kita->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.update_success'))
            : Redirect::back()->withErrors(__('crud.kitas.update_error'));
    }

    /**
     * @param ConnectUserToKitaRequest $request
     * @param Kita                     $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectUser(ConnectUserToKitaRequest $request, Kita $kita)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

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
//        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->updateAttachedUsers($kita->id, $attributes['users']);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.update_success'))
            : Redirect::back()->withErrors(__('crud.kitas.update_error'));
    }

    /**
     * @param DisconnectUserToKitaRequest $request
     * @param Kita                        $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectUser(DisconnectUserToKitaRequest $request, Kita $kita)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->updateAttachedUsers($kita->id, [$attributes['user']], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.update_success'))
            : Redirect::back()->withErrors(__('crud.kitas.update_error'));
    }

    /**
     * @param DisconnectUsersToKitaRequest $request
     * @param Kita                         $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectUsers(DisconnectUsersToKitaRequest $request, Kita $kita)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

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
//        $this->authorize('authorizeAdminAccess', User::class);

        if ($kita->users()->exists()) {
            return Redirect::back()->withErrors(__('crud.kitas.delete_users_denied'));
        }

        $result = $this->kitaItemService->delete($kita->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.delete_success'))
            : Redirect::back()->withErrors(__('crud.kitas.delete_error'));
    }

    /**
     * @param Request $request
     * @param Kita    $kita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, Kita $kita)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $result = $this->kitaItemService->update($kita->id, [
            'deleted_at' => null,
        ]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.restore_success'))
            : Redirect::back()->withErrors(__('crud.kitas.restore_error'));
    }

    /**
     * @param ReorderKitasRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reorder(ReorderKitasRequest $request)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->kitaItemService->reorder($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.kitas.reorder_success'))
            : Redirect::back()->withErrors(__('crud.kitas.reorder_error'));
    }
}
