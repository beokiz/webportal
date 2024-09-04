<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

declare(strict_types = 1);

use App\Models\Evaluation;
use DragonCode\LaravelActions\Action;

return new class () extends Action {
    /**
     * Run the actions.
     *
     * @return void
     */
    public function __invoke() : void
    {
        Evaluation::whereNull('custom_unique_id')
            ->orWhere('custom_unique_id', '')
            ->get()
            ->each(function ($evaluation) {
                $evaluation->custom_unique_id = Evaluation::generateModelCustomUniqueId();
                $evaluation->save();
            });
    }
};
