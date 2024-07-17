<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Observers;

use App\Models\DownloadableFile;
use Illuminate\Support\Facades\Storage;

/**
 * Observer for DownloadableFile Model
 *
 * @package \App\Observers
 */
class DownloadableFileObserver extends BaseObserver
{
    /**
     * DownloadableFileObserver constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param DownloadableFile $downloadableFile
     * @return void
     */
    public function created(DownloadableFile $downloadableFile)
    {
        //
    }

    /**
     * @param DownloadableFile $downloadableFile
     * @return void
     */
    public function updated(DownloadableFile $downloadableFile)
    {
        // Delete old file from storage when record updated
        $oldFilename = $downloadableFile->getOriginal('filename');
        $newFilename = $downloadableFile->filename;

        if (!empty($oldFilename) && ($oldFilename !== $newFilename || empty($newFilename))) {
            Storage::disk('public_files')->delete($oldFilename);
        }
    }

    /**
     * @param DownloadableFile $downloadableFile
     * @return void
     */
    public function deleted(DownloadableFile $downloadableFile)
    {
        // Delete file from storage when record deleted
        if (!empty($downloadableFile->filename)) {
            Storage::disk('public_files')->delete($downloadableFile->filename);
        }
    }

    /**
     * @param DownloadableFile $downloadableFile
     * @return void
     */
    public function restored(DownloadableFile $downloadableFile)
    {
        //
    }

    /**
     * @param DownloadableFile $downloadableFile
     * @return void
     */
    public function forceDeleted(DownloadableFile $downloadableFile)
    {
        //
    }
}
