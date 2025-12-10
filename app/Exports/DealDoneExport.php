<?php

namespace App\Exports;

use App\Models\Inquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DealDoneExport implements FromCollection, WithHeadings, WithMapping
{
    protected $from_date, $to_date;

    public function __construct($from_date = null, $to_date = null)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function collection()
    {
        $query = Inquiry::where('status', 4);

        if ($this->from_date && $this->to_date) {
            $query->whereBetween('schedule_date', [$this->from_date, $this->to_date]);
        } elseif ($this->from_date) {
            $query->whereDate('schedule_date', '>=', $this->from_date);
        } elseif ($this->to_date) {
            $query->whereDate('schedule_date', '<=', $this->to_date);
        }

        return $query->orderBy('inquiry_id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Customer Name',
            'Customer Phone',
            'Customer Email',
            'IMEI 1',
            'IMEI 2',
            'Brand',
            'Model',
            'Expected Amount',
            'Actual Amount',
            'Address',
            'Schedule Date',
            'Schedule Time',
        ];
    }

    public function map($Inquiry): array
    {
        static $i = 0;
        $i++;

        return [
            $i,
            $Inquiry->customer_name,
            $Inquiry->customer_phone,
            $Inquiry->customer_email,
            $Inquiry->imei_1,
            $Inquiry->imei_2,
            $Inquiry->brand,
            $Inquiry->model,
            $Inquiry->expected_amt,
            $Inquiry->actual_amount,
            $Inquiry->address,
            date('d-m-Y', strtotime($Inquiry->schedule_date)),
            date('h:i A', strtotime($Inquiry->schedule_time)),
        ];
    }
}
