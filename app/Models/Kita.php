<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\KitaFilter;
use App\ModelFilters\YearlyEvaluationsFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * Kita Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class Kita extends Model
{
    use HasFactory, Filterable, HasOrderScope, CanGetTableNameStatically;

    // use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'kitas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'provider_of_the_kita',
        'city',
        'number',
        'street',
        'house_number',
        'additional_info',
        'zip_code',
        'order',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'order'                                  => 'integer',
        'number'                                 => 'integer',
        'is_yearly_evaluation_reminder_ntf_sent' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'formatted_name',
    ];

    /**
     * @return Attribute
     */
    public function formattedName() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Str::slug($this->name, '_'),
        );
    }

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(KitaFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    /**
     * @return BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'kita_has_users', 'kita_id', 'user_id');
    }

    /**
     * @return HasMany
     */
    public function evaluations() : HasMany
    {
        return $this->hasMany(Subdomain::class, 'kita_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function yearlyEvaluations() : HasMany
    {
        return $this->hasMany(YearlyEvaluation::class, 'kita_id', 'id');
    }
}
