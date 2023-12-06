<?php

namespace App\Http\Controllers;

use App\Models\TaskAssign;
use App\Models\TaskAccomplishment;
use App\Models\TaskMeasurementAcomplishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TaskAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search', '');
        $user_id = auth()->user()->id;
        $TaskAccomplishments = TaskAccomplishment::select('task_accomplishments.*')
                 ->join('task_assigns', 'task_assigns.id', '=', 'task_accomplishments.task_assign_id')
                // ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
                ->where('task_assigns.assigned_by_id',"=", $user_id)
                ->where('task_assigns.status',"=", 3)
                ->get();
         return view(
            'app.taskassign.index',
            compact('TaskAccomplishments', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         $data = $request->input(); 
         foreach ($data as $key => $value) { //dd($data);
            //$submit = "create";}
            $str_key= (string)$key ; 
            if($str_key!='_token'){             
                $arr_to_split_text = preg_split("/[_,\- ]+/", $str_key);
                $Taskaccomid =  $arr_to_split_text[0];
                $Taskmesurementid =  $arr_to_split_text[1];
                $TaskAccomplishment = TaskAccomplishment::find($Taskaccomid);
                $accoum_value =  $TaskAccomplishment->getAccomplishemtValue($Taskaccomid,  $Taskmesurementid);//dump($accoum_value);
                    if(!$accoum_value->isEmpty()){
                         $updated = tap(DB::table('task_measurement_acomplishments')
                                    ->where('task_accomplishment_id',"=", $Taskaccomid)
                                    ->where('task_measurement_id',"=", $Taskmesurementid))
                                    ->update(['accomplishment_value' => $value])
                                    ->first();

                }
                else{
                        $Taskmeasu_acc =new TaskMeasurementAcomplishment;
                        $Taskmeasu_acc->task_accomplishment_id = $Taskaccomid;
                        $Taskmeasu_acc->task_measurement_id = $Taskmesurementid;
                        $Taskmeasu_acc->accomplishment_value = $value;
                        $Taskmeasu_acc->save();
                }
            }
         }
         return redirect()
            ->route('performer.create')
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskAssign $taskAssign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskAssign $taskAssign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskAssign $taskAssign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskAssign $taskAssign)
    {
        //
    }
}
