<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models\Traits;

/**
 * Trait for getting Model table name statically
 *
 * @package \App\Models\Traits
 */
trait CanGetTableNameStatically
{
    /**
     * @return string
     */
    protected function getTableName() : string
    {
        return with(new static)->getTable();
    }
}
