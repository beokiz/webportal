<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Webhook\Responses;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sample Webhook Response
 *
 * @package \App\Webhook\Responses
 */
class SampleWebhookResponse extends BaseWebhookResponse implements RespondsToWebhook
{
    /**
     * SampleWebhookResponse constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request       $request
     * @param WebhookConfig $config
     * @return Response
     */
    public function respondToValidWebhook(Request $request, WebhookConfig $config) : Response
    {
        return $this->respondSuccess();
    }
}
