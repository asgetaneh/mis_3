<?php

namespace App\Http\Controllers;

use App\Models\TaskMeasurement;
use App\Models\Language;
use Illuminate\Http\Request;

class TaskMeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search', '');
        $office = auth()->user()->offices[0]->id;
        $task_measurements = TaskMeasurement::search($search)
             ->oldest()
            ->paginate(10)
            ->withQueryString();
         return view(
            'app.taskmeasurement.index',
            compact('task_measurements', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
        return view('app.taskmeasurement.create', compact('languages', 'search'));
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
         try {
            $TaskMeasurement = new TaskMeasurement;
            $TaskMeasurement->expected_value  = $data['Expected_value'];
            foreach ($language as $key => $value) {
               $TaskMeasurement ->name = $data['name_'.$value->locale];
               $TaskMeasurement ->description = $data['description_'.$value->locale];
              $TaskMeasurement->save(); 
         }
         return redirect()
            ->route('TaskMeasurements.index',$TaskMeasurement)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('taskmeasurement/create')->withErrors(['errors' => $e]);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskMeasurement $taskMeasurement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskMeasurement $taskMeasurement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskMeasurement $taskMeasurement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskMeasurement $taskMeasurement)
    {
        //
    }
}
