<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Base Policy
 *
 * @package \App\Policies
 */
abstract class BasePolicy
{
    use HandlesAuthorization;

    /**
     * BasePolicy constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
