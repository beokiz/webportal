<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Domains\CreateDomainRequest;
use App\Http\Requests\Domains\UpdateDomainRequest;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Domain;
use App\Services\Items\DomainItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Domains Controller
 *
 * @package \App\Http\Controllers
 */
class DomainsController extends BaseController
{
    /**
     * @var DomainItemService
     */
    protected $domainItemService;

    /**
     * DomainsController constructor.
     *
     * @param DomainItemService $domainItemService
     * @return void
     */
    public function __construct(DomainItemService $domainItemService)
    {
        $this->domainItemService = $domainItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();
        $args        = $request->only(['page', 'per_page', 'sort', 'order_by', 'search']);

        $result = $this->domainItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('Domains/Domains', $this->prepareItemsCollection($result, [
            'filters' => $request->only(['search']),
        ]));
    }

    /**
     * @param Request $request
     * @param Domain  $domain
     * @return \Inertia\Response
     */
    public function show(Request $request, Domain $domain)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        return Inertia::render('Domains/Partials/ManageDomain', [
            'domain' => $domain->loadMissing(['subdomains']),
        ]);
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateDomainRequest $request)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->domainItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.domain.create_success'))
            : Redirect::back()->withErrors(__('crud.domain.create_error'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param Domain            $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateDomainRequest $request, Domain $user)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        $attributes = $request->validated();
        $result     = $this->domainItemService->update($user->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.domain.update_success'))
            : Redirect::back()->withErrors(__('crud.domain.update_error'));
    }

    /**
     * @param Request $request
     * @param Domain  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Domain $user)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        $result = $this->domainItemService->delete($user->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.domain.delete_success'))
            : Redirect::back()->withErrors(__('crud.domain.delete_error'));
    }

    /**
     * @param Request $request
     * @param Domain  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, Domain $user)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        $result = $this->domainItemService->update($user->id, [
            'deleted_at' => null,
        ]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.domain.restore_success'))
            : Redirect::back()->withErrors(__('crud.domain.restore_error'));
    }
}
