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

/**
 * trainingProposalConfirmation Model
 *
 * @mixin \Eloquent
 * @package \App\Models
 */
class TrainingProposalConfirmation extends Model
{
    /**
     * @var string
     */
    protected $table = 'training_proposal_confirmations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'training_proposal_id',
        'kita_id',
        'confirmed',
        'token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define Model Relations
    |--------------------------------------------------------------------------
    */
    /**
     * @return BelongsTo
     */
    public function trainingProposal() : BelongsTo
    {
        return $this->belongsTo(TrainingProposal::class, 'training_proposal_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function kita() : BelongsTo
    {
        return $this->belongsTo(Kita::class, 'kita_id', 'id');
    }
}
