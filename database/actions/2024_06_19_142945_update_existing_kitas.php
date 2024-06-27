<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

declare(strict_types = 1);

use App\Models\Kita;
use DragonCode\LaravelActions\Action;

return new class () extends Action {
    /**
     * Run the actions.
     *
     * @return void
     */
    public function __invoke() : void
    {
        Kita::query()->update([
            'district'              => null,
            'type'                  => Kita::TYPE_SMALL,
            'approved'              => true,
            'operator_id'           => null,
            'num_pedagogical_staff' => null,
            'notes'                 => null,
        ]);
    }
};
