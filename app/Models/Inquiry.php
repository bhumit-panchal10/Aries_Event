<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;
    protected $table = 'inquiry';
    protected $fillable = [
        'inquiry_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'imei_1',
        'imei_2',
        'brand',
        'device_condition',
        'message',
        'model',
        'expected_amt',
        'address',
        'status',
        'prefix',
        'invoiceid',
        'invoiceno',
        'actual_amount',
        'created_at',
        'updated_at',
        'schedule_date',
        'schedule_time',
        'pickup_date',
        'pickup_time',
        'gst',
        'total_amount'

    ];
}
