<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use App\Models\PlanAccomplishment;
use Illuminate\Support\Facades\DB;
use App\Models\KeyPeformanceIndicator;
use App\Models\PlanComment;

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

        // Own plan approval, last office

        $planAccomplishmentsLastOffice = [];
        if(auth()->user()->offices[0]->parent_office_id === null){
            $planAccomplishmentsLastOffice = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
            ->where('reporting_periods.slug', "=", 1)
            ->where('office_id', auth()->user()->offices[0]->id)
            ->select('*', DB::raw('SUM(plan_value) AS sum'))
            ->groupBy('kpi_id')
            ->get();

            // dd($planAccomplishmentsLastOffice);
        }
        return view(
            'app.plan-approval.index',
            compact('planAccomplishments', 'planAccomplishment_all', 'all_office_list', 'only_child_array', 'planning_year', 'obj_office', 'search', 'planAccomplishmentsLastOffice')
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

                    // check and approve self approver office
                    if(auth()->user()->offices[0]->id === (int)$singleOfficePlan[1]){
                        $approvedSelfOffice = DB::table('plan_accomplishments')
                                ->where('planning_year_id', $singleOfficePlan[2])
                                ->where('office_id', auth()->user()->offices[0]->id)
                                ->where('kpi_id', $singleOfficePlan[0])
                                // ->where('reporting_period_id', '=', $index[1])
                                ->update([
                                    'plan_status' => 0, // decide what value it is later.
                                    'approved_by_id' => auth()->user()->id
                                ]);
                    }
                    else{
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
        }


        // dd("Approved");
        return redirect()->back()->withSuccess(__('crud.common.approved'));

    }

    public function planDisapproved(Request $request){
        // dd($request->all());

        $requestData = explode('-', $request->input('disapprove-office-info'));

        $kpi = $requestData[0];
        // $office = $requestData[1];
        // $office = Office::find($office);
        $planningYear = $requestData[1];

        $officeLogged = Office::find(auth()->user()->offices[0]->id);

        $officeList = office_all_childs_ids($officeLogged);
        // dd($officeList);

        // $officeList = array_merge($officeList, array($officeLogged->level+1));

        if(count($officeList) > 0){
            foreach($officeList as $office){
                $officeInfo = Office::find($office);

                $disapproved = DB::table('plan_accomplishments')
                ->where('kpi_id', $kpi)
                ->where('office_id', $office)
                ->where('planning_year_id', $planningYear)
                ->update([
                    'plan_status' => $officeInfo->level,
                ]);

                // try to insert the comment for each office so that they will be able to see the comment when to modify planning
                // $isCommentExists = DB::table('plan_comments')->where('office_id', $office)->get();
                // if($isCommentExists->count() > 0){
                //     $disapproved = DB::table('plan_comments')
                //     ->where('kpi_id', $kpi)
                //     ->where('office_id', $office)
                //     ->where('planning_year_id', $planningYear)
                //     ->update([
                //         'plan_status' => $officeInfo->level,
                //     ]);
                // }
            }
        }

        return redirect()->back()->withSuccess(__('crud.common.disapproved'));
    }

    public function planComment(Request $request){
        // dd($request->all());

        $loggedInUserOfficeLevel = Office::where('id', auth()->user()->offices[0]->id)->first();
        $requestArray = explode('-', $request->input('commented-office-info'));

        $planComment = $request->input('plan_comment');
        $kpi = (int)$requestArray[0];
        $office = (int)$requestArray[1];
        $officeId = Office::find($office);
        $planningYear = (int)$requestArray[2];
        $reportingPeriod = 3; // static for now, think on it later

        $isCurrentOfficePlanned = getOfficePlanRecord($kpi, $officeId, $planningYear);
        $isOfficeLast= office_all_childs_ids($officeId);
        // dd(count($isOfficeLast));
        // dd($isCurrentOfficePlanned);

        // dd($isCurrentOfficePlanned->count() > 0 && count($isOfficeLast) == 0);
        // make current office's plan_status downgrade so that KPI plan form will be editable
        if($isCurrentOfficePlanned->count() > 0 && count($isOfficeLast) == 0){
            $changePlanStatus = DB::table('plan_accomplishments')
                ->where('planning_year_id', $planningYear)
                ->where('office_id', $office)
                ->where('kpi_id', $kpi)
                // ->where('reporting_period_id', $reportingPeriod)
                ->update([
                    'plan_status' => $loggedInUserOfficeLevel->level+1, // or the current office's level
                    // 'approved_by_id' => auth()->user()->id
                ]);
        }

        $isCommentExists = DB::table('plan_comments')->where('office_id', $office)->where('kpi_id', $kpi)->where('planning_year_id', $planningYear)->get();
        // dd($isCommentExists);

        if($isCommentExists->count() > 0){
            $planCommented = DB::table('plan_comments')
            ->update([
                'plan_comment' => $planComment,
                'status' => 1
            ]);
        }

        $planCommented = PlanComment::create([
            'plan_comment' => $planComment,
            'kpi_id' => $kpi,
            'reporting_period_id' => $reportingPeriod,
            'planning_year_id' => $planningYear,
            'office_id' => $office,
            'commented_by' => $loggedInUserOfficeLevel->id
        ]);

        return redirect()->back()->withSuccess(__('crud.common.commented'));


    }

    public function replyComment(Request $request){
        // dd($request->all());

        $requestData = explode('-', $request->input('view-commented-office-info'));
        // dd($requestData);

        $planCommentId = $requestData[0];
        $kpi = $requestData[1];
        $planningYear = $requestData[2];

        $replyComment = DB::table('plan_comments')
            ->where('id', $planCommentId)
            ->where('kpi_id', $kpi)
            ->where('planning_year_id', $planningYear)
            ->update([
                'reply_comment' => $request->input('reply_comment'),
                'status' => 0,
            ]);

        return redirect()->back()->withSuccess(__('crud.common.replied'));
    }

    // AJAX responses
    public function getOfficeKpiInfo($data){
        $requestArray = explode('-', $data);
        // error_log($requestArray[1]);

        $returnData = $data;

        $kpi = (int)$requestArray[0];
        $office = (int)$requestArray[1];
        // $planningYear = (int)$requestArray[2];

        $responseData = [];

        $officeName = Office::find($office);
        $officeName = $officeName->officeTranslations[0]->name;
        $kpiName = KeyPeformanceIndicator::find($kpi);
        $kpiName = $kpiName->keyPeformanceIndicatorTs[0]->name;

        $responseData['info'] = $returnData;
        $responseData['officeName'] = $officeName;
        $responseData['kpi'] = $kpiName;

        return response()->json($responseData);

    }

    public function getCommentInfo($data){
        $requestArray = explode('-', $data);
        error_log($requestArray[0]);

        $returnData = $data;

        $kpi = (int)$requestArray[1];
        // $office = (int)$requestArray[2];
        $planningYear = (int)$requestArray[2];

        $officeName = getPlanCommentorInfo(auth()->user()->offices[0]->id, $kpi, $planningYear);

        $commentText = hasOfficeActiveComment(auth()->user()->offices[0]->id,$kpi, $planningYear)->plan_comment;

        $responseData = [];
        $responseData['info'] = $returnData;
        $responseData['officeName'] = $officeName;
        $responseData['commentText'] = $commentText;

        return response()->json($responseData);
    }

    public function disapproveInfo($data){
        $requestArray = explode('-', $data);
        // error_log($requestArray[1]);

        $returnData = $data;

        $kpi = (int)$requestArray[0];
        $office = (int)$requestArray[1];
        // $planningYear = (int)$requestArray[2];

        $responseData = [];

        $officeName = Office::find($office);

        $responseData['info'] = $returnData;

        return response()->json($responseData);

    }

    public function replyInfo($data){
        $requestArray = explode('-', $data);
        // error_log($requestArray[1]);

        $returnData = $data;

        $office = $requestArray[0];
        $office = Office::find($office);
        $planCommentId = (int)$requestArray[1];
        $kpi = (int)$requestArray[2];
        $planningYear = (int)$requestArray[3];

        $replyText = commentorTextStatus($office, auth()->user()->offices[0]->id, $kpi, $planningYear)->reply_comment;

        $responseData = [];

        $responseData['info'] = $returnData;
        $responseData['replyText'] = $replyText;

        return response()->json($responseData);

    }
}