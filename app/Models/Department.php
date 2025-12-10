<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'Department';
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];
}
