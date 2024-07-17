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
                'name'  => 'login_form_additional_html',
                'value' => '<p>Sehr geehrte BesucherIn, <br>herzlich willkommen beim Ampel Portal!</p><p></p><p></p><p>Zugänge für Kitas aus Berlin erhalten sie nach dere Schulung durch ihre Multiplikatiorin.<br>Für Zugänge für Einrichtung außerhalb Berlins finden sie weitere Information finden sie unter <a target="_blank" rel="noopener noreferrer nofollow" href="http://www.kitearo.de/kita_anmelden"><span style="color: #3f51b5"><strong>www.kitearo.de/kita_anmelden</strong></span></a><span style="color: #3f51b5">.</span></p>',
            ]),
        ]);
    }
};
