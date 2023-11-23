<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Exports\EvaluationsExport;
use App\Models\User;
use App\Services\Items\DomainItemService;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * Export Controller
 *
 * @package \App\Http\Controllers
 */
class ExportController extends BaseController
{
    /**
     * ExportController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToExport', User::class);

        $domainItemService = app(DomainItemService::class);

        return Inertia::render('Export/Export', [
            'domains' => $domainItemService->collection(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function make(Request $request)
    {
        $this->authorize('authorizeAccessToExport', User::class);

        try {
            $uuid = Str::uuid();

            return Excel::download(new EvaluationsExport([
                'user'            => $request->user(),
                'finished_after'  => $request->input('finished_after'),
                'finished_before' => $request->input('finished_before'),
                'age'             => $request->input('age'),
                'zip_code'        => $request->input('zip_code'),
                'domains'         => $request->input('domains'),
            ]), "evaluations-{$uuid}.xlsx");
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors(__('evaluations.kitas.export_error'));
        }
    }
}
