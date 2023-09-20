<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Webhook\Profiles;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

/**
 * Sample Webhook Profile
 *
 * @package \App\Webhook\Profile
 */
class SampleWebhookProfile extends BaseWebhookProfile implements WebhookProfile
{
    /**
     * SampleWebhookProfile constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function shouldProcess(Request $request) : bool
    {
        return true;
    }
}
