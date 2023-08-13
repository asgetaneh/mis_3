<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use App\Models\PlanAccomplishment;
use Illuminate\Support\Facades\DB;
use App\Models\KeyPeformanceIndicator;

class PlanApprovalController extends Controller
{
    public function viewPlanAccomplishment(Request $request)
    {
        $search = $request->get('search', '');
        $office = auth()->user()->offices[0]->id;
        $obj_office = Office::find($office);
        $all_child_and_subchild = office_all_childs_ids($obj_office);
        $all_office_list = $all_child_and_subchild;
        //$all_office_list = array_merge( $all_child_and_subchild,array($office));
        $only_child = $obj_office->offices;
        // dd($only_child);
        $only_child_array = [];
        foreach ($only_child as $key => $value) {
            $only_child_array[$key] = $value->id;
        }
        // $only_child_array = array_merge( $only_child_array,array($office));
        // dd($only_child_array) ;
        //$only_child_array = $all_child_and_subchild;
        if ($obj_office->offices->isEmpty()) {
            $all_office_list = array($office);
        }

        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        //re-enable ONLY_FULL_GROUP_BY
        //DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

        $planAccomplishment_all = DB::table('plan_accomplishments')
            ->whereIn('office_id', $all_office_list)->groupBy('kpi_id')
            ->sum('plan_accomplishments.plan_value');

        $kpii = KeyPeformanceIndicator::select('key_peformance_indicators.*')
            ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
            ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
            ->whereIn('office_id', $all_office_list)
            ->get();
        foreach ($kpii as $key => $value) {
            //dd($value->planacc);
        }
        // dd($all_office_list);
        $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')->whereIn('office_id', $all_office_list)->select('*', DB::raw('SUM(plan_value) AS sum'))->where('reporting_periods.slug', "=", 1)
            ->groupBy('kpi_id')
            ->get();
        //  dd($planAccomplishments);
        if ($obj_office->offices->isEmpty()) {  // office with no child
            return redirect()
                ->route('plan-accomplishment', $obj_office);
        }
        $planning_year = PlaningYear::where('is_active', true)->get();
        return view(
            'app.plan-approval.index',
            compact('planAccomplishments', 'planAccomplishment_all', 'all_office_list', 'only_child_array', 'planning_year', 'obj_office', 'search')
        );
    }

    public function planApproved(Request $request)
    {
        // dd($request->all());
        // getofficel level i.e first approved,second,...
        $approvedOfficelist = $request->except('checkAll');
        $singleOfficePlan = '';
        $loggedInUserOfficeLevel = Office::where('id', auth()->user()->offices[0]->id)->first();
        $mergedOffices = [];

        foreach ($approvedOfficelist as $key => $value) {

            $str_key = (string)$key;
            if ($str_key != '_token') {

                // loop through the office plans
                foreach ($value as $key2 => $value2) {
                    $arr_to_split_text = preg_split("/[_,\- ]+/", $value2);
                    $singleOfficePlan = preg_split("/[_,\- ]+/", $value2);
                    // foreach ($arr_to_split_text as $splitkey => $splitvalue) {
                    //     $singleOfficePlan[$splitkey] = $splitvalue;
                    // }


                    $office = (int)$singleOfficePlan[1];
                    $findOffice = Office::find($office);
                    $allChildren = office_all_childs_ids($findOffice);
                    $allChildrenApproved = getOfficeChildrenApprovedList((int)$singleOfficePlan[0], $findOffice, (int)$singleOfficePlan[2], 1);
                    $isCurrentChildAlreadyApproved = isCurrentOfficeApproved((int)$singleOfficePlan[0], $findOffice, (int)$singleOfficePlan[2]);
                    // dd($allChildrenApproved);

                    // Prevent immediate child from changing its status if it was first approved
                    if (count($allChildrenApproved) > 0) {
                        if(!(empty($isCurrentChildAlreadyApproved)) && $isCurrentChildAlreadyApproved->plan_status < auth()->user()->offices[0]->level){
                            $mergedOffices = $allChildrenApproved;
                        }
                        else{
                            $mergedOffices = array_merge($allChildrenApproved, array($office));
                        }

                    } else {
                        $mergedOffices = array($office);
                    }

                    // dd($mergedOffices);

                    $approvedAllOffices = tap(
                        DB::table('plan_accomplishments')
                            ->where('planning_year_id', $singleOfficePlan[2])
                            ->whereIn('office_id', $mergedOffices)
                            ->where('kpi_id', $singleOfficePlan[0])
                        // ->where('reporting_period_id', '=', $index[1])
                    )
                        ->update([
                            'plan_status' => $loggedInUserOfficeLevel->level,
                            'approved_by_id' => auth()->user()->id
                        ]);
                }
            }
        }


        // dd("Approved");
        return redirect()->back()->withSuccess(__('crud.common.approved'));

    }

    public function planComment(Request $request){
        dd($request->all());
    }
}
