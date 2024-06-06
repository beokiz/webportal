<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Models\Setting;
use App\Models\User;
use App\Services\Items\SettingItemService;
use Illuminate\Support\Facades\Redirect;

/**
 * Settings Controller
 *
 * @package \App\Http\Controllers
 */
class SettingsController extends BaseController
{
    /**
     * @var SettingItemService
     */
    protected $settingItemService;

    /**
     * SettingsController constructor.
     *
     * @param SettingItemService $settingItemService
     * @return void
     */
    public function __construct(SettingItemService $settingItemService)
    {
        $this->settingItemService = $settingItemService;
    }

    /**
     * @param UpdateSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateSettingsRequest $request)
    {
        $this->authorize('authorizeAccessToSettings', User::class);

        $attributes = $request->validated();
        $result     = $this->settingItemService->bulkUpdate($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.settings.update_success'))
            : Redirect::back()->withErrors(__('crud.settings.update_error'));
    }
}
