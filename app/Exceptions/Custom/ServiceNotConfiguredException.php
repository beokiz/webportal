<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Exceptions\Custom;

use Exception;
use Throwable;

/**
 * Service Not Configured Exception
 *
 * @package \App\Exceptions\Custom
 */
class ServiceNotConfiguredException extends Exception
{
    /**
     * ServiceNotConfiguredException constructor.
     *
     * @param string         $serviceName
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     * @return void
     */
    public function __construct(string $serviceName = "", string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if (empty($message)) {
            $message = __('exceptions.service_not_configured_exception_msg', ['name' => $serviceName]);
        }

        parent::__construct($message, $code, $previous);
    }
}
