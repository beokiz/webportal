<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Authentication Request
 *
 * @package \App\Http\Requests\Auth
 */
class AuthenticationRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
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

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate() : void
    {
        $throttleKey = Str::lower($this->input('email')) . '|' . $this->ip();

        $this->ensureIsNotRateLimited($throttleKey, 5, 'auth.throttle');

        $credentials = $this->only('email', 'password');

        if (!auth()->once($credentials)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        } else {
            $this->session()->regenerate();

            $user = auth()->getUser();

            if ($user->two_factor_auth_enabled) {
                $user->sendTwoFactorAuthenticationNotification([
                    'token' => encrypt(json_encode($credentials)),
                ]);
            } else {
                Auth::attempt($this->only('email', 'password'), $this->boolean('remember'));
            }

            RateLimiter::clear($throttleKey);
        }
    }
}
