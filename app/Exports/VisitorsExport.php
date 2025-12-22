<?php

namespace App\Exports;

use App\Models\Visitorvisit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitorsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $expoId;
    protected $industryId;
    protected $isPre;
    protected $isVisit;

    public function __construct($expoId, $industryId, $isPre, $isVisit)
    {
        $this->expoId = $expoId;
        $this->industryId = $industryId;
        $this->isPre = $isPre;
        $this->isVisit = $isVisit;
    }

    public function collection()
    {
        $query = Visitorvisit::with(['visitor.state', 'visitor.city'])
            ->where('expo_id', $this->expoId)
            ->whereHas('visitor', function ($q) {
                $q->where('industry_id', $this->industryId);
            });

        if ($this->isPre != 2) {
            $query->where('Is_Pre', $this->isPre);
        }

        if ($this->isVisit != 2) {
            $query->where('Is_Visit', $this->isVisit);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Mobile No',
            'Company Name',
            'Name',
            'Email',
            'State',
            'City',
            // 'State ID',
            // 'City ID',
            'Is Pre',
            'Is Visit',
            'Created At'
        ];
    }

    public function map($visit): array
    {
        return [
            $visit->visitor->id,
            $visit->visitor->mobileno,
            $visit->visitor->companyname,
            $visit->visitor->name,
            $visit->visitor->email,
            $visit->visitor->state ? $visit->visitor->state->stateName : 'N/A',
            $visit->visitor->city ? $visit->visitor->city->name : 'N/A',
            // $visit->visitor->stateid,
            // $visit->visitor->cityid,
            $visit->Is_Pre == 1 ? 'Yes' : 'No',
            $visit->Is_Visit == 1 ? 'Yes' : 'No',
            $visit->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}