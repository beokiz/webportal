<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware;

use App\Services\TwoFactorAuthenticationService;
use Closure;
use Illuminate\Http\Request;

/**
 * Verify Two-Factor Authentication Code
 *
 * @package \App\Http\Middleware
 */
class VerifyTwoFactorAuthenticationCode
{
    /**
     * @var TwoFactorAuthenticationService
     */
    protected $twoFactorAuthenticationService;

    /**
     * VerifyTwoFactorAuthenticationCode constructor.
     *
     * @param TwoFactorAuthenticationService $twoFactorAuthenticationService
     * @return void
     */
    public function __construct(TwoFactorAuthenticationService $twoFactorAuthenticationService)
    {
        $this->twoFactorAuthenticationService = $twoFactorAuthenticationService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $twoFactorAuthData = $request->session()->get('2fa_data');

        $user = $request->user();

        if ($user && $user->two_factor_auth_enabled) {
            if (!empty($twoFactorAuthData) && !isset($twoFactorAuthData['confirmed_at']) && !$request->is('2fa*')) {
                if (
                    isset($twoFactorAuthData['token']) &&
                    isset($twoFactorAuthData['code']) &&
                    isset($twoFactorAuthData['expires_at'])
                ) {
                    return redirect()->route('2fa.create');
                }

                $this->twoFactorAuthenticationService->remove();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return redirect()
                    ->route('auth.login')
                    ->withErrors(__('auth.2fa_error'));
            }
        } else {
            $twoFactorAuthService = app(TwoFactorAuthenticationService::class);

            $twoFactorAuthService->remove();
        }

        return $next($request);
    }
}
