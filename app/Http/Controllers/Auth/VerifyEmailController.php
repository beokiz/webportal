<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Verify Email Controller
 *
 * @package \App\Http\Controllers\Auth
 */
class VerifyEmailController extends BaseController
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param EmailVerificationRequest $request
     * @return RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request) : RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $request->user()->update([
            'first_login_at' => null,
        ]);

        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
//        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
