<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Models\User;
use App\Services\Items\DownloadableFilesItemService;
use App\Services\Items\SettingItemService;
use App\Services\Items\SurveyTimePeriodItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Download Area Controller
 *
 * @package \App\Http\Controllers
 */
class DownloadAreaController extends BaseController
{
    /**
     * @var DownloadableFilesItemService
     */
    public $downloadableFilesItemService;

    /**
     * DownloadAreaController constructor.
     *
     * @param DownloadableFilesItemService $downloadableFilesItemService
     * @return void
     */
    public function __construct(DownloadableFilesItemService $downloadableFilesItemService)
    {
        $this->downloadableFilesItemService = $downloadableFilesItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToDownloadArea', User::class);

        $args = $request->only(['page', 'per_page', 'sort', 'order_by', 'search']);

        $result = $this->downloadableFilesItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('DownloadableFiles/DownloadArea', $this->prepareItemsCollection($result, [
            'filters' => $request->only(['search']),
        ]));
    }
}