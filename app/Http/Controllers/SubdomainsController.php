<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Subdomains\CreateSubdomainRequest;
use App\Http\Requests\Subdomains\ReorderSubdomainsRequest;
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

        $args = $request->only(['page', 'per_page', 'sort', 'order_by', 'search']);

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

        return Inertia::render('Domains/Partials/ManageSubdomain', [
            'subdomain' => $subdomain->loadMissing([
                'domain',
                'milestones' => function ($query) {
                    $query->orderBy('order');
                },
            ]),
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
            ? Redirect::back()->withSuccesses(__('crud.subdomains.create_success'))
            : Redirect::back()->withErrors(__('crud.subdomains.create_error'));
    }

    /**
     * @param UpdateSubdomainRequest $request
     * @param Subdomain              $subdomain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSubdomainRequest $request, Subdomain $subdomain)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->subdomainItemService->update($subdomain->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.subdomains.update_success'))
            : Redirect::back()->withErrors(__('crud.subdomains.update_error'));
    }

    /**
     * @param Request   $request
     * @param Subdomain $subdomain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Subdomain $subdomain)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $result = $this->subdomainItemService->delete($subdomain->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.subdomains.delete_success'))
            : Redirect::back()->withErrors(__('crud.subdomains.delete_error'));
    }

    /**
     * @param Request   $request
     * @param Subdomain $subdomain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, Subdomain $subdomain)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $result = $this->subdomainItemService->update($subdomain->id, [
            'deleted_at' => null,
        ]);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.subdomains.restore_success'))
            : Redirect::back()->withErrors(__('crud.subdomains.restore_error'));
    }

    /**
     * @param ReorderSubdomainsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reorder(ReorderSubdomainsRequest $request)
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->subdomainItemService->reorder($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.subdomains.reorder_success'))
            : Redirect::back()->withErrors(__('crud.subdomains.reorder_error'));
    }
}
