<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Subdomains\CreateSubdomainRequest;
use App\Http\Requests\Subdomains\UpdateSubdomainRequest;
use App\Models\Subdomain;
use App\Services\Items\SubdomainItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Subdomains Controller
 *
 * @package \App\Http\Controllers
 */
class SubdomainsController extends BaseController
{
    /**
     * @var SubdomainItemService
     */
    protected $subdomainItemService;

    /**
     * SubdomainsController constructor.
     *
     * @param SubdomainItemService $subdomainItemService
     * @return void
     */
    public function __construct(SubdomainItemService $subdomainItemService)
    {
        $this->subdomainItemService = $subdomainItemService;
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

        $result = $this->subdomainItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('Subdomains/Subdomains', $this->prepareItemsCollection($result, [
            'filters' => $request->only(['search']),
        ]));
    }

    /**
     * @param Request   $request
     * @param Subdomain $subdomain
     * @return \Inertia\Response
     */
    public function show(Request $request, Subdomain $subdomain)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        return Inertia::render('Subdomains/Partials/ManageSubdomain', [
            'subdomain' => $subdomain->loadMissing(['domain']),
        ]);
    }

    /**
     * @param CreateSubdomainRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateSubdomainRequest $request)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->subdomainItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.subdomain.create_success'))
            : Redirect::back()->withErrors(__('crud.subdomain.create_error'));
    }

    /**
     * @param UpdateSubdomainRequest $request
     * @param Subdomain              $subdomain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSubdomainRequest $request, Subdomain $subdomain)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        $attributes = $request->validated();
        $result     = $this->subdomainItemService->update($subdomain->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.subdomain.update_success'))
            : Redirect::back()->withErrors(__('crud.subdomain.update_error'));
    }

    /**
     * @param Request   $request
     * @param Subdomain $subdomain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Subdomain $subdomain)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        $result = $this->subdomainItemService->delete($subdomain->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.subdomain.delete_success'))
            : Redirect::back()->withErrors(__('crud.subdomain.delete_error'));
    }

    /**
     * @param Request   $request
     * @param Subdomain $subdomain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, Subdomain $subdomain)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $currentUser = $request->user();

        $result = $this->subdomainItemService->update($subdomain->id, [
            'deleted_at' => null,
        ]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.subdomain.restore_success'))
            : Redirect::back()->withErrors(__('crud.subdomain.restore_error'));
    }
}
