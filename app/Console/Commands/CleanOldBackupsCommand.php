<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych, Pavel Karpushevskiy
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

/**
 * Clean Old Backups Command
 *
 * @package \App\Console\Commands
 */
class CleanOldBackupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:old-backups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete backup folders older than two weeks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $backupPath = database_path('backups');
        $twoWeeksAgo = Carbon::now()->subWeeks(2);

        // Check if the backup directory exists
        if (!File::exists($backupPath)) {
            $this->info(__('commands.clean.no_backup_found_message'));
            return;
        }

        // Get the list of year directories inside the backup directory
        $years = File::directories($backupPath);

        foreach ($years as $yearDir) {
            $months = File::directories($yearDir);
            foreach ($months as $monthDir) {
                $days = File::directories($monthDir);
                foreach ($days as $dayDir) {
                    $folderName = basename($dayDir);
                    $folderDate = Carbon::createFromFormat('Y-m-d', basename($yearDir) . '-' . basename($monthDir) . '-' . $folderName);

                    // Delete the folder if its date is older than two weeks
                    if ($folderDate->lessThan($twoWeeksAgo)) {
                        if (File::deleteDirectory($dayDir)) {
                            $this->info(__('commands.clean.folder_deleted_message', ['folder' => $dayDir]));
                        } else {
                            $this->error(__('commands.clean.folder_delete_fail_message', ['folder' => $dayDir]));
                        }
                    }
                }

                // Check if the month directory is now empty and delete it if it is
                if (empty(File::directories($monthDir)) && empty(File::files($monthDir))) {
                    File::deleteDirectory($monthDir);
                    $this->info(__('commands.clean.empty_month_deleted_message', ['folder' => $monthDir]));
                }
            }

            // Check if the year directory is now empty and delete it if it is
            if (empty(File::directories($yearDir)) && empty(File::files($yearDir))) {
                File::deleteDirectory($yearDir);
                $this->info(__('commands.clean.empty_year_deleted_message', ['folder' => $yearDir]));
            }
        }

        $this->info(__('commands.clean.cleanup_complete_message'));
    }
}
