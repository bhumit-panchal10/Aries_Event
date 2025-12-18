<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpoMaster extends Model
{
    use HasFactory;
    protected $table = 'expo-master';
    protected $fillable = [
        'id',
        'industry_id',
        'state_id',
        'city_id',
        'name',
        'date',
        'slugname',
        'created_at',
        'updated_at',
    ];
    public function state()
    {
        return $this->belongsTo(StateMaster::class, 'state_id', 'stateId');
    }

    public function city()
    {
        return $this->belongsTo(CityMaster::class, 'city_id', 'id');
    }

    public function industry()
    {
        return $this->belongsTo(IndustryMaster::class, 'industry_id', 'id');
    }
}
