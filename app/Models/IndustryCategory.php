<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryCategory extends Model
{
    use HasFactory;
    protected $table = 'industry_categories';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'industry_id',
        'industry_category_name',
        'isDelete',
        'iStatus',
        'entry_by'
    ];

    protected $casts = [
        'industry_id' => 'integer',
        'isDelete'    => 'integer',
        'iStatus'     => 'integer',
        'entry_by'    => 'integer',
    ];
    
    public function industry()
    {
        return $this->belongsTo(
            IndustryMaster::class,
            'industry_id',
            'id'
        );
    }
}
