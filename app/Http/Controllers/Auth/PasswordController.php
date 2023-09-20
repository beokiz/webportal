<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

/**
 * Password Controller
 *
 * @package \App\Http\Controllers\Auth
 */
class PasswordController extends BaseController
{
    /**
     * Update the user's password.
     *
     * @param UpdatePasswordRequest $request
     * @return RedirectResponse
     */
    public function update(UpdatePasswordRequest $request) : RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
