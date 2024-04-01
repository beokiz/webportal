<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\EvaluationFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Evaluation Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class Evaluation extends Model
{
    use HasFactory, Filterable, HasOrderScope, CanGetTableNameStatically;

    // use SoftDeletes;

    const CHILD_AGE_GROUP_2 = '2.5';
    const CHILD_AGE_GROUP_4 = '4.5';
    const CHILD_AGE_GROUPS = [self::CHILD_AGE_GROUP_2, self::CHILD_AGE_GROUP_4];

    const CHILD_DURATION_IN_KITA_1 = 'upToOneYear';
    const CHILD_DURATION_IN_KITA_2 = 'upToTwoYears';
    const CHILD_DURATION_IN_KITA_3 = 'upToThreeYears';
    const CHILD_DURATION_IN_KITA_4 = 'moreThanThreeYears';

    const CHILD_DURATION_IN_KITA_ALL_VARS = [
        self::CHILD_DURATION_IN_KITA_1,
        self::CHILD_DURATION_IN_KITA_2,
        self::CHILD_DURATION_IN_KITA_3,
        self::CHILD_DURATION_IN_KITA_4,
    ];

    const CHILD_DURATION_IN_KITA_2_VARS = [
        self::CHILD_DURATION_IN_KITA_1,
        self::CHILD_DURATION_IN_KITA_2,
        self::CHILD_DURATION_IN_KITA_3,
    ];

    const CHILD_DURATION_IN_KITA_4_VARS = [
        self::CHILD_DURATION_IN_KITA_1,
        self::CHILD_DURATION_IN_KITA_2,
        self::CHILD_DURATION_IN_KITA_3,
        self::CHILD_DURATION_IN_KITA_4,
    ];

    /**
     * @var string
     */
    protected $table = 'evaluations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'kita_id',
        'age',
        'is_daz',
        'child_duration_in_kita',
        'integration_status',
        'speech_therapy_status',
        'is_daz',
        'data',
        'finished_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'age'                   => 'float',
        'is_daz'                => 'boolean',
        'integration_status'    => 'boolean',
        'speech_therapy_status' => 'boolean',
        'data'                  => 'array',
        'finished_at'           => 'datetime',
        'not_editable_at'       => 'datetime',
        'editable'              => 'boolean',
        'finished'              => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'editable',
        'finished',
        'not_editable_at',
    ];

    /**
     * @return Attribute
     */
    public function notEditableAt() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => !is_null($this->finished_at)
                ? $this->finished_at->addMinutes(15)
                : null,
        );
    }

    /**
     * @return Attribute
     */
    public function editable() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => !is_null($this->finished_at)
                ? Carbon::now()->diffInMinutes($this->finished_at, false) > -15
                : true,
        );
    }

    /**
     * @return Attribute
     */
    public function finished() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => !is_null($this->finished_at),
        );
    }

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(EvaluationFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function kita() : BelongsTo
    {
        return $this->belongsTo(Kita::class, 'kita_id', 'id');
    }
}
