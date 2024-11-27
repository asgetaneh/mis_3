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
        $planning_year = PlaningYear::where('is_active',true)->first();

        $all_office_list = array_merge( $all_child_and_subchild,array($office));
        $only_child = $obj_office->offices;
         //dd($only_child);
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

        if (isset($request->search)) {
            $kpi_array = [];
            if ($request->input('kpi')) {
                $kpi = $request->input('kpi'); //dd($kpi);
                $kpi_array = array_merge($kpi_array, array($kpi));
            } else {
                $all_kpis = getAllKpi();
                foreach ($all_kpis as $key => $value) {
                    $kpi_array = array_merge($kpi_array, array($value->id));
                }
            }
            if ($request->input('office')) {
                $office_id = $request->input('office');
                $office = Office::find($office_id);
                $all_office_list = $this->allChildAndChildChild($office);
                $only_child_array = array_merge($all_office_list, array($office_id));
                $off_level = $office->level;
                $imagen_off = $office;
            }
            $planAccomplishments = PlanAccomplishment::
            join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                ->whereIn('office_id', $only_child_array)
                ->whereIn('kpi_id', $kpi_array)
                ->where('planning_year_id', '=', $planningYear)
                ->select('*', DB::raw('SUM(plan_value) AS sum'))
                ->groupBy('kpi_id')
                ->get();
        } else {

            $kpii = KeyPeformanceIndicator::select('key_peformance_indicators.*')
                ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
                ->whereIn('office_id', $all_office_list)
                ->get();

            $planAccomplishments = PlanAccomplishment::join('reporting_periods', 
            'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
               // ->join('key_peformance_indicators', 
               // 'key_peformance_indicators.id', '=', 'plan_accomplishments.kpi_id')
               // ->join('objectives', 
                //'objectives.id', '=', 'key_peformance_indicators.objective_id')
                ->whereIn('office_id', $all_office_list)
                ->select('*', DB::raw('SUM(plan_value) AS sum'))
                ->where('reporting_periods.slug', "=", 1)
                ->where('planning_year_id', '=', $planning_year->id)
                //->groupBy('objectives.id')
                ->groupBy('kpi_id')
                ->get(); //dd($planAccomplishments);

            // $is_admin = auth()->user()->is_admin;
            // if($is_admin){
            //     $imagen_off = Office::find(1); //immaginery office of which contain all office kpi plan
            //     $off_level = 1;
            //     $all_offices = getAllOffices();
            //     $only_child_array = [];
            // foreach ($all_offices as $key => $value) {
            //     $only_child_array = array_merge($only_child_array,array($value->id));
            // }
            // $planAccomplishments = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
            //     ->join('offices', 'offices.id', '=', 'plan_accomplishments.office_id')
            //      ->join('key_peformance_indicators', 'plan_accomplishments.kpi_id', '=', 'key_peformance_indicators.id')
            //     ->join('objectives', 'key_peformance_indicators.objective_id', '=', 'objectives.id')
            //     ->select('*', DB::raw('SUM(plan_value) AS sum'))
            //     // -> where('reporting_periods.slug',"=", 1)
            //     // -> where('plan_status',"=", 2)
            //     ->groupBy('reporting_period_id')
            //     ->groupBy('objective_id')->groupBy('kpi_id')
            //     ->orderBy('reporting_periods.slug')
            //     ->get();
            //     //sdd($planAccomplishments);
            // }
        }

        $planAccomplishment_all = DB::table('plan_accomplishments')
            ->whereIn('office_id', $all_office_list)->groupBy('kpi_id')
            ->where('planning_year_id', '=', $planning_year->id)
            ->sum('plan_accomplishments.plan_value');
        //  dd($planAccomplishments);

        // $allKpisListChildren = getKpiImmediateChilds($all_office_list);
        // $allKpisListChildren = array_unique($allKpisListChildren);
        // // dd($allKpisListChildren);

        $allKpisListChildren = [];
        if ($planAccomplishments->count() > 0) {
            foreach ($planAccomplishments as $kpi) {
                array_push($allKpisListChildren, $kpi->kpi_id);
            }
        }
        // dd($all_office_list);

        // redirect if office has no child and it has parent office
        if ($obj_office->offices->isEmpty() && $obj_office->level > 1) {  // office with no child
            return redirect()
                ->route('plan-accomplishment', $obj_office);
        }
        $planning_year = PlaningYear::where('is_active', true)->first();

        // if last office worked on the kpi, add its sum to show total
        if ($planAccomplishments->count() > 0) {
            foreach ($planAccomplishments as $plan) {
                if (auth()->user()->offices[0]->level === 1) {
                    // if he belongs to the kpi
                    if (isLastOfficeBelongToKpi(auth()->user()->offices[0], $plan->kpi_id)->count() > 0) {
                        // if he has record for the current kpi
                        if (getOfficePlanRecord($plan->kpi_id, auth()->user()->offices[0], $planning_year->id ?? NULL)->count() > 0) {
                            $planAccomplishmentsLastOffice = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                                ->where('reporting_periods.slug', "=", 1)
                                ->where('office_id', auth()->user()->offices[0]->id)
                                ->where('planning_year_id', '=', $planning_year->id)
                                ->select('*', DB::raw('SUM(plan_value) AS sum'))
                                ->where('kpi_id', $plan->kpi_id)
                                ->get();//dd($planAccomplishmentsLastOffice);

                            // last office's sum added up so that to display the total sum in the kpi header view
                            $plan->sum = $plan->sum + $planAccomplishmentsLastOffice[0]->sum;
                        }
                    }
                }
            }
        }

        // Own plan approval, last office

        $planAccomplishmentsLastOffice = [];
        if (auth()->user()->offices[0]->level === 1) {
            $planAccomplishmentsLastOffice = PlanAccomplishment::join('reporting_periods', 'reporting_periods.id', '=', 'plan_accomplishments.reporting_period_id')
                ->where('reporting_periods.slug', "=", 1)
                ->where('office_id', auth()->user()->offices[0]->id)
                ->where('planning_year_id', '=', $planning_year->id)
                ->select('*', DB::raw('SUM(plan_value) AS sum'))
                ->groupBy('kpi_id')
                ->get();

            // dd($planAccomplishments);
        }
        return view(
            'app.plan-approval.index',
            compact('planAccomplishments', 'planAccomplishment_all', 'all_office_list', 'only_child_array', 'planning_year', 'obj_office', 'search', 'planAccomplishmentsLastOffice', 'allKpisListChildren')
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
                    if (auth()->user()->offices[0]->id === (int)$singleOfficePlan[1]) {
                        $approvedSelfOffice = DB::table('plan_accomplishments')
                            ->where('planning_year_id', $singleOfficePlan[2])
                            ->where('office_id', auth()->user()->offices[0]->id)
                            ->where('kpi_id', $singleOfficePlan[0])
                            // ->where('reporting_period_id', '=', $index[1])
                            ->update([
                                'plan_status' => 1, // decide what value it is later.
                                'approved_by_id' => auth()->user()->id
                            ]);
                    } else {
                        $office = (int)$singleOfficePlan[1];
                        $findOffice = Office::find($office);
                        $allChildren = office_all_childs_ids($findOffice);
                        $allChildrenApproved = getOfficeChildrenApprovedList((int)$singleOfficePlan[0], $findOffice, (int)$singleOfficePlan[2], 1);
                        $isCurrentChildAlreadyApproved = isCurrentOfficeApproved((int)$singleOfficePlan[0], $findOffice, (int)$singleOfficePlan[2]);
                        // dd($allChildrenApproved);

                        // Prevent immediate child from changing its status if it was first approved
                        if (count($allChildrenApproved) > 0) {
                            if (!(empty($isCurrentChildAlreadyApproved)) && $isCurrentChildAlreadyApproved->plan_status < auth()->user()->offices[0]->level) {
                                $mergedOffices = $allChildrenApproved;
                            } else {
                                $mergedOffices = array_merge($allChildrenApproved, array($office));
                            }
                        } else {
                            $mergedOffices = array($office);
                        }

                        //dd($loggedInUserOfficeLevel->level);

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
                        // approve major activities also
                        $approvedAllOfficesMactivities = tap(
                            DB::table('report_narrations')
                                ->where('planing_year_id', $singleOfficePlan[2])
                                ->whereIn('office_id', $mergedOffices)
                                ->where('key_peformance_indicator_id', $singleOfficePlan[0])
                            // ->where('reporting_period_id', '=', $index[1])
                        )
                            ->update([
                                'approval_status' => $loggedInUserOfficeLevel->level
                            ]);

                        $approvedBaseline = tap(
                            DB::table('baselines')
                                ->where('planning_year_id', $singleOfficePlan[2])
                                ->whereIn('office_id', $mergedOffices)
                                ->where('kpi_id', $singleOfficePlan[0])
                        )
                            ->update([
                                'plan_status' => $loggedInUserOfficeLevel->level
                            ]);

                        $forPlanComment = array_merge($allChildrenApproved, array($office));

                        // Change status of any plan comment for the office and its children approved
                        $changePlanCommentStatus = DB::table('plan_comments')
                            ->whereIn('office_id', $forPlanComment)
                            ->where('kpi_id', $singleOfficePlan[0])
                            ->where('planning_year_id', $singleOfficePlan[2])
                            ->update([
                                'status' => 0,
                            ]);
                    }
                }
            }
        }


        // dd("Approved");
        return redirect()->back()->withSuccess(__('crud.common.approved'));
    }

    public function planDisapproved(Request $request)
    {
        // dd($request->all());

        $requestData = explode('-', $request->input('disapprove-office-info'));
        $selectedOfficeList = $request->input('disapproved-office-list');
        $writtenComment = $request->input('written_comment');
        // dd($selectedOfficeList);

        $loggedInOffice = (int)$requestData[0];
        $kpi = (int)$requestData[1];
        $planningYear = (int)$requestData[2];
        $reportingPeriod = 3;

        $officeLogged = Office::find($loggedInOffice);

        // dd($officeLogged);
        foreach ($selectedOfficeList as $key => $office) {

            $officeLevel = Office::find((int)$office);

            $childrenoffices = office_all_childs_ids($officeLevel);
            // dd($childrenoffices);
            $allChildrenApproved = PlanAccomplishment::select()
                ->where('kpi_id', $kpi)
                ->whereIn('office_id', $childrenoffices)
                ->where('planning_year_id', $planning_year)
                ->where('plan_status', '=', $officeLogged->level)->distinct('office_id')
                ->get();
            // dd($allChildrenApproved);

            $officeIdList = [];
            if ($allChildrenApproved->count() > 0) {
                foreach ($allChildrenApproved as $officer) {
                    array_push($officeIdList, $officer->office_id);
                }
            }

            $officeIdList = array_unique($officeIdList);
            $officeIdList = array_merge($officeIdList, array((int)$office));

            $officeDisapproved = DB::table('plan_accomplishments')
                ->where('planning_year_id', $planning_year)
                ->whereIn('office_id', $officeIdList)
                ->where('kpi_id', $kpi)
                // ->where('reporting_period_id', '=', $index[1])
                ->update([
                    'plan_status' => $officeLevel->level,
                    // 'approved_by_id' => $loggedInOffice
                ]);

            $officeCommented = PlanComment::updateOrCreate([
                'plan_comment' => $writtenComment,
                'kpi_id' => $kpi,
                'reporting_period_id' => $reportingPeriod,
                'planning_year_id' => $planning_year,
                'office_id' => (int)$office,
                'commented_by' => $loggedInOffice
            ]);
        }

        // dd('disapproved and commented');

        // $officeList = array_merge($officeList, array($officeLogged->level+1));

        // if(count($officeList) > 0){
        //     foreach($officeList as $office){
        //         $officeInfo = Office::find($office);

        //         $disapproved = DB::table('plan_accomplishments')
        //         ->where('kpi_id', $kpi)
        //         ->where('office_id', $office)
        //         ->where('planning_year_id', $planningYear)
        //         ->update([
        //             'plan_status' => $officeInfo->level,
        //         ]);

        //         // try to insert the comment for each office so that they will be able to see the comment when to modify planning
        //         // $isCommentExists = DB::table('plan_comments')->where('office_id', $office)->get();
        //         // if($isCommentExists->count() > 0){
        //         //     $disapproved = DB::table('plan_comments')
        //         //     ->where('kpi_id', $kpi)
        //         //     ->where('office_id', $office)
        //         //     ->where('planning_year_id', $planningYear)
        //         //     ->update([
        //         //         'plan_status' => $officeInfo->level,
        //         //     ]);
        //         // }
        //     }
        // }

        return redirect()->back()->withSuccess(__('crud.common.disapproved'));
    }

    public function planComment(Request $request)
    {
        // dd($request->all());

        $loggedInUserOfficeLevel = Office::where('id', auth()->user()->offices[0]->id)->first();
        $requestArray = explode('-', $request->input('commented-office-info'));

        $planComment = $request->input('plan_comment');
        $kpi = (int)$requestArray[0];
        $office = (int)$requestArray[1];
        $officeId = Office::find($office);
        $planningYear = (int)$requestArray[2];

        $isCurrentOfficePlanned = getOfficePlanRecord($kpi, $officeId, $planningYear);
        $isOfficeLast = office_all_childs_ids($officeId);
        // dd(count($isOfficeLast));
        // dd($isCurrentOfficePlanned);

        // dd($isCurrentOfficePlanned->count() > 0 && count($isOfficeLast) == 0);
        // make current office's plan_status downgrade so that KPI plan form will be editable
        if ($isCurrentOfficePlanned->count() > 0 && count($isOfficeLast) == 0) {
            $changePlanStatus = DB::table('plan_accomplishments')
                ->where('planning_year_id', $planningYear)
                ->where('office_id', $office)
                ->where('kpi_id', $kpi)
                // ->where('reporting_period_id', $reportingPeriod)
                ->update([
                    'plan_status' => $loggedInUserOfficeLevel->level + 1, // or the current office's level
                    // 'approved_by_id' => auth()->user()->id
                ]);
        }

        // $isCommentExists = DB::table('plan_comments')->where('office_id', $office)->where('kpi_id', $kpi)->where('planning_year_id', $planningYear)->get();
        // // dd($isCommentExists);

        // if($isCommentExists->count() > 0){
        //     $planCommented = DB::table('plan_comments')
        //     ->update([
        //         'plan_comment' => $planComment,
        //         'status' => 1
        //     ]);
        // }

        $planCommented = PlanComment::create([
            'plan_comment' => $planComment,
            'kpi_id' => $kpi,
             'planning_year_id' => $planningYear,
            'office_id' => $office,
            'commented_by' => $loggedInUserOfficeLevel->id
        ]);

        return redirect()->back()->withSuccess(__('crud.common.commented'));
    }

    public function replyComment(Request $request)
    {
        // dd($request->all());

        $requestData = explode('-', $request->input('view-commented-office-info'));
        // dd($requestData);

        // $planCommentId = $requestData[0];
        $commentedById = $requestData[0];
        $kpi = $requestData[1];
        $planningYear = $requestData[2];

        $replyComment = DB::table('plan_comments')
            // ->where('id', $planCommentId)
            ->where('commented_by', $commentedById)
            ->where('kpi_id', $kpi)
            ->where('planning_year_id', $planningYear)
            ->update([
                'reply_comment' => $request->input('reply_comment'),
                'replied_active' =>  1,
                'status' => 0,
            ]);

        return redirect()->back()->withSuccess(__('crud.common.replied'));
    }

    // AJAX responses
    public function getOfficeKpiInfo($data)
    {
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

    public function getCommentInfo($data)
    {
        $requestArray = explode('-', $data);
        // error_log($requestArray[0]);

        $returnData = $data;

        $kpi = (int)$requestArray[1];
        // $office = (int)$requestArray[2];
        $planningYear = (int)$requestArray[2];

        $officeName = getPlanCommentorInfo(auth()->user()->offices[0]->id, $kpi, $planningYear)->name ?? '-';

        $commentText = hasOfficeActiveComment(auth()->user()->offices[0]->id, $kpi, $planningYear);
        $commentTextString = '';

        if ($commentText->count() > 0) {
            foreach ($commentText as $comment) {
                $commentTextString = $commentTextString . ' ' . $comment->plan_comment;
            }
        }
        error_log($commentTextString);

        $commentTextString = '<div id="view-comment-paragraph">' . $commentTextString . '</div>';
        error_log($commentTextString);

        $responseData = [];
        $responseData['info'] = $returnData;
        $responseData['officeName'] = $officeName;
        $responseData['commentText'] = $commentTextString;

        return response()->json($responseData);
    }

    // Disapproval will hold offices and comment box
    public function disapproveInfo($data)
    {
        $requestArray = explode('-', $data);
        // error_log($requestArray[0]);

        $returnData = $data;

        $loggedInOffice = $requestArray[0];
        $kpi = (int)$requestArray[1];
        $planningYear = (int)$requestArray[2];

        $responseData = [];

        $office = Office::find($loggedInOffice);
        $onlyChild = $office->offices;

        $onlyChildArray = [];
        foreach ($onlyChild as $key => $office) {
            $onlyChildArray[$key] = $office->id;
        }

        $onlyApprovedOffices = PlanAccomplishment::select()
            ->whereIn('office_id', $onlyChildArray)
            ->where('kpi_id', $kpi)
            ->where('planning_year_id', $planningYear)
            ->where(function ($q) {
                $q->where('plan_status', '<', auth()->user()->offices[0]->level)->orWhere('plan_status', '=', auth()->user()->offices[0]->level);
            })
            ->get();

        $onlyChildArray = [];
        if($onlyApprovedOffices->count() > 0){
            foreach ($onlyApprovedOffices as $key => $office) {
                $onlyChildArray[$office->office_id] = $office->office->officeTranslations[0]->name;
            }
        }

        // error_log(($onlyChildArray[1]));

        $responseData['info'] = $returnData;
        $responseData['offices'] = $onlyChildArray;

        return response()->json($responseData);
    }

    public function replyInfo($data)
    {
        $requestArray = explode('-', $data);
        // error_log($requestArray[1]);

        $returnData = $data;

        $office = $requestArray[0];
        $office = Office::find($office);
        $planCommentId = (int)$requestArray[1];
        $kpi = (int)$requestArray[2];
        $planningYear = (int)$requestArray[3];

        $replyText = commentorTextStatus($office, auth()->user()->offices[0]->id, $kpi, $planningYear, 3)->reply_comment;

        $responseData = [];

        $responseData['info'] = $returnData;
        $responseData['replyText'] = $replyText;

        return response()->json($responseData);
    }




    public function allChildAndChildChild($office)
    {
        $all_ids = [];
        if ($office->offices->count() > 0) {
            foreach ($office->offices as $child) {
                $all_ids[] = $child->id;
                $all_ids = array_merge($all_ids, is_array(office_all_childs_ids($child)) ? office_all_childs_ids($child) : []);
            }
        }
        return $all_ids;
    }
}
