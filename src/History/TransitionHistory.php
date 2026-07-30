<?php

namespace Caner\StateMachine\History;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class TransitionHistory extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return (string) config('state-machine.history.table', parent::getTable());
    }

    /** @return MorphTo<Model, TransitionHistory> */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return MorphTo<Model, TransitionHistory> */
    public function actor(): MorphTo
    {
        return $this->morphTo();
    }
}
