<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitorOtherContact extends Model
{
    protected $table = 'ExhibitorOtherContacts';

    protected $fillable = [
        'exhibitor_primary_contact_id',
        'exhibitor_company_information_id',
        'expo_id',
        'other_contact_mobile',
        'other_contact_name',
        'other_contact_designation',
        'other_contact_email',
        'enter_by',
        'iStatus',
        'iSDelete',
    ];

    public function company()
    {
        return $this->belongsTo(
            ExhibitorCompanyInformation::class,
            'exhibitor_company_information_id',
            'id'
        );
    }

    public function primaryContact()
    {
        return $this->belongsTo(
            ExhibitorPrimaryContact::class,
            'exhibitor_primary_contact_id',
            'id'
        );
    }
}
