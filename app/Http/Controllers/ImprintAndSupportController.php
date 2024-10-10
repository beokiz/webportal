<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Items\DownloadableFileItemService;
use App\Services\Items\SettingItemService;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Imprint And Support Controller
 *
 * @package \App\Http\Controllers
 */
class ImprintAndSupportController extends BaseController
{
    /**
     * ImprintAndSupportController constructor.
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
        $this->authorize('authorizeAccessToImprintAndSupport', User::class);

        $settingItemService = app(SettingItemService::class);

        $imprintSupportHtmlSettings = $settingItemService->findByName('imprint_support_html', false);

        return Inertia::render('Other/ImprintAndSupport', [
            'imprintSupportHtml' => optional($imprintSupportHtmlSettings)->value,
        ]);
    }
}
