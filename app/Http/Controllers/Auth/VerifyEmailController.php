<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\TrainingProposal;
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
        $currentUser = $request->user();

        $args = $request->all();

        if ($currentUser->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        if ($currentUser->markEmailAsVerified()) {
            event(new Verified($currentUser));
        }

        // Update kita Training proposals info
        $trainingType = null;

        $currentUser->loadMissing(['kitas.trainingProposals', 'kitas.trainings']);

        $currentUser->kitas->each(function ($kita) use (&$trainingType) {
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
        $currentUser->update([
            'first_login_at' => null,
        ]);

        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

//        return redirect()->route('auth.login');
//        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        return redirect()->route('verification.verified_notice', [
            'user_id'       => $currentUser->id,
            'training_type' => $trainingType,
            'expires'       => $args['expires'],
            'signature'     => $args['signature'],
        ]);
    }
}
