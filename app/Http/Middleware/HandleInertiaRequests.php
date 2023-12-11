<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

/**
 * Handle Inertia Requests
 *
 * @package \App\Http\Middleware
 */
class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request) : string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request) : array
    {
        $successes = session()->get('successes') ?? [];

        if (!is_array($successes)) {
            $successes = [$successes];
        }

        $shared = [
            'auth'      => [
                'canLogin'    => Route::has('auth.login'),
                'canRegister' => Route::has('auth.register'),
                'user'        => optional($request->user())->toArray(),
            ],
            'successes' => $successes,
            'ziggy'     => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ];

        $data = session()->get('data');

        if (!empty($data)) {
            $shared['data'] = !is_array($data) ? [$data] : $data;
        }

        return array_merge(parent::share($request), $shared);
    }
}
