<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityMaster extends Model
{
    use HasFactory;
    protected $table = 'City';
    protected $fillable = [
        'id',
        'name',
        'stateid',
        'iStatus',
        'iSDelete',
        'created_at',
        'updated_at',
    ];

    public function state()
    {
        return $this->belongsTo(StateMaster::class, 'stateid', 'stateId');
    }
}
