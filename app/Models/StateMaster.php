<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateMaster extends Model
{
    use HasFactory;
    protected $table = 'state';
    protected $fillable = [
        'stateId',
        'stateName',
        'iStatus',
        'iSDelete',
        'created_at',
        'updated_at',
        'strIP',

    ];
}
