<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlanExcelExport implements FromView
{
    protected $planAccomplishments;
    protected $only_child_array;
    protected $off_level;
    protected $imagen_off;
    protected $planning_year;
    protected $officeSentToBlade;
    protected $managerName;

    public function __construct($planAccomplishments,$only_child_array,$off_level,$imagen_off,$planning_year,$officeSentToBlade,$managerName)
    {
        $this->planAccomplishments = $planAccomplishments;
        $this->only_child_array = $only_child_array;
        $this->off_level = $off_level;
        $this->imagen_off = $imagen_off;
        $this->planning_year = $planning_year;
        $this->officeSentToBlade = $officeSentToBlade;
        $this->managerName = $managerName;
    }

    public function view(): View
    {
        return view('app.plan_accomplishments.excel-plan', [
            'planAccomplishments' => $this->planAccomplishments,
            'only_child_array' => $this->only_child_array,
            'off_level' => $this->off_level,
            'imagen_off' => $this->imagen_off,
            'planning_year' => $this->planning_year,
            'officeSentToBlade' => $this->officeSentToBlade,
            'managerName' => $this->managerName,
        ]);
    }

}
