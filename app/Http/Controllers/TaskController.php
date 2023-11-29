<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Performer;
use App\Models\Office;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Models\OfficeTranslation;
use App\Models\KeyPeformanceIndicator;
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
        $reporting_periods = ReportingPeriod::search($search)
             ->oldest()
            ->paginate(10)
            ->withQueryString();
         return view(
            'app.task.index',
            compact('tasks','kpis','reporting_periods', 'search')
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
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
         return view(
            'app.task.create',
            compact('tasks','kpis','reporting_periods', 'languages', 'search')
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

        return view(
            'app.task.edit',
            compact('tasks','task', 'kpis','reporting_periods', 'search', 'languages')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        // dd($request->all());

        $data = $request->input();
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
        }

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
    
}
