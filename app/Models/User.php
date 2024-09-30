<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\UserFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use App\Notifications\ConnectedToKitasNotification;
use App\Notifications\EmailVerifiedNotification;
use App\Notifications\NewOperatorKitaNotification;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\TrainingCancelledNotification;
use App\Notifications\TrainingCompletedNotification;
use App\Notifications\TrainingConfirmedNotification;
use App\Notifications\TrainingProposalConfirmationPendingNotification;
use App\Notifications\TwoFactorVerificationNotification;
use App\Notifications\VerifyEmailNotification;
use App\Notifications\WelcomeNotification;
use App\Notifications\YearlyEvaluationReminderNotification;
use App\Services\TwoFactorAuthenticationService;
use EloquentFilter\Filterable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
class User extends Authenticatable implements MustVerifyEmail
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
        'phone_number',
        'email_verified_at',
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
        'has_self_training_operator',
    ];

    /**
     * @return Attribute
     */
    protected function firstName() : Attribute
    {
        return Attribute::make(
            get: fn($value) => trim(ucfirst($value)),
        );
    }

    /**
     * @return Attribute
     */
    protected function lastName() : Attribute
    {
        return Attribute::make(
            get: fn($value) => trim(ucfirst($value)),
        );
    }

    /**
     * @return Attribute
     */
    protected function fullName() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => trim(ucfirst($attributes['first_name']) . ' ' . ucfirst($attributes['last_name'])),
        );
    }

    /**
     * @return Attribute
     */
    public function primaryRole() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => optional($this->roles()->orderBy('id')->first()) ?? null,
        );
    }

    /**
     * @return Attribute
     */
    public function primaryRoleName() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => optional($this->roles()->orderBy('id')->first())->name ?? null,
        );
    }

    /**
     * @return Attribute
     */
    public function primaryRoleHumanName() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => optional($this->roles()->orderBy('id')->first())->human_name ?? null,
        );
    }

    /**
     * @return Attribute
     */
    public function primaryRoleId() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => optional($this->roles()->orderBy('id')->first())->id ?? null,
        );
    }

    /**
     * @return Attribute
     */
    public function isOnline() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) < 3,
        );
    }

    /**
     * @return Attribute
     */
    public function isSuperAdmin() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.super_admin')),
        );
    }

    /**
     * @return Attribute
     */
    public function isAdmin() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.admin')),
        );
    }

    /**
     * @return Attribute
     */
    public function isMonitor() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.monitor')),
        );
    }

    /**
     * @return Attribute
     */
    public function isMonitorOe() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.monitor_oe')),
        );
    }

    /**
     * @return Attribute
     */
    public function isManager() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.manager')),
        );
    }

    /**
     * @return Attribute
     */
    public function isUserMultiplier() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.user_multiplier')),
        );
    }

    /**
     * @return Attribute
     */
    public function isEmployer() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->hasRole(config('permission.project_roles.employer')),
        );
    }

    /**
     * @return Attribute
     */
    public function hasSelfTrainingOperator() : Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if ($this->relationLoaded('operators')) {
                    return $this->operators->where('self_training', true)->count() > 0;
                } else {
                    return null;
                }
            },
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
     * @return void
     */
    public function sendEmailVerifiedNotification() : void
    {
        $this->loadMissing(['kitas.trainingProposals']);

        $trainingProposalsData = [];

        $this->kitas->each(function ($kita) use (&$trainingProposalsData) {
            if ($kita->trainingProposals->isNotEmpty()) {
                $counter = 0;

                $kita->trainingProposals->each(function ($trainingProposal) use (&$trainingProposalsData, &$counter, $kita) {
                    $counter++;

                    $trainingProposalsData[] = __(
                        $counter > 1 ? 'notifications.email_verified.other_training_item' : 'notifications.email_verified.first_training_item',
                        [
                            'first_date'  => $trainingProposal->first_date->format('d.m.Y'),
                            'second_date' => $trainingProposal->second_date->format('d.m.Y'),
                        ]
                    );
                });
            }
        });

        $this->notify(new EmailVerifiedNotification($trainingProposalsData));
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

    /**
     * @param array $args
     * @return void
     */
    public function sendYearlyEvaluationReminderNotification(array $args) : void
    {
        $this->notify(new YearlyEvaluationReminderNotification($args));
    }

    /**
     * @param array $args
     * @return void
     */
    public function sendTrainingConfirmedNotification(array $args) : void
    {
        $this->notify(new TrainingConfirmedNotification($args));
    }

    /**
     * @param array $args
     * @return void
     */
    public function sendTrainingCompletedNotification(array $args) : void
    {
        $this->notify(new TrainingCompletedNotification($args));
    }

    /**
     * @param array $args
     * @return void
     */
    public function sendTrainingCancelledNotification(array $args) : void
    {
        $this->notify(new TrainingCancelledNotification($args));
    }

    /**
     * @param array $args
     * @return void
     */
    public function sendTrainingProposalConfirmationPendingNotification(array $args) : void
    {
        $this->notify(new TrainingProposalConfirmationPendingNotification($args));
    }

    /**
     * @param array $args
     * @return void
     */
    public function sendNewOperatorKitaNotification(array $args) : void
    {
        $this->notify(new NewOperatorKitaNotification($args));
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    /**
     * @return BelongsToMany
     */
    public function kitas() : BelongsToMany
    {
        return $this->BelongsToMany(Kita::class, 'kita_has_users', 'user_id', 'kita_id');
    }

    /**
     * @return BelongsToMany
     */
    public function operators() : BelongsToMany
    {
        return $this->BelongsToMany(Operator::class, 'operator_has_users', 'user_id', 'operator_id');
    }

    /**
     * @return HasMany
     */
    public function evaluations() : HasMany
    {
        return $this->hasMany(Evaluation::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function trainings() : HasMany
    {
        return $this->hasMany(Training::class, 'multi_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function trainingProposals() : HasMany
    {
        return $this->hasMany(TrainingProposal::class, 'multi_id', 'id');
    }
}
