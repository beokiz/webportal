<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services;

use App\Exceptions\Custom\ShellCommandException;
use App\Interfaces\ShellCommandServiceInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Linux Shell Command Service
 *
 * @package \App\Services
 */
class LinuxShellCommandService implements ShellCommandServiceInterface
{
    /**
     * LinuxShellCommandService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param array          $command
     * @param string|null    $cwd
     * @param array|null     $env
     * @param null           $input
     * @param float|int|null $timeout
     * @return string
     */
    public function executeCommand(array $command, string $cwd = null, array $env = null, $input = null, ?float $timeout = 30) : string
    {
        $process = new Process($command, $cwd, $env, $input, $timeout);

        try {
            $process->setTimeout($timeout);
            $process->mustRun();

            return mb_convert_encoding($process->getOutput(), 'UTF-8', 'UTF-8');
        } catch (ProcessFailedException $exception) {
            if ($output = $process->getOutput()) {
                return mb_convert_encoding($output, 'UTF-8', 'UTF-8');
            }

            throw new ShellCommandException($exception);
        } catch (\Exception $exception) {
            throw new ShellCommandException($exception);
        }
    }
}
