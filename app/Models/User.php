<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\ModelFilters\UserFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use App\Notifications\ConnectedToKitasNotification;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\TwoFactorVerificationNotification;
use App\Notifications\VerifyEmailNotification;
use App\Notifications\WelcomeNotification;
use App\Services\TwoFactorAuthenticationService;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * User Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, Filterable, HasOrderScope, CanGetTableNameStatically;

    // SoftDeletes

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'two_factor_auth_enabled',
        'first_login_at',
        'last_seen_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at'       => 'datetime', // timestamp
        'first_login_at'          => 'datetime', // timestamp
        'last_seen_at'            => 'datetime', // timestamp
        'two_factor_auth_enabled' => 'boolean',
        'is_online'               => 'boolean',
        'is_super_admin'          => 'boolean',
        'is_admin'                => 'boolean',
        'is_monitor'              => 'boolean',
        'is_monitor_oe'           => 'boolean',
        'is_manager'              => 'boolean',
        'is_user_multiplier'      => 'boolean',
        'is_employer'             => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'full_name',
        'primary_role_name',
        'primary_role_human_name',
        'primary_role_id',
        'is_online',
        'is_super_admin',
        'is_admin',
        'is_monitor',
        'is_monitor_oe',
        'is_manager',
        'is_user_multiplier',
        'is_employer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function firstName() : Attribute
    {
        return Attribute::make(
            get: fn($value) => trim(ucfirst($value)),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function lastName() : Attribute
    {
        return Attribute::make(
            get: fn($value) => trim(ucfirst($value)),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function fullName() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => trim(ucfirst($attributes['first_name']) . ' ' . ucfirst($attributes['last_name'])),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function primaryRole() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => optional($this->roles()->orderBy('id')->first()) ?? null,
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function primaryRoleName() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => optional($this->roles()->orderBy('id')->first())->name ?? null,
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function primaryRoleHumanName() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => optional($this->roles()->orderBy('id')->first())->human_name ?? null,
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function primaryRoleId() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => optional($this->roles()->orderBy('id')->first())->id ?? null,
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isOnline() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) < 3,
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isSuperAdmin() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.super_admin')),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isAdmin() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.admin')),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isMonitor() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.monitor')),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isMonitorOe() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.monitor_oe')),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isManager() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.manager')),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isUserMultiplier() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.user_multiplier')),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isEmployer() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.employer')),
        );
    }

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(UserFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define User Notifications
    |--------------------------------------------------------------------------
    */
    /**
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token) : void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return void
     */
    public function sendEmailVerificationNotification() : void
    {
        $this->notify(new VerifyEmailNotification());
    }

    /**
     * @param array $args
     * @return void
     */
    public function sendTwoFactorAuthenticationNotification(array $args = []) : void
    {
        if ($this->two_factor_auth_enabled) {
            $twoFactorAuthService = app(TwoFactorAuthenticationService::class);

            $twoFactorAuthService->generate($args);

            $this->notify(new TwoFactorVerificationNotification());
        }
    }

    /**
     * @param string $token
     * @return void
     */
    public function sendWelcomeNotification($token) : void
    {
        $this->notify(new WelcomeNotification($token));
    }

    /**
     * @return void
     */
    public function sendPasswordChangedNotification() : void
    {
        $this->notify(new PasswordChangedNotification());
    }

    /**
     * @param array|null $kitas
     * @return void
     */
    public function sendConnectedToKitasNotification(?array $kitas) : void
    {
        if (!empty($kitas)) {
            $this->notify(new ConnectedToKitasNotification($kitas));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    public function kitas() : BelongsToMany
    {
        return $this->BelongsToMany(Kita::class, 'kita_has_users', 'user_id', 'kita_id');
    }
}
