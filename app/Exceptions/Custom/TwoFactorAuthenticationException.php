<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Exceptions\Custom;

use Exception;
use Throwable;

/**
 * Two-Factor Authentication Exception
 *
 * @package \App\Exceptions\Custom
 */
class TwoFactorAuthenticationException extends Exception
{
    /**
     * SuperAdminUpdatingException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     * @return void
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if (empty($message)) {
            $message = __('exceptions.2fa_default_error');
        }

        parent::__construct($message, $code, $previous);
    }
}
