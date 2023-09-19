<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GoalStoreRequest;
use App\Http\Requests\GoalUpdateRequest;
use App\Models\Language;
use App\Models\GoalTranslation;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        if (! Gate::allows('view keypeformanceindicators', Goal::class)) {
                    abort(403);
                }
        $search = $request->get('search', '');
        $goal_ts = GoalTranslation::search($search)

             ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('app.goals.index', compact('goal_ts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Goal::class);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $users = User::pluck('name', 'id');

        return view('app.goals.create', compact('languages', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Goal::class);

        $data = $request->input();
        $language = Language::all();
         //$lastGoal = Goal::select('id')->orderBy('id','desc')->first();
        try {
            $goal = new Goal;
            $goal->updated_by= auth()->user()->id;
            $goal->created_by_id= auth()->user()->id;
            $goal->created_at= new \DateTime();
            $goal->updated_at =new \DateTime();
            $goal->save();
             foreach ($language as $key => $value) {
                // code...
                $goal_translation = new GoalTranslation;
                $goal_translation ->translation_id=$goal->id;
                $goal_translation ->name = $data['name_'.$value->locale];
                $goal_translation ->out_put = $data['out_put_'.$value->locale];
                $goal_translation ->out_come = $data['out_come_'.$value->locale];
                $goal_translation ->locale = $value->locale;
                $goal_translation ->description = $data['description_'.$value->locale];
                $goal_translation->save();
         }
         return redirect()
            ->route('goals.index', $goal)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('goal/new')->withErrors(['errors' => $e]);
            }
        $goals = GoalTranslation::all();
        return view('goal.index',['goals', $goals]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Goal $goal): View
    {
        $this->authorize('view', $goal);

        return view('app.goals.show', compact('goal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Goal $goal): View
    {
        $this->authorize('update', $goal);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $goalTranslations = $goal->goalTranslations->groupBy('locale');
        // dd($goalTranslations);

        return view('app.goals.edit', compact('goal', 'languages', 'goalTranslations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        Goal $goal
    ): RedirectResponse {
        $this->authorize('update', $goal);

        $goal->update([
            'updated_at' => new \DateTime(),
            'updated_by' => auth()->user()->id,
            // 'created_by_id' => $goal->created_by_id || '',
        ]);

        $isNewLangAdded = false;
        $localeArray = [];

        foreach ($request->except('_token', '_method') as $key => $value) {

            $locale = str_replace(['name_', 'description_', 'out_put_', 'out_come_'], '', $key);

            $goalTranslation = $goal->goalTranslations->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($goalTranslation) {
                $goalTranslation->update([
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

                $goalTranslation = new GoalTranslation;
                $goalTranslation->translation_id = $goal->id;
                $goalTranslation->name = $name;
                $goalTranslation->locale = $locale;
                $goalTranslation->description = $description;
                $goalTranslation->out_put = $output;
                $goalTranslation->out_come = $outcome;
                $goalTranslation->save();
            }
        }

        return redirect()
            ->route('goals.index', $goal)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Goal $goal): RedirectResponse
    {
        $this->authorize('delete', $goal);

        $goal->delete();

        return redirect()
            ->route('goals.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
