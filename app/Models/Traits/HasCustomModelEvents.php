<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models\Traits;

/**
 * Trait for adding method to trigger custom Model events
 *
 * @package \App\Models\Traits
 */
trait HasCustomModelEvents
{
    /**
     * @var array
     */
    public $eventData = [];

    /**
     * Fire the given event for the model.
     *
     * @param string $event
     * @param bool   $halt
     * @param array  $data
     * @return mixed
     */
    public function triggerCustomModelEvent($event, $halt = true, array $data = [])
    {
        $this->eventData[$event] = $data;

        return parent::fireModelEvent($event, $halt);
    }


    /**
     * Get the event data by event
     *
     * @param string $event
     * @return array|NULL
     */
    public function getCustomModelEventData(string $event)
    {
        return array_key_exists($event, $this->eventData)
            ? $this->eventData[$event]
            : NULL;
    }
}
