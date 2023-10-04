<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services;

use App\Exceptions\Custom\TwoFactorAuthenticationException;

/**
 * Two-Factor Authentication Service
 *
 * @package \App\Services
 */
class TwoFactorAuthenticationService
{
    /**
     * TwoFactorAuthenticationService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param array $args
     * @return void
     */
    public function generate(array $args) : void
    {
        session()->put('2fa_data', array_merge($args, [
            'code'       => encrypt(rand(100000, 999999)),
            'expires_at' => now()->addMinutes(config('auth.2fa_settings.expire')),
        ]));
    }

    /**
     * @param numeric $code
     * @return mixed
     */
    public function verify($code)
    {
        $twoFactorAuthData = session('2fa_data');

        if (!empty($twoFactorAuthData) && is_array($twoFactorAuthData)) {
            $sessionCode = decrypt($twoFactorAuthData['code']);

            if (now()->gt($twoFactorAuthData['expires_at'])) {
                $this->remove();

                throw new TwoFactorAuthenticationException(__('auth.2fa_expired'), 1);
            }

            if ((int) $sessionCode !== (int) $code) {
                throw new TwoFactorAuthenticationException(__('auth.2fa_failed'), 2);
            }

            session()->put('2fa_data.confirmed_at', now());

            return true;
        } else {
            throw new TwoFactorAuthenticationException(__('auth.2fa_error'), 0);
        }
    }

    /**
     * @return void
     */
    public function remove() : void
    {
        session()->remove('2fa_data');
    }
}
