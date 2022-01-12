<?php

namespace Caner\StateMachine\Tests\Stubs\Models;

use Caner\StateMachine\Traits\HasState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasFactory;
    use HasState;

    protected $fillable = [
        'name',
        'status',
    ];
}
