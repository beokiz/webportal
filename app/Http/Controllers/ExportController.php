<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Exports\EvaluationsExport;
use App\Models\Domain;
use App\Models\User;
use App\Services\Items\DomainItemService;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

            $finishedAfter  = $request->input('finished_after');
            $finishedBefore = $request->input('finished_before');
            $age            = $request->input('age');
            $zipCode        = $request->input('zip_code');
            $domains        = $request->input('domains');

            $filename = 'BeoKiz-Export_evaluations';

            if (!empty($finishedAfter)) {
                $filename .= '_' . Carbon::make($finishedAfter)->format('Ymd');
            }

            if (!empty($finishedBefore)) {
                $filename .= '_' . Carbon::make($finishedBefore)->format('Ymd');
            }

            if (!empty($domains)) {
                $filename .= '_' . Str::slug(
                        Domain::whereIn('id', (array) $domains)
                            ->pluck('name')
                            ->implode(','),
                        '_'
                    );
            } else {
                $filename .= '_alle';
            }

            if (!empty($age)) {
                $filename .= '_' . Str::slug($age, '_');
            }

            if (!empty($zipCode)) {
                $filename .= '_' . Str::slug($zipCode, '_');
            }

            return Excel::download(new EvaluationsExport([
                'user'            => $request->user(),
                'finished_after'  => $finishedAfter,
                'finished_before' => $finishedBefore,
                'age'             => $age,
                'zip_code'        => $zipCode,
                'domains'         => $domains,
            ]), "{$filename}.xlsx");
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors(__('evaluations.kitas.export_error'));
        }
    }
}
