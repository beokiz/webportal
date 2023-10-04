<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Webhook\Responses;

use App\Http\Traits\ApiResponse;

/**
 * Base Webhook Response
 *
 * @package \App\Webhook\Responses
 */
abstract class BaseWebhookResponse
{
    use ApiResponse;

    /**
     * BaseWebhookResponse constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
