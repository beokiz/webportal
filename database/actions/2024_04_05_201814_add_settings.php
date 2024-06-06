<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

declare(strict_types = 1);

use DragonCode\LaravelActions\Action;

return new class () extends Action {
    /**
     * Run the actions.
     *
     * @return void
     */
    public function __invoke() : void
    {
        $createdAt = now();

        $commonOptions = [
            //
        ];

        \App\Models\Setting::insert([
            array_merge($commonOptions, [
                'name'  => 'send_yearly_evaluation_reminder_ntf_before_days',
                'value' => 10,
            ]),
        ]);
    }
};
