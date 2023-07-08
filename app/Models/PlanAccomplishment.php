<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanAccomplishment extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'suitable_kpi_id',
        'reporting_period_id',
        'plan_value',
        'accom_value',
        'plan_status',
        'accom_status',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'plan_accomplishments';

    public function Kpi()
    {
        return $this->belongsTo(KeyPeformanceIndicator::class);
    }
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function reportingPeriod()
    {
        return $this->belongsTo(ReportingPeriod::class);
    }
     public function planningYear()
    {
        return $this->belongsTo(PlaningYear::class);
    }
    public function getOfficeFromKpiAndOfficeList($kpi,$off_list) {//dump($off_list);
        $offices = Office::select('offices.*')
                      ->join('kpi_office', 'offices.id', '=', 'kpi_office.office_id')
                     ->join('key_peformance_indicators', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                      ->whereIn('office_id', $off_list)
                      ->where('kpi_id' , '=', $kpi->id)
                     ->get();
            return $offices;
        

        
    }
    public function planIndividual($kkp,$one,$two,$three,$office){
        $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id' , '=', $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->where('kpi_child_three_id' , '=', $three)->get();
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment->plan_value;
        }
        
    }
    public function planOne($kkp,$one,$office){
        $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id' , '=', $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->get();
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment->plan_value;
        }
        
    }
    public function planOneTwo($kkp,$one,$two,$office){
        $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id' , '=', $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_two_id' , '=', $two)->get();
        foreach ($planAccomplishments as $key => $planAccomplishment) {
            return $planAccomplishment->plan_value;
        }
        
    }
    public function planSum($kkp,$office){
        $planAccomplishments = PlanAccomplishment::select('plan_value')->where('office_id' , '=', $office)->where('kpi_id' , '=', $kkp)->get();
        foreach ($planAccomplishments as $key => $planAccomplishment) {
           return $planAccomplishment->plan_value;
        }
     }
}
