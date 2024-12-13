<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\TrainingProposal;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Email Verified Prompt Controller
 *
 * @package \App\Http\Controllers\Auth
 */
class EmailVerifiedPromptController extends BaseController
{
    /**
     * Display the email verification prompt.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function __invoke(Request $request) : RedirectResponse|Response
    {
        $currentUser = $request->user();

        $args = $request->all();

        if (!empty($currentUser)) {
            return $currentUser->hasVerifiedEmail()
                ? redirect()->intended(RouteServiceProvider::HOME)
                : redirect()->route('verification.notice');
        }

        if (empty($currentUser) && !empty($args['user_id']) && !empty($args['expires']) && !empty($args['signature'])) {
            $user = User::find($args['user_id']);

            if (!empty($user)) {
                $user->loadMissing(['kitas.operator.users', 'kitas.trainingProposals', 'kitas.trainings']);

                $trainingItems = collect();

                $user->kitas->each(function ($kita) use (&$trainingItems, &$operatorItems) {
                    if ($kita->trainings->isNotEmpty()) {
                        $trainingItems->push(...$kita->trainings);
                    } else {
                        $trainingItems->push(...$kita->trainingProposals);
                    }

                });

                return Inertia::render('Auth/Verified');
            }
        }

        return redirect()->route('auth.login');
    }
}
