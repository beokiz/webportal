<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Clear Temporary Files Command
 *
 * @package \App\Console\Commands
 */
class CleanTemporaryFilesCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'temp:files:clean {--f|force} {--s|silent}';

    /**
     * @var string
     */
    protected $description = 'Remove the temporary project files
                                    {--f|force : Forced remove all temporary files}
                                    {--s|silent : Silent mode, no output}';

    /**
     * ClearTemporaryFilesCommand constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle()
    {
        try {
            $tmpStorage = Storage::disk('local_tmp');

            $tmpFiles      = $tmpStorage->files('');
            $excludedFiles = ['.gitignore'];

            $deletedFiles = array_diff($tmpFiles, $excludedFiles);

            if (!$this->option('force')) {
                foreach ($deletedFiles as $key => $fileName) {
                    $filePath = $tmpStorage->path($fileName);
                    $fileTime = filemtime($filePath);

                    if ($fileTime >= strtotime('-1 hour')) {
                        unset($deletedFiles[$key]);
                    }
                }
            }

            $tmpStorage->delete($deletedFiles);

            if (!$this->option('silent')) {
                $this->info(__('commands.temp.clear_tmp_message', [
                    'count' => count($deletedFiles),
                ]));
            }
        } catch (\Exception $exception) {
            $this->error(__('commands.common.error'));
            $this->error(__('commands.common.exception', ['exception' => $exception->getMessage()]));
        }
    }
}
