<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Milestones\CreateMilestoneRequest;
use App\Http\Requests\Milestones\ReorderMilestonesRequest;
use App\Http\Requests\Milestones\UpdateMilestoneRequest;
use App\Models\Milestone;
use App\Models\User;
use App\Services\Items\MilestoneItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Milestones Controller
 *
 * @package \App\Http\Controllers
 */
class MilestonesController extends BaseController
{
    /**
     * @var MilestoneItemService
     */
    protected $milestoneItemService;

    /**
     * MilestonesController constructor.
     *
     * @param MilestoneItemService $milestoneItemService
     * @return void
     */
    public function __construct(MilestoneItemService $milestoneItemService)
    {
        $this->milestoneItemService = $milestoneItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $args = $request->only(['page', 'per_page', 'sort', 'order_by', 'search']);

        $result = $this->milestoneItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('Milestones/Milestones', $this->prepareItemsCollection($result, [
            'filters' => $request->only(['search']),
        ]));
    }

    /**
     * @param Request   $request
     * @param Milestone $milestone
     * @return \Inertia\Response
     */
    public function show(Request $request, Milestone $milestone)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        return Inertia::render('Domains/Partials/ManageMilestone', [
            'milestone' => $milestone,
            'from'      => $request->input('from'),
        ]);
    }

    /**
     * @param CreateMilestoneRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateMilestoneRequest $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->milestoneItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.milestones.create_success'))
            : Redirect::back()->withErrors(__('crud.milestones.create_error'));
    }

    /**
     * @param UpdateMilestoneRequest $request
     * @param Milestone              $milestone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateMilestoneRequest $request, Milestone $milestone)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->milestoneItemService->update($milestone->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.milestones.update_success'))
            : Redirect::back()->withErrors(__('crud.milestones.update_error'));
    }

    /**
     * @param Request   $request
     * @param Milestone $milestone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Milestone $milestone)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $result = $this->milestoneItemService->delete($milestone->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.milestones.delete_success'))
            : Redirect::back()->withErrors(__('crud.milestones.delete_error'));
    }

    /**
     * @param ReorderMilestonesRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reorder(ReorderMilestonesRequest $request)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->milestoneItemService->reorder($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.milestones.reorder_success'))
            : Redirect::back()->withErrors(__('crud.milestones.reorder_error'));
    }
}
