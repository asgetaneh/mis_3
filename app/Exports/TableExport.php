<?php

namespace App\Exports;

use App\Models\PlanAccomplishment;
use Maatwebsite\Excel\Concerns\FromCollection;

class TableExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PlanAccomplishment::all();
    }
}
