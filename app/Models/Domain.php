<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\DomainFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Domain Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class Domain extends Model
{
    use HasFactory, Filterable, HasOrderScope, CanGetTableNameStatically;

    // use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'domains';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'abbreviation',
        'order',
        'age_2_red_threshold',
        'age_2_red_threshold_daz',
        'age_2_yellow_threshold',
        'age_2_yellow_threshold_daz',
        'age_4_red_threshold',
        'age_4_red_threshold_daz',
        'age_4_yellow_threshold',
        'age_4_yellow_threshold_daz',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'order'                      => 'integer',
        'age_2_red_threshold'        => 'integer',
        'age_2_red_threshold_daz'    => 'integer',
        'age_2_yellow_threshold'     => 'integer',
        'age_2_yellow_threshold_daz' => 'integer',
        'age_4_red_threshold'        => 'integer',
        'age_4_red_threshold_daz'    => 'integer',
        'age_4_yellow_threshold'     => 'integer',
        'age_4_yellow_threshold_daz' => 'integer',
    ];

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(DomainFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    /**
     * @return HasMany
     */
    public function subdomains() : HasMany
    {
        return $this->hasMany(Subdomain::class, 'domain_id', 'id');
    }
}
