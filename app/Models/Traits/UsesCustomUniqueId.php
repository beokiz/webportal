<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models\Traits;

/**
 * Trait for adding "custom_unique_id" field value to model on creating
 *
 * @package \App\Models\Traits
 */
trait UsesCustomUniqueId
{
    /**
     * @return void
     */
    protected static function bootUsesCustomUniqueId()
    {
        // Define a model creating event listener
        static::creating(function ($model) {
            if (empty($model->custom_unique_id)) {
                $model->custom_unique_id = self::generateModelCustomUniqueId();
            }
        });

        // Define a model updating event listener
        static::updating(function ($model) {
            if (empty($model->custom_unique_id)) {
                $model->custom_unique_id = self::generateModelCustomUniqueId();
            }
        });
    }

    /**
     * @param int $attempt
     * @return string
     * @throws \Exception
     */
    public static function generateModelCustomUniqueId(int $attempt = 0)
    {
        $key = generate_custom_unique_id();

        if (self::where('custom_unique_id', $key)->exists()) {
            if ($attempt <= 50) {
                return self::generateModelCustomUniqueId(++$attempt);
            } else {
                throw new \Exception(__('exceptions.non_unique_custom_unique_id'));
            }
        }

        return $key;
    }
}
