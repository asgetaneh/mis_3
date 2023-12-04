<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskMeasurement;
use App\Models\User;
use App\Models\Office;
use App\Models\Language;
use App\Models\Performer;
use App\Models\TaskAssign;
use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Models\OfficeTranslation;
use Illuminate\Support\Carbon;
use App\Models\KeyPeformanceIndicator;
use Andegna\DateTimeFactory;
use App\Models\TaskAccomplishment;
use Illuminate\Support\Facades\DB;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search', '');
        $office = auth()->user()->offices[0]->id;
        $kpis = KeyPeformanceIndicator::select('key_peformance_indicators.*')
                ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
                ->where('office_id',"=", $office)
                ->get();
        $tasks = Task::search($search)
             ->oldest()
            ->paginate(10)
            ->withQueryString();
        $task_measurements = TaskMeasurement::all();
        $reporting_periods = ReportingPeriod::search($search)
             ->oldest()
            ->paginate(10)
            ->withQueryString();
         return view(
            'app.task.index',
            compact('tasks','kpis','reporting_periods', 'task_measurements', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $search = $request->get('search', '');
        $office = auth()->user()->offices[0]->id;
        $kpis = KeyPeformanceIndicator::select('key_peformance_indicators.*')
                ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
                ->where('office_id',"=", $office)
                ->get();
        $tasks = Task::search($search)
             ->oldest()
            ->paginate(10)
            ->withQueryString();
        $reporting_periods = ReportingPeriod::search($search)
             ->oldest()
            ->paginate(10)
            ->withQueryString();
        $task_measurements = TaskMeasurement::all();
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
          return view(
            'app.task.create',
            compact('tasks','kpis','reporting_periods', 'languages', 'task_measurements',  'search')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->input();//dd($data);
        $office = auth()->user()->offices[0]->id;
        $language = Language::all();
         //$lastGoal = Goal::select('id')->orderBy('id','desc')->first();
        try {
            $task = new Task;
            $task->weight= $data['weight'] ?? null;
            $task->period_id= $data['reporting_period'];
            $task->office_id = $office;
            $task->kpi_id = $data['kpi'];
            $task->created_by_id= auth()->user()->id;
             foreach ($language as $key => $value) {
                $task ->name = $data['name_'.$value->locale];
                $task ->description = $data['description_'.$value->locale];
               $task->save();
             foreach ($data['measurements'] as $key => $value) {
               $task_measurement = DB::insert('insert into task_task_measures (task_id, task_measurement_id) values (?, ?)', [$task->id, $value]);
            }
         }


         return redirect()
            ->route('tasks.index', $task)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('task/create')->withErrors(['errors' => $e]);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        // $this->authorize('view', $task);

        return view('app.task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        $search = $request->get('search', '');
        // dd($task);

        $office = auth()->user()->offices[0]->id;
        $kpis = KeyPeformanceIndicator::select('key_peformance_indicators.*')
                ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
                ->where('office_id',"=", $office)
                ->get();
        $tasks = Task::search($search)
             ->oldest()
            ->paginate(10)
            ->withQueryString();
        $reporting_periods = ReportingPeriod::search($search)
             ->oldest()
            ->paginate(10)
            ->withQueryString();
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
        $task_measurements = TaskMeasurement::all();
        $selected_measure = $task->taskMeasurement;
         return view(
            'app.task.edit',
            compact('tasks','task', 'kpis','reporting_periods', 'search', 'languages','task_measurements', 'selected_measure')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        // dd($request->all());

        $data = $request->input();//dd($data);
        $office = auth()->user()->offices[0]->id;
        $language = Language::all();

        $task->update([
            'created_by_id' => auth()->user()->id,
            'period_id' => $data['reporting_period'],
            'kpi_id' => $data['kpi'],
            'office_id' => $office,
            'weight' => $data['weight'] ?? null
        ]);

        foreach ($language as $key => $value) {
            $task->update([
                'name' => $data['name_'.$value->locale],
                'description' => $data['description_'.$value->locale],
            ]);
        }//dd($data);
        // get task_mesurement form task
        $task_measurements = $task->taskMeasurement;
        $task->taskMeasurement()->detach($task_measurements);
        $task->taskMeasurement()->attach($data['measurements']);

        // foreach ($task_measurements as $key => $obj) {
        //     DB::table('task_task_measures')->where('task_measurement_id', $obj->id)->delete();
        //  }


        //  foreach ($data['measurements'] as $key => $value) {
        //        $task_measurement = DB::insert('insert into task_task_measures (task_id, task_measurement_id) values (?, ?)', [$task->id, $value]);
        //     }

        return redirect()
            ->route('tasks.index', $task)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // $this->authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->withSuccess(__('crud.common.removed'));
    }
    public function performer(Request $request)
    {
        //
        $search = $request->get('search', '');
        $offices = auth()->user()->offices;
        $operformerAdds = Performer::select('performers.*')
                ->join('users', 'users.id', '=', 'performers.user_id')
                 ->where('performers.office_id',"=", $offices[0]->id)
                ->get();//dd($operformerAdds);

        $addedUserList = [];
        foreach ($operformerAdds as $operformerAdd){
            array_push($addedUserList, $operformerAdd->user_id);
        }
        $users = User::select('users.*')
                -> whereNotIn('id', $addedUserList)
                ->get();

        return view(
            'app.performer.index',
            compact('users', 'offices', 'operformerAdds', 'search')
        );
    }
     public function addPerformer(Request $request)
    {
        //
         $data = $request->input();
        $office = $data['office'];
        $users = $data['users'];

        foreach($users as $user){
            $performer = DB::insert('insert into performers (user_id, office_id) values (?, ?)', [$user, $office]);
        }

        $search = $request->get('search', '');
        $offices = auth()->user()->offices;
         $operformerAdds = Performer::select('performers.*')
                ->join('users', 'users.id', '=', 'performers.user_id')
                 ->where('performers.office_id',"=", $offices[0]->id)
                ->get();
        $addedUserList = [];
        foreach ($operformerAdds as $operformerAdd){
            array_push($addedUserList, $operformerAdd->user_id);
        }
        $users = User::select('users.*')
                -> whereNotIn('id', $addedUserList)
                ->get();

        return redirect()->back()->with(
            [
                'users'=> $users,
                'offices'=> $offices,
                'operformerAdds'=> $operformerAdds,
             ]

        );
    }
    public function performerRemoveFromOffice($performer, Request $request) {
        $performer_ob = Performer::find($performer);//dd($performer_ob);
        $performer_ob->delete();
        $offices = auth()->user()->offices;
        $operformerAdds = Performer::select('performers.*')
                ->join('users', 'users.id', '=', 'performers.user_id')
                 ->where('performers.office_id',"=", $offices[0]->id)
                ->get();
        $addedUserList = [];
        foreach ($operformerAdds as $operformerAdd){
            array_push($addedUserList, $operformerAdd->user_id);
        }
        $users = User::select('users.*')
                -> whereNotIn('id', $addedUserList)
                ->get();

        return redirect()->back()->with(
            [
                'users'=> $users,
                'offices'=> $offices,
                'operformerAdds'=> $operformerAdds,
             ]

        );
    }


    public function taskAssignIndex(Request $request, $id){

        // $this->authorize('task-assign-index', $task);

        $task = Task::find($id);
        // dd($task);

        $user_office = auth()->user()->offices[0]->id;

        $assignedPerformers = TaskAssign::where('task_id', $task->id);
        // dd($assignedPerformers->pluck('assigned_to_id'));

        $performersNotAssigned = Performer::whereNotIn('user_id', $assignedPerformers->pluck('assigned_to_id'))->get();
        // dd($performersNotAssigned);

        $languages = Language::all();
        $assignedPerformers = TaskAssign::where('task_id', $task->id)->get();

        return view(
            'app.task.task-assign',
            compact(
                'task',
                'performersNotAssigned',
                'assignedPerformers',
                'languages'
            )
        );
    }

    public function taskAssignStore(Request $request)
    {

        // $this->authorize('task-assign-store', $task);

        // dd($request->all());
        $data = $request->input();

        $taskId = $data['taskId'];
        $expectedValue = $data['expectedValue'];
        $timeGap = $data['timeGap'];
        $performers = $data['performers'];

        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        $startDate = explode('/', $data['start_date']);
        $endDate = explode('/', $data['end_date']);

        $startDate = DateTimeFactory::of($startDate[2], $startDate[1], $startDate[0]);
        $startDate = $startDate->format('Y-m-d');

        $endDate = DateTimeFactory::of($endDate[2], $endDate[1], $endDate[0]);
        $endDate = $endDate->format('Y-m-d');

        foreach ($performers as $performerId) {
            TaskAssign::create([
                'task_id' => (int)$taskId,
                'assigned_by_id' => auth()->user()->id,
                'assigned_to_id' => $performerId,
                'assigned_at' => now(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'expected_value' => (int)$expectedValue,
                'time_gap' => (int)$timeGap,
            ]);
        }

        return redirect()->back()->withSuccess(__('crud.common.assigned'));
    }

    public function taskAssignRemove($performer, $task, Request $request){

        // $this->authorize('task-assign-delete', $task);

        $user_office = auth()->user()->offices[0]->id;
        $task = Task::find($task);

        $deletePerformerTask = TaskAssign::where('assigned_to_id', $performer)->where('task_id', $task->id)->delete();

        return redirect()->back()->withSuccess(__('crud.common.removed'));

    }

    public function assignedTasksIndex(Request $request){

        $search = $request->get('search', '');

        // get only assigned and accepted tasks using their status
        $assignedTasks = TaskAssign::whereIn('status', [0, 1])->where('assigned_to_id', auth()->user()->id)->paginate(10);
        return view('app.performer-task.index', compact('assignedTasks'));
    }

    public function assignedTaskStatus(Request $request){

        $taskAssignId = $request->input('taskAssignId');
        $taskAssign = TaskAssign::where('id', (int)$taskAssignId)->where('assigned_to_id', auth()->user()->id)->update([
            'status' => $request->type === 'accept' ? 1 : 2,
            'reject_reason' => $request->type === 'reject' ? $request->input('reject_text')  : NULL
        ]);

        return redirect()->back()->withSuccess(__($request->type === 'accept' ? 'crud.common.accepted' : 'crud.common.rejected'));
    }

    public function assignedTaskReport(Request $request){

        $data = $request->input();

        $taskReported = TaskAccomplishment::create([
            'task_assign_id' => (int)$data['taskAssignId'],
            'reported_value' => $data['reported-value'],
            'reported_at' => now(),
            'task_done_description' => $data['report_text'],
        ]);

        $taskStatusChanged = TaskAssign::where('id', (int)$data['taskAssignId'])->where('assigned_to_id', auth()->user()->id)->update([
            'status' => 3,
        ]);


        return redirect()->back()->withSuccess(__('crud.common.saved'));
    }

    public function assignedTaskHistory(Request $request){

        $perfomer = auth()->user()->id;

        $taskHistory = TaskAssign::where('assigned_to_id', $perfomer)
            ->whereIn('status', [2,3,4])
            ->paginate(10);

        return view('app.performer-task.view-tasks', compact('taskHistory'));

    }

    public function getPerformerReportInfo($data)
    {

        $returnValue = TaskAccomplishment::where('task_assign_id', $data)->first();
        // error_log($returnValue->reported_value);

        $responseData = [];
        $responseData['reported_value'] = $returnValue->reported_value;
        $responseData['reported_description'] = $returnValue->task_done_description;

        return response()->json($responseData);
    }

    public function getPerformerEvaluationInfo($data)
    {

        $returnValue = TaskAccomplishment::where('task_assign_id', $data)->first();
        // error_log($returnValue->reported_value);

        $responseData = [];
        $responseData['evaluated_value'] = $returnValue->reported_value;
        $responseData['evaluated_description'] = $returnValue->task_done_description;

        return response()->json($responseData);
    }
}
