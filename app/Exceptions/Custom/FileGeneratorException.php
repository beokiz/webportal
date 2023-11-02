<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Exceptions\Custom;

use Exception;
use Throwable;

/**
 * File Generator Exception
 *
 * @package \App\Exceptions\Custom
 */
class FileGeneratorException extends Exception
{
    /**
     * FileGeneratorException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     * @return void
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
