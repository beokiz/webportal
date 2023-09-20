<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models\Traits;

use Illuminate\Support\Str;

/**
 * Trait for adding "uuid" field value to model on creating
 *
 * @package \App\Models\Traits
 */
trait UsesUuid
{
    /**
     * @return void
     */
    protected static function bootUsesUuid()
    {
        static::creating(function ($model) {
            if (is_null($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
