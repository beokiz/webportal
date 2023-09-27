<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\SubdomainFilter;
use App\Models\Traits\CanGetTableNameStatically;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Subdomain Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class Subdomain extends Model
{
    use HasFactory, Filterable, HasOrderScope, CanGetTableNameStatically;

    // SoftDeletes

    /**
     * @var string
     */
    protected $table = 'subdomains';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'domain_id',
        'name',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(SubdomainFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    /**
     * @return BelongsTo
     */
    public function domain() : BelongsTo
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'id');
    }
}
