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
                'name'  => 'imprint_support_html',
                'value' => '<p>Wenn Sie Fragen zur Verwendung des Portals haben, wenden Sie sich gerne an <a target="_blank" rel="noopener noreferrer nofollow" href="mailto:support@kitearo.de"><span style="color: #3f51b5">support@kitearo.de</span></a>.</p>',
            ]),
        ]);
    }
};
