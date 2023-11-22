<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Exports\EvaluationsExport;
use App\Models\User;
use App\Services\Items\KitaItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Excel;

/**
 * Export Controller
 *
 * @package \App\Http\Controllers
 */
class ExportController extends BaseController
{
    /**
     * @var KitaItemService
     */
    protected $kitaItemService;

    /**
     * ExportController constructor.
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
        $this->authorize('authorizeAccessToExport', User::class);

        return Inertia::render('Export/Export');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function make(Request $request)
    {
        $this->authorize('authorizeAccessToExport', User::class);

//        $attributes = $request->validated();
//        $result     = $this->kitaItemService->create($attributes);
//
//        return $result
//            ? Redirect::back()->withSuccesses(__('crud.kitas.create_success'))
//            : Redirect::back()->withErrors(__('crud.kitas.create_error'));

        try {
            $uuid = Str::uuid();

            return Excel::download(new EvaluationsExport, "evaluations-{$uuid}.xlsx");
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors(__('evaluations.kitas.export_error'));
        }
    }
}
