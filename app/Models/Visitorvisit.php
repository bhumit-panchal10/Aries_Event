<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitorvisit extends Model
{
    use HasFactory;
    protected $table = 'visitor_visit';
    protected $fillable = [
        'id',
        'visitor_id',
        'expo_id',
        'created_at',
        'updated_at',
        'user_id',
        'Is_Pre',
        'Is_Visit',
    ];
    
    // In Visitorvisit model
    public function visitor()
    {
        return $this->belongsTo(Visitor::class, 'visitor_id'); // Adjust the foreign key if needed
    }
    
}
