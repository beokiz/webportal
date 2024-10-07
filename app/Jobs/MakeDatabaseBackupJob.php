<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych, Pavel Karpushevskiy
 */

namespace App\Jobs;

use App\Helpers\MysqlDatabaseHelper;
use App\Mail\DatabaseBackupMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Make Database Backup Job
 *
 * @package \App\Jobs
 */
class MakeDatabaseBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        $backupEmail = config('app.emails.backup');

        if (!empty($backupEmail)) {
            $filePath = MysqlDatabaseHelper::export(config('database.default'), [
                '--no-tablespaces',
                '--column-statistics=0',
            ]);

            if (!empty($filePath)) {
                Mail::to($backupEmail)->send(
                    new DatabaseBackupMail([
                        'file_path' => $filePath,
                    ])
                );
            }
        }
    }
}
