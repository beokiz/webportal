<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    'configs'           => [

        // Sample config
        'sample' => [
            'name'                  => 'sample',
            'signing_secret'        => env('SAMPLE_WEBHOOK_SECRET', null),
            'signature_header_name' => env('SAMPLE_WEBHOOK_SIGNATURE_HEADER_NAME', null),
            'signature_validator'   => \App\Webhook\SignatureValidators\SampleSignatureValidator::class,
            'webhook_profile'       => \App\Webhook\Profiles\SampleWebhookProfile::class,
            'webhook_response'      => \App\Webhook\Responses\SampleWebhookResponse::class,
            'webhook_model'         => \App\Models\WebhookCall::class,
            'process_webhook_job'   => \App\Jobs\Webhooks\SampleWebhookJob::class,
        ],

    ],

    /*
     * The integer amount of days after which models should be deleted.
     *
     * 7 deletes all records after 1 week. Set to null if no models should be deleted.
     */
    'delete_after_days' => 30,

];
