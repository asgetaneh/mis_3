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
use App\Models\PerspectiveTranslation;
use App\Models\ObjectiveTranslation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ObjectiveStoreRequest;
use App\Http\Requests\ObjectiveUpdateRequest;
use Illuminate\Support\Facades\Gate;
class ObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
         if (! Gate::allows('view keypeformanceindicators', KeyPeformanceIndicator::class)) {
                    abort(403);
                }

        $search = $request->get('search', '');
        $goals = Goal::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $objective_ts = ObjectiveTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('app.objectives.index', compact('goals','objective_ts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Objective::class);
        $search = $request->get('search', '');
        $goals = GoalTranslation::all();
        $perspectives = PerspectiveTranslation::all();
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
            $objective->is_active= true;
            $objective->objective_number= 1;
            $objective->save();
            // $objective->created_at= new \DateTime();
            // $objective->updated_at =new \DateTime();
            $objective->save();
             foreach ($language as $key => $value) {
                // code...
                $objective_translation = new ObjectiveTranslation;
                $objective_translation ->translation_id=$objective->id;
                $objective_translation ->name = $data['name_'.$value->locale];
                $objective_translation ->out_put = $data['out_put_'.$value->locale];
                $objective_translation ->out_come = $data['out_come_'.$value->locale];
                $objective_translation ->locale = $value->locale; // add locale in migration later
                $objective_translation ->description = $data['description_'.$value->locale];
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

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $objectiveTranslations = $objective->objectiveTranslations->groupBy('locale');

        $goals = GoalTranslation::all();
        $perspectives = PerspectiveTranslation::all();
        $users = User::pluck('name', 'id');

        return view(
            'app.objectives.edit',
            compact('objective', 'objectiveTranslations', 'goals', 'perspectives', 'users', 'languages')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        Objective $objective
    ): RedirectResponse {
        $this->authorize('update', $objective);

        $objective->update([
            'goal_id' => $request->goal_id,
            'perspective_id' => $request->perspective_id,
            'weight' => $request->weight,
            'updated_at' => new \DateTime(),
            'updated_by_id' => auth()->user()->id,
            // 'created_by_id' => $goal->created_by_id || '',
        ]);

        $isNewLangAdded = false;
        $localeArray = [];

        foreach ($request->except('_token', '_method', 'goal_id', 'perspective_id', 'weight') as $key => $value) {

            $locale = str_replace(['name_', 'description_', 'out_put_', 'out_come_'], '', $key);

            $objectiveTranslation = $objective->objectiveTranslations->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($objectiveTranslation) {
                $objectiveTranslation->update([
                    $column => $value
                ]);
            }else{
                $isNewLangAdded = true;
                array_push($localeArray, $locale);
            }
        }

        // handle editing if new language was added but translation has no recored for the new language
        if($isNewLangAdded){
            $localeArray = array_unique($localeArray);
            foreach($localeArray as $locale){
                // dd($locale);

                $loc = $locale;
                $inputName = 'name_'.$loc;
                $inputDescription = 'description_'.$loc;
                $inputOutcome = 'out_come_'.$loc;
                $inputOutput = 'out_put_'.$loc;

                $name = $request->input($inputName);
                $description = $request->input($inputDescription);
                $output = $request->input($inputOutput);
                $outcome = $request->input($inputOutcome);

                $goalTranslation = new ObjectiveTranslation;
                $goalTranslation->translation_id = $objective->id;
                $goalTranslation->name = $name;
                $goalTranslation->locale = $locale;
                $goalTranslation->description = $description;
                $goalTranslation->out_put = $output;
                $goalTranslation->out_come = $outcome;
                $goalTranslation->save();
            }
        }

        return redirect()
            ->route('objectives.index', $objective)
            ->withSuccess(__('crud.common.updated'));

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
