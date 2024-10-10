<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Console\Commands;

use App\Interfaces\ShellCommandServiceInterface;
use Illuminate\Console\Command;

/**
 * Supervisor Command
 *
 * @package \App\Console\Commands
 */
class SupervisorCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'gk:supervisor {action}';

    /**
     * @var string
     */
    protected $description = 'Execute selected action for Supervisor programs
                                    {action : Selected Supervisor action}';

    /**
     * SupervisorCommand constructor.
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
        $action = $this->argument('action');

        $availableActions = ['status', 'restart', 'start', 'stop'];

        try {
            if (in_array($action, $availableActions)) {
                $shellCommandService = app(ShellCommandServiceInterface::class);

                $command = sprintf('supervisorctl %s %s:', $action, config('supervisor.programs_group_name'));

                $result = $shellCommandService->executeCommand(explode(' ', $command));

                $this->info(__('commands.supervisor.success_message'));
                $this->info($result);
            } else {
                $this->error(__('commands.supervisor.invalid_action_argument_message', ['action' => $action]));
            }
        } catch (\Exception $exception) {
            $this->error(__('commands.common.error'));
            $this->error(__('commands.common.exception', ['exception' => $exception->getMessage()]));
        }
    }
}
