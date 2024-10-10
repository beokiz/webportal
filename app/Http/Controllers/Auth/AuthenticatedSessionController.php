<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\AuthenticationRequest;
use App\Providers\RouteServiceProvider;
use App\Services\Items\SettingItemService;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Authenticated Session Controller
 *
 * @package \App\Http\Controllers\Auth
 */
class AuthenticatedSessionController extends BaseController
{
    /**
     * Display the login view.
     *
     * @return Response
     */
    public function create() : Response
    {
        $settingItemService = app(SettingItemService::class);

        $loginFormHtmlSettings = $settingItemService->findByName('login_form_additional_html', false);

        return Inertia::render('Auth/Login', [
            'loginFormHtml'    => optional($loginFormHtmlSettings)->value,
            'canResetPassword' => Route::has('password.request'),
            'canRegister'      => Route::has('auth.register'),
            'status'           => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param AuthenticationRequest $request
     * @return RedirectResponse
     */
    public function store(AuthenticationRequest $request) : RedirectResponse
    {
        $request->authenticate();

//        $request->session()->regenerate();

        $user = $request->user();

        if ($user && $user->two_factor_auth_enabled) {
            return redirect()->route('2fa.create');
        } else {
            $targetUrl = redirect()->intended()->getTargetUrl();

            return redirect()->intended(prepare_intended_path($targetUrl));
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request) : RedirectResponse
    {
        $twoFactorAuthenticationService = app(TwoFactorAuthenticationService::class);

        Auth::guard('web')->logout();

        $twoFactorAuthenticationService->remove();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(RouteServiceProvider::HOME);
    }
}
