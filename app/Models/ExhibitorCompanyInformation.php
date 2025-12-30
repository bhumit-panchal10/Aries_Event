<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitorCompanyInformation extends Model
{
    protected $table = 'ExhibitorCompanyInformations';

    protected $fillable = [
        'exhibitor_primary_contact_id',
        'expo_id',
        'company_name',
        'gst',
        'state_id',
        'city_id',
        'address',
        'industry_id',
        'category_id',
        'subcategory_id',
        'store_size_sq_meter',
        'enter_by',
        'iStatus',
        'iSDelete',
    ];

    public function primaryContact()
    {
        return $this->belongsTo(
            ExhibitorPrimaryContact::class,
            'exhibitor_primary_contact_id',
            'id'
        );
    }

    public function otherContacts()
    {
        return $this->hasMany(
            ExhibitorOtherContact::class,
            'exhibitor_company_information_id',
            'id'
        );
    }
}
