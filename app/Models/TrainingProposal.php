<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\TrainingProposalFilter;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * TrainingProposal Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class TrainingProposal extends Model
{
    use HasFactory, Filterable, HasOrderScope;

    // use SoftDeletes;

    const STATUS_EMAIL_NOT_CONFIRMED  = 'email_not_confirmed ';
    const STATUS_OPEN                 = 'open';
    const STATUS_OBSOLETE             = 'obsolete';
    const STATUS_RESERVED             = 'reserved';
    const STATUS_CONFIRMATION_PENDING = 'confirmation_pending';
    const STATUS_CONFIRMED            = 'confirmed';
    const STATUSES                    = [
        self::STATUS_EMAIL_NOT_CONFIRMED,
        self::STATUS_OPEN,
        self::STATUS_OBSOLETE,
        self::STATUS_RESERVED,
        self::STATUS_CONFIRMATION_PENDING,
        self::STATUS_CONFIRMED,
    ];

    /**
     * @var string
     */
    protected $table = 'training_proposals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'multi_id',
        'first_date',
        'second_date',
        'location',
        'participant_count',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'first_date'        => 'date',
        'second_date'       => 'date',
        'participant_count' => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'formatted_status',
        'kitas_list',
    ];

    /**
     * @return Attribute
     */
    public function formattedStatus() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => __("validation.attributes.{$attributes['status']}"),
        );
    }

    /**
     * @return Attribute
     */
    public function kitasList() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->relationLoaded('kitas') ? $this->kitas->pluck('name') : [],
        );
    }

    /**
     * @return array
     */
    public function getNotificationsData() : array
    {
        return [
            'first_date'      => $this->first_date->format('Y-m-d'),
            'second_date'     => $this->second_date->format('Y-m-d'),
            'location'        => $this->location,
            'multiplier_name' => optional($this->multiplier)->full_name,
        ];
    }

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(TrainingProposalFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    /**
     * @return HasMany
     */
    public function trainingProposalConfirmations() : HasMany
    {
        return $this->hasMany(TrainingProposalConfirmation::class, 'training_proposal_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function multiplier() : BelongsTo
    {
        return $this->belongsTo(User::class, 'multi_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function kitas() : BelongsToMany
    {
        return $this->BelongsToMany(Kita::class, 'kita_has_training_proposals', 'training_proposal_id', 'kita_id');
    }
}
