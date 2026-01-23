<?php

namespace App\Exports;

use App\Models\Visitorvisit;
use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class VisitorsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $expoId;
    protected $industryId;
    protected $isPre;
    protected $isVisit;
    protected $counter = 1;

    public function __construct($expoId, $industryId, $isPre, $isVisit)
    {
        $this->expoId = $expoId;
        $this->industryId = $industryId;
        $this->isPre = $isPre;
        $this->isVisit = $isVisit;
    }

    public function collection()
    {
        $query = Visitor::query()
            ->select([
                'visitor.id',
                'visitor.mobileno',
                'visitor.companyname',
                'visitor.name',
                'visitor.email',
                'state.stateName as state_name',
                'City.name as city_name',
                'visitor.created_at',
                // Get Is_Pre from the latest matching visit
                DB::raw("(
                    SELECT vv.Is_Pre 
                    FROM visitor_visit vv 
                    WHERE vv.visitor_id = visitor.id 
                    " . ($this->expoId !== null ? " AND vv.expo_id = " . $this->expoId : "") . "
                    " . ($this->isPre != 2 ? " AND vv.Is_Pre = " . $this->isPre : "") . "
                    " . ($this->isVisit != 2 ? " AND vv.Is_Visit = " . $this->isVisit : "") . "
                    ORDER BY vv.created_at DESC 
                    LIMIT 1
                ) as Is_Pre"),
                // Get Is_Visit from the latest matching visit
                DB::raw("(
                    SELECT vv.Is_Visit 
                    FROM visitor_visit vv 
                    WHERE vv.visitor_id = visitor.id 
                    " . ($this->expoId !== null ? " AND vv.expo_id = " . $this->expoId : "") . "
                    " . ($this->isPre != 2 ? " AND vv.Is_Pre = " . $this->isPre : "") . "
                    " . ($this->isVisit != 2 ? " AND vv.Is_Visit = " . $this->isVisit : "") . "
                    ORDER BY vv.created_at DESC 
                    LIMIT 1
                ) as Is_Visit")
            ])
            ->leftJoin('state', 'state.stateId', '=', 'visitor.stateid')
            ->leftJoin('City', 'City.id', '=', 'visitor.cityid')
            ->where('visitor.industry_id', $this->industryId);
            
        // If any filters are applied, only show visitors with matching visits
        if ($this->expoId !== null || $this->isPre != 2 || $this->isVisit != 2) {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('visitor_visit')
                  ->whereColumn('visitor_visit.visitor_id', 'visitor.id');
                  
                if ($this->expoId !== null) {
                    $q->where('visitor_visit.expo_id', $this->expoId);
                }
                
                if ($this->isPre != 2) {
                    $q->where('visitor_visit.Is_Pre', $this->isPre);
                }
                
                if ($this->isVisit != 2) {
                    $q->where('visitor_visit.Is_Visit', $this->isVisit);
                }
            });
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Mobile No',
            'Email',
            'Company Name',
            'State',
            'City',
            'Is Pre',
            'Is Visit',
            'Created At'
        ];
    }
    
    public function map($row): array
    {
        // Convert numeric values to Yes/No
        $isPre = $row->Is_Pre == 1 ? 'Yes' : 'No';
        $isVisit = $row->Is_Visit == 1 ? 'Yes' : 'No';
        
        // Handle null values (if no matching visit found)
        if ($row->Is_Pre === null) {
            $isPre = 'No';
        }
        if ($row->Is_Visit === null) {
            $isVisit = 'No';
        }

        return [
            $this->counter++,
            $row->name,
            $row->mobileno,
            $row->email,
            $row->companyname,
            $row->state_name ?? 'N/A',
            $row->city_name ?? 'N/A',
            $isPre,
            $isVisit,
            $row->created_at->format('Y-m-d H:i:s'),
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