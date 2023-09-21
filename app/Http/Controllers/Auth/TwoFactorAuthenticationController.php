<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\TwoFactorCodeVerificationRequest;
use App\Providers\RouteServiceProvider;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Two-Factor Authentication Controller
 *
 * @package \App\Http\Controllers\Auth
 */
class TwoFactorAuthenticationController extends BaseController
{
    /**
     * @var TwoFactorAuthenticationService
     */
    protected $twoFactorAuthenticationService;

    /**
     * TwoFactorAuthenticationController constructor.
     *
     * @param TwoFactorAuthenticationService $twoFactorAuthenticationService
     * @return void
     */
    public function __construct(TwoFactorAuthenticationService $twoFactorAuthenticationService)
    {
        $this->twoFactorAuthenticationService = $twoFactorAuthenticationService;
    }

    /**
     * Display the Two-Factor Verification Form view.
     *
     * @param Request $request
     * @return InertiaResponse
     */
    public function create(Request $request) : InertiaResponse
    {
        return Inertia::render('Auth/VerifyTwoFactorAuthenticationCode', [
            'status'  => session('status'),
            'request' => $request->all(),
        ]);
    }

    /**
     * Handle an incoming Two-Factor Verification request.
     *
     * @param TwoFactorCodeVerificationRequest $request
     * @return RedirectResponse
     */
    public function store(TwoFactorCodeVerificationRequest $request) : RedirectResponse
    {
        $request->verify();

        if ($verificationError = $request->session()->get('2fa_error.message')) {
            $this->twoFactorAuthenticationService->remove();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('auth.login')
                ->withErrors($verificationError);
        }

        $targetUrl   = redirect()->intended()->getTargetUrl();
        $redirectUrl = (get_url_path($targetUrl)) ? $targetUrl : RouteServiceProvider::HOME;

        return redirect()->intended($redirectUrl);
    }

    /**
     * Resend Two-Factor Verification code.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function resend(Request $request) : RedirectResponse
    {
        // Ensure is not rate limited
        $throttleKey = $request->session()->getId() . '|2fa_code_email';

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            event(new Lockout($request));

            $seconds = RateLimiter::availableIn($throttleKey);

            $msgKey = !empty($msgKey) ? $msgKey : 'validation.custom.throttle';

            throw ValidationException::withMessages([
                'throttle' => trans($msgKey, [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Try to get user info
        $token = $request->session()->get('2fa_data.token');

        try {
            $codeCanBeResent = auth()->once((array) json_decode(decrypt($token)));
        } catch (\Exception $exception) {
            $codeCanBeResent = false;
        }

        // Resend code
        if ($token && $codeCanBeResent) {
            $user = auth()->getUser();

            $user->sendTwoFactorAuthenticationNotification([
                'token' => $token,
            ]);

            RateLimiter::hit($throttleKey);

            return redirect()->back()
                ->with('status', __('auth.2fa_resend'));
        } else {
            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('auth.login')
                ->withErrors(__('auth.2fa_error'));
        }
    }
}
