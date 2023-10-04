<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use App\Services\Items\VerificationCodeItemService;
use App\Services\TwoFactorAuthenticationService;
use App\Services\TwoFactorAuthService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Two-Factor Code Verification Request
 *
 * @package \App\Http\Requests\Auth
 */
class TwoFactorCodeVerificationRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'two_factor_code' => ['required', 'numeric'],
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

    /*
    |--------------------------------------------------------------------------
    | Additional methods
    |--------------------------------------------------------------------------
    */
    /**
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verify() : void
    {
        $throttleKey = $this->throttleKey();

        try {
            $verificationCodeItemService = app(TwoFactorAuthenticationService::class);

            $this->ensureIsNotRateLimited($throttleKey, config('auth.2fa_settings.max_attempts'));

            $verificationCodeItemService->verify(
                $this->input('two_factor_code')
            );

            $twoFactorAuthData = $this->session()->get('2fa_data');

            auth()->attempt((array) json_decode(decrypt($twoFactorAuthData['token'])));

            RateLimiter::clear($throttleKey);
        } catch (\Exception $exception) {
            if ($exception->getCode() === 2) {
                RateLimiter::hit($throttleKey);

                throw ValidationException::withMessages([
                    'two_factor_code' => trans('auth.two_factor_failed'),
                ]);
            }

            $this->session()->flash('2fa_error', [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @return string
     */
    public function throttleKey() : string
    {
        return $this->session()->getId() . '|2fa_verification|' . $this->ip();
    }
}
