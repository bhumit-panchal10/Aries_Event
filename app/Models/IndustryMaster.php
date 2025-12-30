<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryMaster extends Model
{
    use HasFactory;
    protected $table = 'Industry';
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];
    
    public function categories()
    {
        return $this->hasMany(
            IndustryCategory::class,
            'industry_id',
            'id'
        );
    }
    
    public function subCategories()
    {
        return $this->hasMany(IndustrySubCategory::class, 'industry_id', 'id');
    }
}
