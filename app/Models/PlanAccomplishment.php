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
    public function getOfficeFromKpiAndOfficeList($only_child_array,$level) {
        $offices = Office::select('offices.*')
                ->where('level','=', $level)->whereIn('id', $only_child_array) -> orderBy('offices.level')->get();
             return $offices;


    }
    public function getAllOffices() {
             $offices = Office::select('offices.*')
                      ->get();
             return $offices;
    }
      
     // get plan and report with parametres for only one office
    public function OnlyKpiOTT($kkp,$office,$period,$is_report,$planning_year ,$one,$two,$three){
         $plan_accom = [];
         $getkpi = KeyPeformanceIndicator::find($kkp);
         $status = getStatus($kkp,$office,$period,$is_report,$planning_year ,null,null,null);
        
        
        //dd($avarage_plan[0]/$avarage_plan[1]);
        $childAndHimOffKpi_array =[];
        $childAndHimOffKpi = office_all_childs_ids($office);
        $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
        $sum_of_sub_office_plan = 0;
        $sum_of_sub_office_report = 0;
       
        $office_level = $office->level;
        if($office_level == 0) $office_level=1;
        $planAccomplishments = PlanAccomplishment::select('*')
             ->where('office_id', $office->id)
            ->where('kpi_id' , '=', $kkp)
            ->where('planning_year_id','=', $planning_year)
            ->where('kpi_child_one_id' , '=', $one)
            ->where('kpi_child_two_id' , '=', $two)
            ->where('kpi_child_three_id' , '=', $three)
            ->where('reporting_period_id' , '=', $period)
            ->where('plan_status' , '<=', $office_level)
        ->get();
        $my_status = $status?->plan_status;
        //dump($planning_year);
        if($is_report){
             $planAccomplishments = PlanAccomplishment::select('*')
             ->where('office_id', $office->id)
            ->where('kpi_id' , '=', $kkp)
            ->where('planning_year_id','=', $planning_year)
            ->where('kpi_child_one_id' , '=', $one)
            ->where('kpi_child_two_id' , '=', $two)
            ->where('kpi_child_three_id' , '=', $three)
            ->where('reporting_period_id' , '=', $period)
            ->where('accom_status' , '<=', $office_level)
        ->get();
        $my_status = $status?->accom_status;
        }
        //dump($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
           $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
           $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
        }
        $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
        $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
        $plan_accom = array_merge( $plan_accom,array($my_status));
        return $plan_accom;
    }
    // get plan and report with parametres
    public function KpiOTT($kkp,$office,$period,$is_report,$planning_year ,$one,$two,$three){
         $plan_accom = [];
         $getkpi = KeyPeformanceIndicator::find($kkp);
         $status = getStatus($kkp,$office,$period,$is_report,$planning_year ,null,null,null);
        if($getkpi->measurement){     //->isEmpty()
            // kpi measurement is in percent
            if($getkpi->measurement?->slug == 'percent'){
                 $avarage_plan = calculateAveragePlan($kkp,$office,$period,$is_report,$planning_year ,$one,$two,$three); //echo $kkp." ".$office->id." ".$period." ".$is_report." ".$planning_year." ".$one."<br/> ";
                 ///dump($avarage_plan);
                $avarage_plan_of_percent = 0;
                if($avarage_plan && $avarage_plan[1]>0){
                    $avarage_plan_of_percent = (double)number_format($avarage_plan[0]/$avarage_plan[1], 2, ".", "");
                    // dd($avarage_plan_of_percent);
                }
                if(!$is_report){
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array(0));
                    $plan_accom = array_merge( $plan_accom,array($status?->plan_status));
                }
                if($is_report){
                    $plan_accom = array_merge( $plan_accom,array(0));
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array($status?->accom_status));
                }
             return $plan_accom;
            }
            else{
                
                //dd($avarage_plan[0]/$avarage_plan[1]);
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi = office_all_childs_ids($office);
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
                $sum_of_sub_office_plan = 0;
                $sum_of_sub_office_report = 0;
               
                $office_level = $office->level;
                if($office_level == 0) $office_level=1;
                $planAccomplishments = PlanAccomplishment::select('*')
                    ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('planning_year_id','=', $planning_year)
                    ->where('kpi_child_one_id' , '=', $one)
                    ->where('kpi_child_two_id' , '=', $two)
                    ->where('kpi_child_three_id' , '=', $three)
                    ->where('reporting_period_id' , '=', $period)
                    ->where('plan_status' , '<=', $office_level)
                ->get();
                $my_status = $status?->plan_status;
                //dump($planning_year);
                if($is_report){
                     $planAccomplishments = PlanAccomplishment::select('*')
                     ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('planning_year_id','=', $planning_year)
                    ->where('kpi_child_one_id' , '=', $one)
                    ->where('kpi_child_two_id' , '=', $two)
                    ->where('kpi_child_three_id' , '=', $three)
                    ->where('reporting_period_id' , '=', $period)
                    ->where('accom_status' , '<=', $office_level)
                ->get();
                $my_status = $status?->accom_status;
                }
                //dump($planAccomplishments);
                foreach ($planAccomplishments as $key => $planAccomplishment) {
                   $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                   $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                }
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($my_status));
                return $plan_accom;
            }

        } else{
                
                //dd($avarage_plan[0]/$avarage_plan[1]);
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi = office_all_childs_ids($office);
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
                $sum_of_sub_office_plan = 0;
                $sum_of_sub_office_report = 0;
               
                $office_level = $office->level;
                if($office_level == 0) $office_level=1;
                $planAccomplishments = PlanAccomplishment::select('*')
                    ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('planning_year_id','=', $planning_year)
                    ->where('kpi_child_one_id' , '=', $one)
                    ->where('kpi_child_two_id' , '=', $two)
                    ->where('kpi_child_three_id' , '=', $three)
                    ->where('reporting_period_id' , '=', $period)
                    ->where('plan_status' , '<=', $office_level)
                ->get();
                $my_status = $status?->plan_status;
                //dump($planning_year);
                if($is_report){
                     $planAccomplishments = PlanAccomplishment::select('*')
                     ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('planning_year_id','=', $planning_year)
                    ->where('kpi_child_one_id' , '=', $one)
                    ->where('kpi_child_two_id' , '=', $two)
                    ->where('kpi_child_three_id' , '=', $three)
                    ->where('reporting_period_id' , '=', $period)
                    ->where('accom_status' , '<=', $office_level)
                ->get();
                $my_status = $status?->accom_status;
                }
                //dump($planAccomplishments);
                foreach ($planAccomplishments as $key => $planAccomplishment) {
                   $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                   $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                }
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($my_status));
                return $plan_accom;
         }
    }
    // get total for kpi
    public function ForKpi($kkp,$office,$period,$is_report,$planning_year, $one, $two, $three){
        $plan_accom = [];
         $getkpi = KeyPeformanceIndicator::find($kkp); 
         $status = getStatus($kkp,$office,$period,$is_report,$planning_year ,$one, $two, $three);          
         //dd($status);
        if($getkpi->measurement){    //dump("measurement");
            // kpi measurement is in percent
            if($getkpi->measurement?->slug == 'percent'){ //dump("kpi measurement is in percent");
                $avarage_plan = calculateAveragePlan($kkp,$office,$period,$is_report,$planning_year ,$one, $two, $three); 
                
                $avarage_plan_of_percent = 0;
                 if($avarage_plan && $avarage_plan[1]>0){
                    $avarage_plan_of_percent = (double)number_format($avarage_plan[0]/$avarage_plan[1], 2, ".", "");
                     //dump($avarage_plan_of_percent);
                }
                $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                $plan_accom = array_merge( $plan_accom,array(0));
                $plan_accom = array_merge( $plan_accom,array($status?->plan_status));
                if($is_report){
                    $plan_accom = array_merge( $plan_accom,array(0));
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array($status?->accom_status));
                }
                //echo $kkp."-> ".$office->id." ->".$period."-> ".$is_report."-> ".$planning_year. "<br/> ";
                 return $plan_accom;
            }
            // if kpi with  any measurement except percent.
            else{  //dump("if kpi with  any measurement except percent");
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi = office_all_childs_ids($office);
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
                $sum_of_sub_office_plan = 0;
                $sum_of_sub_office_report = 0;
                 $office_level = $office->level;
                if($office_level == 0) $office_level=1;
                $planAccomplishments = PlanAccomplishment::select('*')
                    ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('planning_year_id','=', $planning_year)
                    ->where('reporting_period_id' , '=', $period)
                    ->where('kpi_child_one_id' , '=', $one)
                    ->where('kpi_child_two_id' , '=', $two)
                    ->where('kpi_child_three_id' , '=', $three)
                    ->where('plan_status' , '<=', $office_level)
                ->get(); 
                //dump($planAccomplishments);
                $my_status = $status?->plan_status;
                if($is_report){
                     $planAccomplishments = PlanAccomplishment::select('*')
                     ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('reporting_period_id' , '=', $period)
                    ->where('accom_status' , '<=', $office_level)
                ->get();
                 $my_status = $status?->accom_status;
                }

                foreach ($planAccomplishments as $key => $planAccomplishment) {
                   $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                   $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                }
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($my_status));
                 //dump($planAccomplishments);
                 return $plan_accom;
            }

        } 
        // if kpi with out any measurement.
        else{ //dump("if kpi with out any measurement");
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi = office_all_childs_ids($office);
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
                $sum_of_sub_office_plan = 0;
                $sum_of_sub_office_report = 0;
                 $office_level = $office->level;
                if($office_level == 0) $office_level=1;
                $planAccomplishments = PlanAccomplishment::select('*')
                    ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('planning_year_id','=', $planning_year)
                    ->where('reporting_period_id' , '=', $period)
                    ->where('kpi_child_one_id' , '=', $one)
                    ->where('kpi_child_two_id' , '=', $two)
                    ->where('kpi_child_three_id' , '=', $three)
                    ->where('plan_status' , '<=', $office_level)
                ->get(); 
                $my_status = $status?->plan_status;
                //dump($planAccomplishments);
                //d/ump($office_level);
                //dump($planning_year);
                if($is_report){
                     $planAccomplishments = PlanAccomplishment::select('*')
                     ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('reporting_period_id' , '=', $period)
                    ->where('kpi_child_one_id' , '=', $one)
                    ->where('kpi_child_two_id' , '=', $two)
                    ->where('kpi_child_three_id' , '=', $three)
                    ->where('accom_status' , '<=', $office_level)
                ->get();
                 $my_status = $status?->accom_status;
                }

                foreach ($planAccomplishments as $key => $planAccomplishment) {
                   $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                   $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                }
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($my_status));//dump($my_status);
                 return $plan_accom;
    }
    //dump("why");
    //dd("last");
    return $plan_accom;
}

    // public function planIndividual($kkp,$one,$two,$three,$office,$period){
    //     $childAndHimOffKpi_array =[];
    //     $childAndHimOffKpi = office_all_childs_ids($office);
    //     $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    //     $sum_of_sub_office_plan = 0;
    //     $sum_of_sub_office_report = 0;
    //     $plan_accom = [];
    //     $office_level = $office->level;
    //     if($office_level == 0) $office_level=1;
    //     $planAccomplishments = PlanAccomplishment::select('*')
    //     ->whereIn('office_id', $childAndHimOffKpi_array)
    //     ->where('kpi_id' , '=', $kkp)
    //     ->where('kpi_child_one_id' , '=', $one)
    //     ->where('kpi_child_two_id' , '=', $two)
    //     ->where('kpi_child_three_id' , '=', $three)
    //     ->where('reporting_period_id' , '=', $period)
    //     ->where('plan_status' , '<=', $office_level)
    //     ->get();//dump($planAccomplishments);
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {
    //        $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
    //        $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
    //     }
    //     $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
    //     $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
    //      return $plan_accom;
    // }
    // public function planIndividualChOnechThreeSum($kkp,$one,$two,$three,$office){
    //     $sumch1ch3_value = 0;
    //     $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id' , $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->where('kpi_child_three_id' , '=', $three)->get();
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {

    //         $sumch1ch3_value = $sumch1ch3_value+$planAccomplishment->plan_value;
    //     }
    //      return $sumch1ch3_value;

    // }
    // public function planIndividualChOnech($kkp,$one,$two,$office){
    //     $sumch1ch3_value = 0;
    //     $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id' , $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_one_id' , '=', $one)->get();
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {

    //         $sumch1ch3_value = $sumch1ch3_value+$planAccomplishment->plan_value;
    //     }
    //      return $sumch1ch3_value;

    // }
    // public function planIndividualChTwoSum($kkp,$two,$office ,$period){
    //     $sumch1ch3_value = 0;
    //     $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id' , $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_two_id' , '=', $two)->where('reporting_period_id' , '=', $period)->get();
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {

    //         $sumch1ch3_value = $sumch1ch3_value+$planAccomplishment->plan_value;
    //     }
    //      return $sumch1ch3_value;

    // }

    // public function planIndividualChOneSum($kkp,$office){
    //     $sumch1ch3_value = 0;
    //     $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id' , $office)->where('kpi_id' , '=', $kkp)->where('kpi_child_two_id' , '=', $two)->get();
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {

    //         $sumch1ch3_value = $sumch1ch3_value+$planAccomplishment->plan_value;
    //     }
    //      return $sumch1ch3_value;

    // }

    // public function planSumOfKpi($kkp,$office){
    //     $childAndHimOffKpi_array =[];
    //     $childAndHimOffKpi = office_all_childs_ids($office);
    //     $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    //      $sumch1ch3_value = 0;
    //     $planAccomplishments = PlanAccomplishment::select('plan_value')->whereIn('office_id', $childAndHimOffKpi_array)->where('kpi_id' , '=', $kkp)->get();
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {
    //          $sumch1ch3_value = $sumch1ch3_value+$planAccomplishment->plan_value;
    //      } //dd($sumch1ch3_value);
    //      return $sumch1ch3_value;
    // }

    // Get kpi value
    //  public function planSum($kkp,$office,$period,$is_report,$planning_year){
    //      $childAndHimOffKpi_array =[];
    //     $childAndHimOffKpi = office_all_childs_ids($office);
    //     $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    //       $sum_of_sub_office_plan = 0;
    //     $sum_of_sub_office_report = 0;
    //     $plan_accom = [];
    //     $office_level = $office->level;
    //     if($office_level == 0) $office_level=1;
    //     //dump($office_level);
    //     $planAccomplishments = PlanAccomplishment::select('*')
    //     ->whereIn('office_id' , $childAndHimOffKpi_array)
    //     ->where('planning_year_id','=', $planning_year->id ?? NULL)
    //     ->where('kpi_id' , '=', $kkp)
    //      ->where('reporting_period_id' , '=', $period)

    //     ->where('plan_status' , '<=', $office_level)
    //     ->get();
    //     if($is_report){
    //          $planAccomplishments = PlanAccomplishment::select('*')
    //          ->whereIn('office_id' , $childAndHimOffKpi_array)
    //          ->where('planning_year_id','=', $planning_year->id ?? NULL)
    //          ->where('kpi_id' , '=', $kkp)
    //          ->where('reporting_period_id' , '=', $period)
    //          ->where('accom_status' , '<=', $office_level)
    //          ->get();
    //     }
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {
    //         $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
    //         $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
    //     }
    //    $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
    //     $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));

    //      return $plan_accom;
    //  }
    // public function planOne($kkp,$one,$office,$period){
    //     $childAndHimOffKpi_array =[];
    //     $childAndHimOffKpi = office_all_childs_ids($office);
    //     $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    //      $sum_of_sub_office_plan = 0;
    //     $sum_of_sub_office_report = 0;
    //     $plan_accom = [];
    //     $office_level = $office->level;
    //     if($office_level == 0) $office_level=1;
    //     $planAccomplishments = PlanAccomplishment::select('*')
    //     ->whereIn('office_id' , $childAndHimOffKpi_array)
    //     ->where('kpi_id' , '=', $kkp)
    //     ->where('kpi_child_one_id' , '=', $one)
    //     ->where('reporting_period_id' , '=', $period)
    //     ->where('plan_status' , '<=', $office_level)
    //     ->get();
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {
    //         $sum_of_sub_office_plan =$sum_of_sub_office_plan +$planAccomplishment->plan_value;
    //         $sum_of_sub_office_report =$sum_of_sub_office_report +$planAccomplishment->accom_value;

    //     }
    //     $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
    //     $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
    //      return $plan_accom;
    // }
    // public function planOneTwo($kkp,$one,$two,$office,$period){
    //      $childAndHimOffKpi_array =[];
    //     $childAndHimOffKpi = office_all_childs_ids($office);
    //     $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
    //      $sum_of_sub_office_plan = 0;
    //     $sum_of_sub_office_report = 0;
    //     $plan_accom = [];
    //     $office_level = $office->level;
    //     if($office_level == 0) $office_level=1;
    //     $planAccomplishments = PlanAccomplishment::select('*')
    //     ->whereIn('office_id', $childAndHimOffKpi_array)
    //     ->where('kpi_id' , '=', $kkp)
    //     ->where('kpi_child_one_id' , '=', $one)
    //     ->where('kpi_child_two_id' , '=', $two)
    //     ->where('reporting_period_id' , '=', $period)
    //     ->where('plan_status' , '<=', $office_level)
    //     ->get();//dd($planAccomplishments);
    //     foreach ($planAccomplishments as $key => $planAccomplishment) {
    //          $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
    //         $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
    //     }
    //    $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
    //     $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));

    //      return $plan_accom;
    // }

    public function getNarration($kkp,$year,$office){
        // get all child and subchild offices for login user
        $childAndHimOffKpi_array =[];
        $childAndHimOffKpi = office_all_childs_ids($office);
        $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
         $office_level = $office->level;
        if($office_level == 0) $office_level=1;
        $plannarations = ReportNarration::select('*')
        // ->join('offices', 'offices.id', '=', 'report_narrations.office_id')
        // ->join('plan_accomplishments', 'plan_accomplishments.office_id', '=', 'offices.id')
        ->where('key_peformance_indicator_id' , '=', $kkp)
        ->whereIn('office_id' , $childAndHimOffKpi_array)
        ->where('planing_year_id' , '=', $year)
        ->where('approval_status' , '<=', $office_level)
        ->get();
         //dump($plannarations);
        // dump("x");
            return $plannarations;
          foreach ($plannarations as $key => $plannaration) {
             return $plannaration->plan_naration;
        }

    }
     public function getReportNarration($kkp,$year,$office,$period){
        // get all child and subchild offices for login user
        $childAndHimOffKpi_array =[];
        $childAndHimOffKpi = office_all_childs_ids($office);
        $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
        $reportNarations = ReportNarrationReport::select('*')->whereIn('office_id' , $childAndHimOffKpi_array)->where('key_peformance_indicator_id' , '=', $kkp)->where('planing_year_id' , '=', $year)->get();
            return $reportNarations;

    }
    // get single office naration.
    public function OnlygetNarration($kkp,$year,$office){
         $office_level = $office->level;
        if($office_level == 0) $office_level=1;
        $plannarations = ReportNarration::select('*')
        // ->join('offices', 'offices.id', '=', 'report_narrations.office_id')
        // ->join('plan_accomplishments', 'plan_accomplishments.office_id', '=', 'offices.id')
        ->where('key_peformance_indicator_id' , '=', $kkp)
        ->where('office_id', "=", $office->id)
        ->where('planing_year_id' , '=', $year)
        ->where('approval_status' , '<=', $office_level)
        ->get();
         //dump($office);
             return $plannarations;
          foreach ($plannarations as $key => $plannaration) {
             return $plannaration->plan_naration;
        }

    }
}
