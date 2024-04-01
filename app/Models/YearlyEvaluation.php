<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\YearlyEvaluationsFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * YearlyEvaluations Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class YearlyEvaluation extends Model
{
    use HasFactory, Filterable, HasOrderScope, CanGetTableNameStatically;

    // use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'yearly_evaluations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'year',
        'kita_id',
        'children_2_born_per_year',
        'children_4_born_per_year',
        'children_2_with_german_lang',
        'children_4_with_german_lang',
        'children_2_with_foreign_lang',
        'children_4_with_foreign_lang',
    ];

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(YearlyEvaluationsFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    /**
     * @return BelongsTo
     */
    public function kita() : BelongsTo
    {
        return $this->belongsTo(Kita::class, 'kita_id', 'id');
    }
}
