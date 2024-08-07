<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Profile Controller
 *
 * @package \App\Http\Controllers
 */
class ProfileController extends BaseController
{
    /**
     * Display the user's profile form.
     *
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request) : Response
    {
        $currentUser = $request->user();

        $props = [
            'mustVerifyEmail' => $currentUser instanceof MustVerifyEmail,
            'status'          => session('status'),
            'kitas'           => [],
        ];

        if ($currentUser->is_manager || $currentUser->is_employer) {
            $props['kitas'] = $currentUser->kitas;
        }

        return Inertia::render('Profile/Edit', $props);
    }

    /**
     * Update the user's profile information.
     *
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request) : RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $result = $request->user()->save();

        return $result
            ? Redirect::back()->withStatus(__('profile.info_success'))
            : Redirect::back()->withErrors(__('profile.info_error'));
    }

    /**
     * Delete the user's account.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request) : RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(RouteServiceProvider::HOME);
    }
}
