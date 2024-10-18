<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Operators\ConnectKitasToOperatorRequest;
use App\Http\Requests\Operators\ConnectKitaToOperatorRequest;
use App\Http\Requests\Operators\ConnectUsersToOperatorRequest;
use App\Http\Requests\Operators\ConnectUserToOperatorRequest;
use App\Http\Requests\Operators\CreateOperatorRequest;
use App\Http\Requests\Operators\DisconnectKitaFromOperatorRequest;
use App\Http\Requests\Operators\DisconnectKitasFromOperatorRequest;
use App\Http\Requests\Operators\DisconnectUserFromOperatorRequest;
use App\Http\Requests\Operators\DisconnectUsersFromOperatorRequest;
use App\Http\Requests\Operators\UpdateOperatorRequest;
use App\Models\Kita;
use App\Models\Operator;
use App\Models\User;
use App\Services\Items\KitaItemService;
use App\Services\Items\OperatorItemService;
use App\Services\Items\RoleItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Operators Controller
 *
 * @package \App\Http\Controllers
 */
class OperatorsController extends BaseController
{
    /**
     * @var OperatorItemService
     */
    protected $operatorItemService;

    /**
     * @var KitaItemService
     */
    protected $kitaItemService;

    /**
     * OperatorsController constructor.
     *
     * @param OperatorItemService $operatorItemService
     * @param KitaItemService     $kitaItemService
     * @return void
     */
    public function __construct(OperatorItemService $operatorItemService, KitaItemService $kitaItemService)
    {
        $this->operatorItemService = $operatorItemService;
        $this->kitaItemService     = $kitaItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $args = $request->only(['page', 'per_page', 'sort', 'order_by']);

        $result = $this->operatorItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('Operators/Operators', $this->prepareItemsCollection($result, [
            'filters' => $request->only([]),
        ]));
    }

    /**
     * @param Request  $request
     * @param Operator $operator
     * @return \Inertia\Response
     */
    public function show(Request $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $roleItemService = app(RoleItemService::class);
        $kitaItemService = app(KitaItemService::class);
        $userItemService = app(UserItemService::class);

        // Get params for sorting & filtering Kitas & Users
        $userArgs = $request->only(['user_args']);
        $kitaArgs = $request->only(['kita_args']);

        $operatorUsers = $userItemService->collection(array_merge($userArgs['user_args'] ?? [], ['paginated' => false, 'with_operators' => [$operator->id]]));
        $operatorKitas = $kitaItemService->collection(array_merge($kitaArgs['kita_args'] ?? [], ['paginated' => false, 'with_operators' => [$operator->id], 'with' => ['users', 'currentYearlyEvaluations']]));

        // Fetch all zip codes from kitas
        $zipCodesList = $operatorKitas->pluck('zip_code')->unique()->transform(function ($zipCode) {
            return [
                'title' => $zipCode,
                'value' => $zipCode,
            ];
        })->values()->toArray();

        return Inertia::render('Operators/Partials/ManageOperator', [
            'operator'      => $operator,
            'operatorUsers' => $operatorUsers,
            'operatorKitas' => $operatorKitas,
            'usersEmails'   => (!empty($operatorKitas) && $operatorKitas->isNotEmpty()) ? $kitaItemService->getUsersEmails($operatorKitas->pluck('id')->toArray()) : [],
            'roles'         => $roleItemService->collection(['only_name' => [config('permission.project_roles.user_multiplier')]]),
            'users'         => $userItemService->collection(['with_roles' => [config('permission.project_roles.user_multiplier')]]),
            'userFilters'   => $userArgs['user_args'] ?? [],
            'kitaFilters'   => $kitaArgs['kita_args'] ?? [],
            'kitaTypes'     => array_map(function ($type) {
                return [
                    'title' => __("validation.attributes.{$type}"),
                    'value' => $type,
                ];
            }, Kita::TYPES),
            'zipCodes'      => $zipCodesList,
            'from'          => $request->input('from'),
        ]);
    }

    /**
     * @param CreateOperatorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateOperatorRequest $request)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.create_success'))
            : Redirect::back()->withErrors(__('crud.operators.create_error'));
    }

    /**
     * @param UpdateOperatorRequest $request
     * @param Operator              $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->update($operator->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param ConnectUserToOperatorRequest $request
     * @param Operator                     $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectUser(ConnectUserToOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->updateAttachedUsers($operator->id, [$attributes['user']]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param ConnectUsersToOperatorRequest $request
     * @param Operator                      $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectUsers(ConnectUsersToOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->updateAttachedUsers($operator->id, $attributes['users']);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param DisconnectUserFromOperatorRequest $request
     * @param Operator                          $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectUser(DisconnectUserFromOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->updateAttachedUsers($operator->id, [$attributes['user']], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param DisconnectUsersFromOperatorRequest $request
     * @param Operator                           $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectUsers(DisconnectUsersFromOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->updateAttachedUsers($operator->id, $attributes['users'], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param ConnectKitaToOperatorRequest $request
     * @param Operator                     $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectKita(ConnectKitaToOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->updateAttachedKitas($operator->id, [$attributes['kita']]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param ConnectKitasToOperatorRequest $request
     * @param Operator                      $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function connectKitas(ConnectKitasToOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->updateAttachedKitas($operator->id, $attributes['kitas']);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param DisconnectKitaFromOperatorRequest $request
     * @param Operator                          $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectKita(DisconnectKitaFromOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->updateAttachedKitas($operator->id, [$attributes['kita']], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param DisconnectKitasFromOperatorRequest $request
     * @param Operator                           $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectKitas(DisconnectKitasFromOperatorRequest $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $attributes = $request->validated();
        $result     = $this->operatorItemService->updateAttachedKitas($operator->id, $attributes['kitas'], true);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.update_success'))
            : Redirect::back()->withErrors(__('crud.operators.update_error'));
    }

    /**
     * @param Request  $request
     * @param Operator $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Operator $operator)
    {
        $this->authorize('authorizeAccessToOperators', User::class);

        $result = $this->operatorItemService->delete($operator->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.operators.delete_success'))
            : Redirect::back()->withErrors(__('crud.operators.delete_error'));
    }
}
