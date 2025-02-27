<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

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
        $offices = Office::select('offices.id','offices.level')
                ->where('level','=', $level)
                ->whereIn('id', $only_child_array)
                -> orderBy('offices.level')
                ->get();
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
        $childAndHimOffKpi_array =[];
        $childAndHimOffKpi = office_all_childs_ids($office);
        $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
        $sum_of_sub_office_plan = 0;
        $sum_of_sub_office_report = 0;

        $office_level = $office->level;
        if($office_level == 0) $office_level=1;
        $planAccomplishments = PlanAccomplishment::select('plan_value')
                     ->where('office_id' , '=', $office->id)
                    ->where('kpi_id' , '=', $kkp)
                    ->where('planning_year_id','=', $planning_year)
                    ->where('kpi_child_one_id' , '=', $one)
                    ->where('kpi_child_two_id' , '=', $two)
                    ->where('kpi_child_three_id' , '=', $three)
                    ->where('reporting_period_id' , '=', $period)
                     ->where('plan_status' , '<=', $office_level)
                ->get(); //dump($office);
        // $my_status = $status[0]?->plan_status;
        //dump($planning_year);
        if($is_report){
            $planAccomplishments = DB::select("
                    SELECT accom_value
                    FROM plan_accomplishments
                    WHERE office_id IN (?)
                    AND kpi_id = ?
                    AND planning_year_id = ?
                    AND kpi_child_one_id = ?
                    AND kpi_child_two_id = ?
                    AND kpi_child_three_id = ?
                    AND reporting_period_id = ?
                    AND plan_status <= ?
                ", [
                    implode(',', $childAndHimOffKpi_array),
                    $kkp,
                    $planning_year,
                    $one,
                    $two,
                    $three,
                    $period,
                    $office_level
                ]);
        $my_status = $status?->accom_status;
        }
        //dump($planAccomplishments);
        foreach ($planAccomplishments as $key => $planAccomplishment) {
           $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
           $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
        }
        $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
        $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
        $plan_accom = array_merge( $plan_accom,array($status));
        return $plan_accom;
    }
    // get plan and report with parametres
    public function KpiOTT($kkp,$office,$period,$is_report,$planning_year ,$one,$two,$three){
         $plan_accom = [];
         $getkpi = KeyPeformanceIndicator::find($kkp);
         $status = getStatus($kkp,$office,$period,$is_report,$planning_year ,null,null,null);
        //dump($office);
         if($getkpi->measurement){     //->isEmpty()
            // kpi measurement is in percent
            if($getkpi->measurement?->slug == 'percent'){
                 $avarage_plan = calculateAveragePlan($kkp,$office,$period,$is_report,$planning_year ,$one,$two,$three); //echo $kkp." ".$office->id." ".$period." ".$is_report." ".$planning_year." ".$one."<br/> ";
                 $avarage_plan_of_percent = 0;
                if($avarage_plan && $avarage_plan[1]>0){
                    $avarage_plan_of_percent = (double)number_format($avarage_plan[0]/$avarage_plan[1], 2, ".", "");
                    // dd($avarage_plan_of_percent);
                }
                if(!$is_report){
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array(0));
                    $plan_accom = array_merge( $plan_accom,array($status));
                }
                if($is_report){
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array($status));
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

                // $planAccomplishments = DB::select("
                //     SELECT plan_value
                //     FROM plan_accomplishments
                //     WHERE office_id IN (?)
                //     AND kpi_id = ?
                //     AND planning_year_id = ?
                //     AND kpi_child_one_id = ?
                //     AND kpi_child_two_id = ?
                //     AND kpi_child_three_id = ?
                //     AND reporting_period_id = ?
                //     AND plan_status <= ?
                // ", [
                //     implode(',', $childAndHimOffKpi_array),
                //     $kkp,
                //     $planning_year,
                //     $one,
                //     $two,
                //     $three,
                //     $period,
                //     $office_level
                // ]);
                //$my_status = $status[0]?->plan_status;
                //dump($planning_year);
                if($is_report){
                    $planAccomplishments = PlanAccomplishment::select('accom_value')
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
                // foreach ($planAccomplishments as $key => $planAccomplishment) {
                //    $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                //    $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                // }

                $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishments->sum('plan_value');
                $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishments->sum('accom_value');

                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($status));//dump($childAndHimOffKpi_array);
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
                $planAccomplishments = DB::select("
                        SELECT plan_value  FROM plan_accomplishments
                        WHERE office_id IN (" . implode(',', $childAndHimOffKpi_array) . ")
                      AND kpi_id = ?
                      AND planning_year_id = ?
                      AND kpi_child_one_id = ?
                      AND kpi_child_two_id = ?
                      AND kpi_child_three_id = ?
                      AND reporting_period_id = ?
                      AND plan_status <= ?
                ", [
                    $kkp,  $planning_year,  $one,   $two, $three, $period,  $office_level
                ]);
                 //$my_status = $status[0]?->plan_status;
                //dump($planning_year);
                if($is_report){
                    $planAccomplishments = DB::select("
                        SELECT accom_value  FROM plan_accomplishments
                        WHERE office_id IN (" . implode(',', $childAndHimOffKpi_array) . ")
                      AND kpi_id = ?
                      AND planning_year_id = ?
                      AND kpi_child_one_id = ?
                      AND kpi_child_two_id = ?
                      AND kpi_child_three_id = ?
                      AND reporting_period_id = ?
                      AND accom_status <= ?
                ", [
                    $kkp,  $planning_year,  $one,   $two, $three, $period,  $office_level
                ]);
                $my_status = $status?->accom_status;
                }
                //dump($planAccomplishments);
                foreach ($planAccomplishments as $key => $planAccomplishment) {
                   $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                   $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                }
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($status));
                return $plan_accom;
         }
    }
    // get total for kpi
    public function ForKpi($kkp,$office,$period,$is_report,$planning_year, $one, $two, $three){
        $plan_accom = [];
         $getkpi = KeyPeformanceIndicator::find($kkp);
         $status = getStatus($kkp,$office,$period,$is_report,$planning_year ,$one, $two, $three);
         //dd($status);
         $childAndHimOffKpi = office_all_childs_ids($office);
        if($getkpi->measurement){    //dump($getkpi->measurement->slug);
            // kpi measurement is in percent
            if($getkpi->measurement?->slug == 'percent'){ //dump("kpi measurement is in percent");
                $avarage_plan = calculateAveragePlan($kkp,$office,$period,$is_report,$planning_year ,$one, $two, $three);

                $avarage_plan_of_percent = 0;
                 if($avarage_plan && $avarage_plan[1]>0){
                    $avarage_plan_of_percent = (double)number_format($avarage_plan[0]/$avarage_plan[1], 2, ".", "");
                     //dump($avarage_plan_of_percent);
                }
                $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                $plan_accom = array_merge( $plan_accom,array($status?->plan_status));
                if($is_report){
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array($status?->accom_status));
                }
                //echo $kkp."-> ".$office->id." ->".$period."-> ".$is_report."-> ".$planning_year. "<br/> ";
                 return $plan_accom;
            }
            // if kpi with  any measurement except percent.
            else{  //dump("if kpi with  any measurement except percent");
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
                $sum_of_sub_office_plan = 0;
                $sum_of_sub_office_report = 0;
                 $office_level = $office->level;
                if($office_level == 0) $office_level=1;
                $planAccomplishments = DB::select("
                        SELECT plan_value  FROM plan_accomplishments
                        WHERE office_id IN (" . implode(',', $childAndHimOffKpi_array) . ")
                      AND kpi_id = ?
                      AND planning_year_id = ?
                      AND kpi_child_one_id = ?
                      AND kpi_child_two_id = ?
                      AND kpi_child_three_id = ?
                      AND reporting_period_id = ?
                      AND plan_status <= ?
                ", [
                    $kkp,  $planning_year,  $one,   $two, $three, $period,  $office_level
                ]);
                 // $my_status = $status[0]?->plan_status;
                if($is_report){
                    $planAccomplishments = DB::select("
                    SELECT accom_value  FROM plan_accomplishments
                    WHERE office_id IN (" . implode(',', $childAndHimOffKpi_array) . ")
                    WHERE kpi_child_one_id IN (" . implode(',', $one) . ")
                    WHERE kpi_child_two_id IN (" . implode(',', $two) . ")
                    WHERE kpi_child_three_id IN (" . implode(',', $three) . ")
                  AND kpi_id = ?
                  AND planning_year_id = ?
                   AND reporting_period_id = ?
                  AND accom_status <= ?
            ", [
                $kkp,  $planning_year, $period,  $office_level
            ]);
                 $my_status = $status?->accom_status;
                }
                dump($planAccomplishments);
                foreach ($planAccomplishments as $key => $planAccomplishment) {
                   $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                   $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                }
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($status));
                 //dump($planAccomplishments);
                 return $plan_accom;
            }

        }
        // if kpi with out any measurement.
        else{ //dump("if kpi with out any measurement");
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
                $sum_of_sub_office_plan = 0;
                $sum_of_sub_office_report = 0;
                 $office_level = $office->level;
                if($office_level == 0) $office_level=1;
                $planAccomplishments = DB::select("
                        SELECT plan_value  FROM plan_accomplishments
                        WHERE office_id IN (" . implode(',', $childAndHimOffKpi_array) . ")
                      AND kpi_id = ?
                      AND planning_year_id = ?
                      AND kpi_child_one_id = ?
                      AND kpi_child_two_id = ?
                      AND kpi_child_three_id = ?
                      AND reporting_period_id = ?
                      AND plan_status <= ?
                ", [
                    $kkp,  $planning_year,  $one,   $two, $three, $period,  $office_level
                ]);
                 //$my_status = $status[0]?->plan_status;
                //dump($planAccomplishments);
                //d/ump($office_level);
                //dump($planning_year);
                if($is_report){
                    $planAccomplishments = DB::select("
                    SELECT accom_value  FROM plan_accomplishments
                    WHERE office_id IN (" . implode(',', $childAndHimOffKpi_array) . ")
                  AND kpi_id = ?
                  AND planning_year_id = ?
                  AND kpi_child_one_id = ?
                  AND kpi_child_two_id = ?
                  AND kpi_child_three_id = ?
                  AND reporting_period_id = ?
                  AND accom_status <= ?
            ", [
                $kkp,  $planning_year,  $one,   $two, $three, $period,  $office_level
            ]);
                 //$my_status = $status?->accom_status;
                }

                foreach ($planAccomplishments as $key => $planAccomplishment) {
                   $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                   $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                }
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($status));//dump($my_status);
                 return $plan_accom;
    }
    //dump("why");
    //dd("last");
    return $plan_accom;

}

// get total for kpi
    public function ForKpiTotalOnKpi($kkp,$office,$period,$is_report,$planning_year, $one, $two, $three){
        $plan_accom = [];

        $getkpi = KeyPeformanceIndicator::find($kkp);
        $status = getStatus($kkp,$office,$period,$is_report,$planning_year ,$one, $two, $three);
        //dd($status);
        $childAndHimOffKpi = office_all_childs_ids($office);
        if($getkpi->measurement){
            if($getkpi->measurement?->slug == 'percent'){       //dump("kpi measurement is in percent");
                $avarage_plan = calculateAveragePlan($kkp,$office,$period,$is_report,$planning_year ,$one, $two, $three);

                $avarage_plan_of_percent = 0;
                if($avarage_plan && $avarage_plan[1]>0){
                    $avarage_plan_of_percent = (double)number_format($avarage_plan[0]/$avarage_plan[1], 2, ".", "");
                    //dump($avarage_plan_of_percent);
                }
                $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                $plan_accom = array_merge( $plan_accom,array($status?->plan_status));
                if($is_report){
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array($avarage_plan_of_percent));
                    $plan_accom = array_merge( $plan_accom,array($status?->accom_status));
                }
                //echo $kkp."-> ".$office->id." ->".$period."-> ".$is_report."-> ".$planning_year. "<br/> ";
                return $plan_accom;
            }
            // if kpi with  any measurement except percent.
            else{  //dump("if kpi with  any measurement except percent");
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
                $sum_of_sub_office_plan = 0;
                $sum_of_sub_office_report = 0;
                $office_level = $office->level;
                if($office_level == 0) $office_level=1;
                    $query = DB::table('plan_accomplishments')
                    ->select('plan_value','accom_value')
                    ->whereIn('office_id', $childAndHimOffKpi_array)
                    ->when(!empty($one), fn($q) => $q->whereIn('kpi_child_one_id', $one))
                    ->when(!empty($two), fn($q) => $q->whereIn('kpi_child_two_id', $two))
                    ->when(!empty($three), fn($q) => $q->whereIn('kpi_child_three_id', $three))
                    ->where('kpi_id', $kkp)
                    ->where('planning_year_id', $planning_year)
                    ->where('reporting_period_id', $period)
                    ->where('accom_status', '<=', $office_level);
                $planAccomplishments = $query->get();
                $my_status = $status?->accom_status;

                foreach ($planAccomplishments as $key => $planAccomplishment) {
                 $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
                $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
                }
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
                $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
                $plan_accom = array_merge( $plan_accom,array($status));
                //dump($planAccomplishments);
                return $plan_accom;
            }

        }
        // if kpi with out any measurement.
        else{ //dump("if kpi with out any measurement");
            $childAndHimOffKpi_array =[];
            $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
            $sum_of_sub_office_plan = 0;
            $sum_of_sub_office_report = 0;
            $office_level = $office->level;
            if($office_level == 0) $office_level=1;
                $query = DB::table('plan_accomplishments')
                ->select('plan_value','accom_value')
                ->whereIn('office_id', $childAndHimOffKpi_array)
                ->when(!empty($one), fn($q) => $q->whereIn('kpi_child_one_id', $one))
                ->when(!empty($two), fn($q) => $q->whereIn('kpi_child_two_id', $two))
                ->when(!empty($three), fn($q) => $q->whereIn('kpi_child_three_id', $three))
                ->where('kpi_id', $kkp)
                ->where('planning_year_id', $planning_year)
                ->where('reporting_period_id', $period)
                ->where('accom_status', '<=', $office_level);
            $planAccomplishments = $query->get();
            $my_status = $status?->accom_status;

            foreach ($planAccomplishments as $key => $planAccomplishment) {
             $sum_of_sub_office_plan = $sum_of_sub_office_plan+$planAccomplishment->plan_value;
            $sum_of_sub_office_report = $sum_of_sub_office_report+$planAccomplishment->accom_value;
            }
            $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_plan));
            $plan_accom = array_merge( $plan_accom,array($sum_of_sub_office_report));
            $plan_accom = array_merge( $plan_accom,array($status));
            //dump($planAccomplishments);
            return $plan_accom;
    }
    //dump("why");
    //dd("last");
    return $plan_accom;

    }
public static function getOfficePlans($office)
    {
        $sql = "
            WITH RECURSIVE OfficeHierarchy AS (
                SELECT
                    o.id,
                    o.parent_office_id,
                    o.level
                FROM
                    offices o
                WHERE
                    o.parent_office_id =".$office."

                UNION ALL

                SELECT
                    o.id,
                    o.parent_office_id,
                    o.level
                FROM
                    offices o
                INNER JOIN
                    OfficeHierarchy oh ON o.parent_office_id = oh.id
            )
            SELECT
                oh.id,
                kpi.id,
                SUM(pa.plan_value) AS total_plan_value
            FROM
                OfficeHierarchy oh
            LEFT JOIN
                plan_accomplishments pa ON oh.id = pa.office_id
            LEFT JOIN
                key_peformance_indicators kpi ON pa.kpi_id = kpi.id
            GROUP BY
                oh.id, kpi.id
            ORDER BY
                oh.level, oh.id;
        ";

        // Execute the query
        $results = DB::select($sql);
        // Transform the flat data into a hierarchy
        //$hierarchicalData = self::buildHierarchy($results);
        // Return the results (modify as needed)
        return response()->json($results);
    }
    // Helper function to build hierarchy
private static function buildHierarchy($data)
{
    $data = collect($data);

    // Group data by parent office
    $grouped = $data->groupBy('parent_office_id');

    // Recursive function to build the hierarchy
    $buildTree = function ($parentId) use (&$buildTree, $grouped) {
        return $grouped->get($parentId, collect())->map(function ($item) use ($buildTree) {
            return [
                'office_id' => $item->office_id,
                'kpi_id' => $item->kpi_id,
                'total_plan_value' => $item->total_plan_value,
                'children' => $buildTree($item->office_id),
            ];
        });
    };

    // Start building the hierarchy from the root office
    return $buildTree(null); // Replace `null` with `$office` if the root has a specific ID.
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
        $plannarations = ReportNarration::select('plan_naration')
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
    public function getNarrationSelf($kkp,$year,$office){
          $office_level = $office->level;
        if($office_level == 0) $office_level=1;
        $plannarations_self = ReportNarration::select('plan_naration')
            ->where('key_peformance_indicator_id' , '=', $kkp)
            ->where('office_id' , '=', $office->id)
            ->where('planing_year_id' , '=', $year)
            ->where('approval_status' , '<=', $office_level)
        ->get();
        // dump($plannarations_self);
        // dump("x");
            return $plannarations_self;
    }
     public function getReportNarration($kkp,$year,$office,$period){
        // get all child and subchild offices for login user
        $childAndHimOffKpi_array =[];
        $office_level = $office->level;
        if($office_level == 0) $office_level=1;
        $childAndHimOffKpi = office_all_childs_ids($office);
        $childAndHimOffKpi_array = array_merge($childAndHimOffKpi, array($office->id));
        $reportNarations = ReportNarrationReport::select('*')
            ->whereIn('office_id' , $childAndHimOffKpi_array)
            ->where('key_peformance_indicator_id' , '=', $kkp)
            ->where('planing_year_id' , '=', $year)
            ->where('approval_status' , '<=', $office_level)
        ->get();
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
