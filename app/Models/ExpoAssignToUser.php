<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpoAssignToUser extends Model
{
    use HasFactory;
    protected $table = 'ExpoAssignToUser';
    protected $fillable = [
        'id',
        'industry_id',
        'expo_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function industry()
    {
        return $this->belongsTo(IndustryMaster::class, 'industry_id', 'id');
    }

    public function expomaster()
    {
        return $this->belongsTo(ExpoMaster::class, 'expo_id', 'id');
    }
}
