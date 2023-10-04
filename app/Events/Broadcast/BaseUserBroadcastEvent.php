<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Events\Broadcast;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Base User Broadcast Event
 *
 * @package \App\Events\Broadcast
 */
abstract class BaseUserBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var mixed
     */
    public $data;

    /**
     * BaseUserBroadcastEvent constructor.
     *
     * @param mixed $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;

//        $this->dontBroadcastToCurrentUser();
    }

    /**
     * @return \Illuminate\Broadcasting\Channel|array|void
     */
    public function broadcastOn()
    {
        $userId = $this->data['user_id'] ?? null;

        return new PrivateChannel("App.Models.User.{$userId}");
    }
}
