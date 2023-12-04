<?php

namespace App\Http\Controllers;

use App\Models\TaskAssign;
use Illuminate\Http\Request;

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
        $TaskAssigns = TaskAssign::select('task_assigns.*')
                // ->join('kpi_office', 'key_peformance_indicators.id', '=', 'kpi_office.kpi_id')
                // ->join('offices', 'offices.id', '=', 'kpi_office.office_id')
                ->where('assigned_by_id',"=", $user_id)
                ->where('status',"=", 3)
                ->get();
         return view(
            'app.taskassign.index',
            compact('TaskAssigns', 'search')
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
