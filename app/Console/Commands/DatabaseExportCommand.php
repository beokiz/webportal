<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Console\Commands;

use App\Helpers\MysqlDatabaseHelper;
use Illuminate\Console\Command;

/**
 * Database Export Command
 *
 * @package \App\Console\Commands
 */
class DatabaseExportCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'db:export {--P|path=}';

    /**
     * @var string
     */
    protected $description = 'Run DB tables export
                                    {--P|path= : Path to export file. Default: "/database/backups/%cur_year%/%cur_month%/%cur_day%/"';

    /**
     * DatabaseExportCommand constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @echo mixed
     */
    public function handle()
    {
        try {
            $this->info(__('artisan.database_export.start_message'));

            $result = MysqlDatabaseHelper::export(config('database.default'), [
                '--no-tablespaces',
                '--column-statistics=0',
            ], $this->option('path'));

            if ($result) {
                $this->info(__('artisan.database_export.success_message', ['path' => $result]));
            } else {
                $this->error(__('artisan.database_export.error_message'));
            }
        } catch (\Exception $exception) {
            $this->error(__('artisan.common.error'));
            $this->error(__('artisan.common.exception', ['exception' => $exception->getMessage()]));
        }
    }
}
