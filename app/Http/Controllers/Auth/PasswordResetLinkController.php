<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Password Reset Link Controller
 *
 * @package \App\Http\Controllers\Auth
 */
class PasswordResetLinkController extends BaseController
{
    /**
     * Display the password reset link request view.
     *
     * @return Response
     */
    public function create() : Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.email_not_verified')],
            ]);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $email = !empty($user->first_login_at) ? $request->only('email') : [];

        $status = Password::sendResetLink($email);

        if ($status == Password::RESET_LINK_SENT) {
            return redirect()->route('auth.login')
                ->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
