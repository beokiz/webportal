<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Jobs\Webhooks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

/**
 * Sample Webhook Job
 *
 * @package \App\Jobs\Webhooks
 */
class SampleWebhookJob extends SpatieProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * SampleWebhookJob constructor.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall)
    {
        parent::__construct($webhookCall);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $eventPayload = $this->webhookCall->payload;

        //
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        log_exception($exception, 'webhook');
    }
}
