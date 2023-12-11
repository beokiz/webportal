<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Registered User Controller
 *
 * @package \App\Http\Controllers\Auth
 */
class RegisteredUserController extends BaseController
{
    /**
     * Display the registration view.
     *
     * @return Response
     */
    public function create() : Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param RegistrationRequest $request
     * @return RedirectResponse
     */
    public function store(RegistrationRequest $request) : RedirectResponse
    {
        $attributes = $request->validated();

        $user = User::create([
            'first_name' => $attributes['first_name'],
            'last_name'  => $attributes['last_name'],
            'email'      => $attributes['email'],
            'password'   => Hash::make($attributes['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
