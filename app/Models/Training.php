<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\TrainingFilter;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Training Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class Training extends Model
{
    use HasFactory, Filterable, HasOrderScope;

    // use SoftDeletes;

    const STATUS_PLANNED   = 'planned';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUSES         = [self::STATUS_PLANNED, self::STATUS_CONFIRMED, self::STATUS_COMPLETED, self::STATUS_CANCELLED];

    const TYPE_COMBINED = 'combined';
    const TYPE_IN_HOUSE = 'in-house';
    const TYPES         = [self::TYPE_COMBINED, self::TYPE_IN_HOUSE];

    /**
     * @var string
     */
    protected $table = 'trainings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'multi_id',
        'first_date',
        'first_date_start_and_end_time',
        'second_date',
        'second_date_start_and_end_time',
        'location',
        'max_participant_count',
        'participant_count',
        'type',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'first_date'                  => 'date',
        'second_date'                 => 'date',
        'max_participant_count'       => 'integer',
        'participant_count'           => 'integer',
        'available_participant_count' => 'integer',
        'prepared_participant_count'  => 'integer',
        'created_at'                  => 'datetime',
        'updated_at'                  => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'prepared_participant_count',
        'available_participant_count',
        'formatted_type',
        'formatted_status',
    ];

    /**
     * @return Attribute
     */
    public function preparedParticipantCount() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => "{$attributes['participant_count']}/{$attributes['max_participant_count']}",
        );
    }

    /**
     * @return Attribute
     */
    public function availableParticipantCount() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $attributes['max_participant_count'] > $attributes['participant_count']
                ? $attributes['max_participant_count'] - $attributes['participant_count']
                : 0,
        );
    }

    /**
     * @return Attribute
     */
    public function formattedType() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => __("validation.attributes.{$attributes['type']}"),
        );
    }

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
     * @return array
     */
    public function getNotificationsData() : array
    {
        return [
            'first_date'                     => $this->first_date->format('Y-m-d'),
            'first_date_start_and_end_time'  => $this->first_date_start_and_end_time,
            'second_date'                    => $this->second_date->format('Y-m-d'),
            'second_date_start_and_end_time' => $this->second_date_start_and_end_time,
            'location'                       => $this->location,
            'multiplier_name'                => optional($this->multiplier)->full_name,
        ];
    }

    /**
     * @return array
     */
    public function getEmailMessagesContent() : array
    {
        $notificationData = $this->getNotificationsData();

        return [
            'confirmed' => [
                'subject' => __('notifications.training_confirmed.subject', $notificationData),
                'body'    => implode("  %0D%0A%0D%0A", [
                    str_replace("\n", '%0D%0A', __('notifications.training_confirmed.greeting')),
                    str_replace("\n", '%0D%0A', __('notifications.training_confirmed.first_line')),
                    str_replace("\n", '%0D%0A', __('notifications.training_confirmed.second_line', $notificationData)),
                    str_replace("\n", '%0D%0A', __('notifications.training_confirmed.salutation')),
                ]),
            ],
            'completed' => [
                'subject' => __('notifications.training_confirmed.subject', $notificationData),
                'body'    => implode("  %0D%0A%0D%0A", [
                    str_replace("\n", '%0D%0A', __('notifications.training_completed.greeting')),
                    str_replace("\n", '%0D%0A', __('notifications.training_completed.first_line', $notificationData)),
                    str_replace("\n", '%0D%0A', __('notifications.training_completed.second_line')),
                    str_replace("\n", '%0D%0A', __('notifications.training_completed.salutation')),
                ]),
            ],
            'cancelled' => [
                'subject' => __('notifications.training_confirmed.subject', $notificationData),
                'body'    => implode("  %0D%0A", [
                    str_replace("\n", '%0D%0A', __('notifications.training_cancelled.greeting')),
                    str_replace("\n", '%0D%0A', __('notifications.training_cancelled.first_line', $notificationData)),
                    str_replace("\n", '%0D%0A', __('notifications.training_cancelled.second_line')),
                    str_replace("\n", '%0D%0A', __('notifications.training_cancelled.salutation')),
                ]),
            ],
        ];
    }

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(TrainingFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
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
        return $this->BelongsToMany(Kita::class, 'kita_has_trainings', 'training_id', 'kita_id');
    }
}
