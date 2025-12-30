<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndustrySubCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'IndustrySubCategory';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'industry_id',
        'industry_category_id',
        'industry_subcategory_name',
        'iStatus',
        'entry_by',
        'isDelete'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'iStatus' => 'integer',
        'entry_by' => 'integer',
        'isDelete' => 'integer',
        'industry_id' => 'integer',
        'industry_category_id' => 'integer'
    ];

    /**
     * Relationship with Industry
     */
    public function industry()
    {
        return $this->belongsTo(IndustryMaster::class, 'industry_id', 'id');
    }

    /**
     * Relationship with IndustryCategory
     */
    public function industryCategory()
    {
        return $this->belongsTo(IndustryCategory::class, 'industry_category_id', 'id');
    }
}