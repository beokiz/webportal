<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Items\DownloadableFileItemService;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Download Area Controller
 *
 * @package \App\Http\Controllers
 */
class DownloadAreaController extends BaseController
{
    /**
     * @var DownloadableFileItemService
     */
    public $downloadableFileItemService;

    /**
     * DownloadAreaController constructor.
     *
     * @param DownloadableFileItemService $downloadableFileItemService
     * @return void
     */
    public function __construct(DownloadableFileItemService $downloadableFileItemService)
    {
        $this->downloadableFileItemService = $downloadableFileItemService;
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $this->authorize('authorizeAccessToDownloadArea', User::class);

        $args = $request->only(['page', 'per_page', 'sort', 'order_by', 'search']);

        $result = $this->downloadableFileItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return Inertia::render('DownloadableFiles/DownloadArea', $this->prepareItemsCollection($result, [
            'filters' => $request->only(['search']),
        ]));
    }
}
