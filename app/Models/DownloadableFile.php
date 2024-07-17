<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models;

use App\ModelFilters\DownloadableFileFilter;
use App\Models\Traits\HasOrderScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * DownloadableFiles Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class DownloadableFile extends Model
{
    use Filterable, HasOrderScope;

    /**
     * @var string
     */
    protected $table = 'downloadable_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'filename',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'path',
    ];

    /**
     * @return Attribute
     */
    public function path() : Attribute
    {
        $storage = Storage::disk('public_files');

        return Attribute::make(
            get: fn($value, $attributes) => !empty($this->filename) && $storage->exists($this->filename)
                ? $storage->url("files/{$this->filename}")
                : null,
        );
    }

    /**
     * @return string|null
     */
    public function modelFilter() : ?string
    {
        return $this->provideFilter(DownloadableFileFilter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
}
