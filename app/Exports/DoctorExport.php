<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\WithHeadings;


    /**
    * @return \Illuminate\Support\Collection
    */
class DoctorExport implements FromCollection, WithHeadings
{
    public function __construct(private $search = null) {}

    public function collection()
    {
        return Doctor::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('emp_id', 'like', "%{$this->search}%"))
            ->get(['name', 'emp_id', 'hq', 'photo', 'created_at']);
    }

    public function headings(): array
    {
        return ['Name', 'Emp ID', 'Headquarters', 'Photo URL', 'Created At'];
    }
}

