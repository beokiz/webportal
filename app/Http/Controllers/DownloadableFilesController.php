<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\DownloadableFiles\CreateDownloadableFileRequest;
use App\Http\Requests\DownloadableFiles\UpdateDownloadableFileRequest;
use App\Models\DownloadableFile;
use App\Models\User;
use App\Services\Items\DownloadableFilesItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Downloadable Files Controller
 *
 * @package \App\Http\Controllers
 */
class DownloadableFilesController extends BaseController
{
    /**
     * @var DownloadableFilesItemService
     */
    protected $downloadableFilesItemService;

    /**
     * DownloadableFilesController constructor.
     *
     * @param DownloadableFilesItemService $downloadableFilesItemService
     * @return void
     */
    public function __construct(DownloadableFilesItemService $downloadableFilesItemService)
    {
        $this->downloadableFilesItemService = $downloadableFilesItemService;
    }

    /**
     * @param Request          $request
     * @param DownloadableFile $downloadableFile
     * @return \Inertia\Response
     */
    public function show(Request $request, DownloadableFile $downloadableFile)
    {
        $this->authorize('authorizeAccessToDownloadableFiles', User::class);

        return Inertia::render('DownloadableFiles/Partials/ManageDownloadableFile', [
            'downloadableFile' => $downloadableFile,
        ]);
    }

    /**
     * @param CreateDownloadableFileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateDownloadableFileRequest $request)
    {
        $this->authorize('authorizeAccessToDownloadableFiles', User::class);

        $attributes = $request->validated();
        $result     = $this->downloadableFilesItemService->create($attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.downloadable_files.create_success'))
            : Redirect::back()->withErrors(__('crud.downloadable_files.create_error'));
    }

    /**
     * @param UpdateDownloadableFileRequest $request
     * @param DownloadableFile              $downloadableFile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateDownloadableFileRequest $request, DownloadableFile $downloadableFile)
    {
        $this->authorize('authorizeAccessToDownloadableFiles', User::class);

        $attributes = $request->validated();
        $result     = $this->downloadableFilesItemService->update($downloadableFile->id, $attributes);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.downloadable_files.update_success'))
            : Redirect::back()->withErrors(__('crud.downloadable_files.update_error'));
    }

    /**
     * @param Request          $request
     * @param DownloadableFile $downloadableFile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, DownloadableFile $downloadableFile)
    {
        $this->authorize('authorizeAccessToDownloadableFiles', User::class);

        $result = $this->downloadableFilesItemService->delete($downloadableFile->id);

        return $result
            ? Redirect::back()->withSuccesses(__('crud.downloadable_files.delete_success'))
            : Redirect::back()->withErrors(__('crud.downloadable_files.delete_error'));
    }
}