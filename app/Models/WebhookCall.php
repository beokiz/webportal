<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use Spatie\WebhookClient\Models\WebhookCall as SpatieWebhookCall;

/**
 * WebhookCall Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class WebhookCall extends SpatieWebhookCall
{
    /**
     * @var string
     */
    protected $table = 'webhook_calls';
}
