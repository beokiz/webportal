<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

declare(strict_types = 1);

use App\Models\User;
use DragonCode\LaravelActions\Action;
use Illuminate\Support\Carbon;

return new class () extends Action {
    /**
     * Run the actions.
     *
     * @return void
     */
    public function __invoke() : void
    {
        User::query()->update([
            'email_verified_at' => Carbon::now(),
        ]);
    }
};
