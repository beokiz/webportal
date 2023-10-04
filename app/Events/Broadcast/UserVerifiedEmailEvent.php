<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Events\Broadcast;

/**
 * User Verified Email Event
 *
 * @package \App\Events\Broadcast
 */
class UserVerifiedEmailEvent extends BaseUserBroadcastEvent
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return parent::broadcastOn();
    }
}
