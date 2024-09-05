<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Models\TrainingProposal;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
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
        // Retrieve the user ID from the route
        $userId = $request->route('id');

        // Find the user by ID
        $user = User::find($userId);

        $args = $request->all();

        if (empty($user)) {
            return redirect()->route('auth.login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Update kita Training proposals info
        $trainingType = null;

        $user->loadMissing(['kitas.trainingProposals', 'kitas.trainings']);

        $user->kitas->each(function ($kita) use (&$trainingType) {
            if ($kita->trainings->isNotEmpty()) {
                $trainingType = 'training';
            } else {
                $trainingType = 'training-proposals';

                $kita->trainingProposals()
                    ->where('status', TrainingProposal::STATUS_EMAIL_NOT_CONFIRMED)
                    ->update(['status' => TrainingProposal::STATUS_OPEN]);
            }
        });

        // Update user info
        $user->update([
            'first_login_at' => null,
        ]);

        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

//        return redirect()->route('auth.login');
//        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        return redirect()->route('verification.verified_notice', [
            'user_id'       => $user->id,
            'training_type' => $trainingType,
            'expires'       => $args['expires'],
            'signature'     => $args['signature'],
        ]);
    }
}
