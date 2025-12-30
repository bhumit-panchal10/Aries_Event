<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;
    protected $table = 'visitor';
    protected $fillable = [
        'id',
        'mobileno',
        'companyname',
        'name',
        'email',
        'stateid',
        'cityid',
        'user_id',
        'expo_id',
        'industry_id',
        'address',
        'iStatus',
        'iSDelete',
        'created_at',
        'updated_at',
        'enter_by'
    ];

    public function state()
    {
        return $this->belongsTo(StateMaster::class, 'stateid', 'stateId');
    }

    public function city()
    {
        return $this->belongsTo(CityMaster::class, 'cityid', 'id');
    }
}
