<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitorPrimaryContact extends Model
{
    protected $table = 'ExhibitorPrimaryContacts';

    protected $fillable = [
        'expo_id',
        'primary_contact_mobile',
        'primary_contact_name',
        'primary_contact_designation',
        'primary_contact_email',
        'enter_by',
        'iStatus',
        'iSDelete',
    ];

    public function company()
    {
        return $this->hasOne(
            ExhibitorCompanyInformation::class,
            'exhibitor_primary_contact_id',
            'id'
        );
    }

    public function otherContacts()
    {
        return $this->hasMany(
            ExhibitorOtherContact::class,
            'exhibitor_primary_contact_id',
            'id'
        );
    }
}
