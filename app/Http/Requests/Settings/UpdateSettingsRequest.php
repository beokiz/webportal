<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Settings;

use App\Http\Requests\BaseFormRequest;

//use Illuminate\Validation\Rules;

/**
 * Update Settings Request
 *
 * @package \App\Http\Requests\Settings
 */
class UpdateSettingsRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'settings.*' => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            //
        ];
    }
}
