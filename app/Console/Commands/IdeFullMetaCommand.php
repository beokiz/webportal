<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * IDE Full Meta Command
 *
 * @package \App\Console\Commands
 */
class IdeFullMetaCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'ide-helper:full {--O|optimize}';

    /**
     * @var string
     */
    protected $description = 'Generate a new IDE Helper file, metadata for PhpStorm and autocompletion for models
                                    {--O|optimize : Run "optimize:clear" Artisan command}';

    /**
     * IdeFullMetaCommand constructor.
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
            if ($this->option('optimize')) {
                $this->call('optimize:clear');
            }

            $this->call('ide-helper:generate');
            $this->call('ide-helper:model', ['--nowrite' => true]);
            $this->call('ide-helper:meta');
        } catch (\Exception $exception) {
            $this->error(__('commands.common.error'));
            $this->error(__('commands.common.exception', ['exception' => $exception->getMessage()]));
        }
    }
}
