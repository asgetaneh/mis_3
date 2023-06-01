<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\User;
use App\Models\Language;
use App\Models\Objective;
use Illuminate\View\View;
use App\Models\Perspective;
use Illuminate\Http\Request;
use App\Models\GoalTranslation;
use App\Models\ObjectiveTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ObjectiveStoreRequest;
use App\Http\Requests\ObjectiveUpdateRequest;

class ObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Objective::class);

        $search = $request->get('search', '');

        $objective_ts = ObjectiveTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.objectives.index', compact('objective_ts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Objective::class);
        $search = $request->get('search', '');
        $goals = Goal::all();
         $perspectives = Perspective::all();
        $users = User::pluck('name', 'id');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.objectives.create',
            compact('goals', 'perspectives', 'users', 'users', 'languages')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Objective::class);

        // $validated = $request->validated();

        // $objective = Objective::create($validated);

        // return redirect()
        //     ->route('objectives.edit', $objective)
        //     ->withSuccess(__('crud.common.created'));

        $data = $request->input();
        $language = Language::all();
         //$lastGoal = Goal::select('id')->orderBy('id','desc')->first();
        try {
            $objective = new Objective;
            $objective->updated_by_id= auth()->user()->id;
            $objective->created_by_id= auth()->user()->id;
            $objective->goal_id= $data['goal_id'];
            $objective->perspective_id= $data['perspective_id'];
            $objective->weight= $data['weight'];
            $objective->save();
            // $objective->created_at= new \DateTime();
            // $objective->updated_at =new \DateTime();
            $objective->save();
             foreach ($language as $key => $value) {
                // code...
                $objective_translation = new ObjectiveTranslation;
                $objective_translation ->translation_id=$objective->id;
                $objective_translation ->name = $data['name'.$value->locale];
                $objective_translation ->out_put = $data['output'.$value->locale];
                $objective_translation ->out_come = $data['outcome'.$value->locale];
                // $objective_translation ->locale = $value->locale; // add locale in migration later
                $objective_translation ->description = $data['description'.$value->locale];
                $objective_translation->save();
         }
         return redirect()
            ->route('objectives.index', $objective)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('goal/new')->withErrors(['errors' => $e]);
            }

        // code is unreachable
        $objectives = ObjectiveTranslation::all();
        return view('objective.index',['objectives', $objectives]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Objective $objective): View
    {
        $this->authorize('view', $objective);

        return view('app.objectives.show', compact('objective'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Objective $objective): View
    {
        $this->authorize('update', $objective);

        $goals = Goal::pluck('id', 'id');
        $perspectives = Perspective::pluck('id', 'id');
        $users = User::pluck('name', 'id');

        return view(
            'app.objectives.edit',
            compact('objective', 'goals', 'perspectives', 'users', 'users')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ObjectiveUpdateRequest $request,
        Objective $objective
    ): RedirectResponse {
        $this->authorize('update', $objective);

        $validated = $request->validated();

        $objective->update($validated);

        return redirect()
            ->route('objectives.edit', $objective)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Objective $objective
    ): RedirectResponse {
        $this->authorize('delete', $objective);

        $objective->delete();

        return redirect()
            ->route('objectives.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
