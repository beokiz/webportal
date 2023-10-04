<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Interfaces;

/**
 * Shell Command Service Interface
 *
 * @package \App\Interfaces
 */
interface ShellCommandServiceInterface
{
    /**
     * @param array          $command
     * @param string|null    $cwd
     * @param array|null     $env
     * @param null           $input
     * @param float|int|null $timeout
     * @return mixed
     */
    public function executeCommand(array $command, string $cwd, array $env, $input, ?float $timeout);
}
