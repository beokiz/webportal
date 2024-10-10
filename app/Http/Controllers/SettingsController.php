<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Models\User;
use App\Services\Items\DownloadableFileItemService;
use App\Services\Items\SettingItemService;
use App\Services\Items\SurveyTimePeriodItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Settings Controller
 *
 * @package \App\Http\Controllers
 */
class SettingsController extends BaseController
{
    /**
     * SettingsController constructor.
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
        $this->authorize('authorizeAccessToSettings', User::class);

        $surveyTimePeriodItemService  = app(SurveyTimePeriodItemService::class);
        $settingItemService           = app(SettingItemService::class);
        $downloadableFilesItemService = app(DownloadableFileItemService::class);

        /*
         * Prepare collections args
         */
        $orderBy = $request->input('order_by');
        $sort    = $request->input('sort', 'asc');

        $surveyTimePeriodsOrderBy = 'id';
        $downloadableFilesOrderBy = 'id';

        switch ($orderBy) {
            case 'year':
            case 'age':
            case 'survey_start_date':
            case 'survey_end_date':
                $surveyTimePeriodsOrderBy = $orderBy;
                break;
            case 'name':
            case 'created_at':
                $downloadableFilesOrderBy = $orderBy;
                break;
        }

        /*
         * Get collections
         */
        $settings = $settingItemService->list();

        $surveyTimePeriods = $surveyTimePeriodItemService->collection(['order_by' => $surveyTimePeriodsOrderBy, 'sort' => $sort]);
        $downloadableFiles = $downloadableFilesItemService->collection(['order_by' => $downloadableFilesOrderBy, 'sort' => $sort]);

        $emailSettings = array_filter($settings, function ($value, $name) {
            return in_array($name, ['send_yearly_evaluation_reminder_ntf_before_days']);
        }, ARRAY_FILTER_USE_BOTH);

        $loginSettings = array_filter($settings, function ($value, $name) {
            return in_array($name, ['login_form_additional_html']);
        }, ARRAY_FILTER_USE_BOTH);

        $supportSettings = array_filter($settings, function ($value, $name) {
            return in_array($name, ['imprint_support_html']);
        }, ARRAY_FILTER_USE_BOTH);

        /*
         * Return results
         */
        return Inertia::render('Settings/Settings', [
            'filters'           => $request->only([]),
            'settings'          => $settingItemService->list(),
            'surveyTimePeriods' => $surveyTimePeriods,
            'downloadableFiles' => $downloadableFiles,
            'emailSettings'     => $emailSettings,
            'loginSettings'     => $loginSettings,
            'supportSettings'   => $supportSettings,
        ]);
    }

    /**
     * @param UpdateSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateSettingsRequest $request)
    {
        $this->authorize('authorizeAccessToSettings', User::class);

        $settingItemService = app(SettingItemService::class);

        $attributes = $request->validated();
        $result     = $settingItemService->bulkUpdate($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.settings.update_success'))
            : Redirect::back()->withErrors(__('crud.settings.update_error'));
    }
}
