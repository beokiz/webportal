<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\SurveyTimePeriodFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * SurveyTimePeriod Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class SurveyTimePeriod extends Model
{
    use HasFactory, Filterable, HasOrderScope, CanGetTableNameStatically;

    // use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'survey_time_periods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'year',
        'survey_start_date',
        'survey_end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'survey_start_date' => 'date',
        'survey_end_date'   => 'date',
    ];

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(SurveyTimePeriodFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
}
