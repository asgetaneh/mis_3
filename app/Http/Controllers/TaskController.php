<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\KeyPeformanceIndicator;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\ReportingPeriod;


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
        $offices = $request->input('offices');
        $language = Language::all();
         //$lastGoal = Goal::select('id')->orderBy('id','desc')->first();
        try {
            $task = new Task;
             foreach ($language as $key => $value) {
                // code...
                $task->weight= $data['weight'] ?? null;
                $task->period_id= $data['reporting_period'];
                $task->kpi_id = $office;
                $task->office_id = $data['kpi'];
                $task->created_by_id= auth()->user()->id;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
