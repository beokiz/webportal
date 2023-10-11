<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\KitaFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'order',
        'zip_code',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'order' => 'integer',
    ];

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
        return $this->BelongsToMany(User::class, 'kita_has_users_primary', 'kita_id', 'user_id');
    }
}
