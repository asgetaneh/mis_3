<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Language;
use App\Models\Strategy;
use App\Models\Objective;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StrategyStoreRequest;
use App\Http\Requests\StrategyUpdateRequest;
use App\Models\ObjectiveTranslation;
use App\Models\StrategyTranslation;

class StrategyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Strategy::class);

        $search = $request->get('search', '');

        $strategy_ts = StrategyTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.strategies.index', compact('strategy_ts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Strategy::class);

        $search = $request->get('search', '');
        $objectives = ObjectiveTranslation::all();
        $users = User::pluck('name', 'id');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.strategies.create',
            compact('objectives', 'users', 'languages')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Strategy::class);

        // $validated = $request->validated();

        // $strategy = Strategy::create($validated);

        $data = $request->input();
        $language = Language::all();
         //$lastGoal = Goal::select('id')->orderBy('id','desc')->first();
        try {
            $strategy = new Strategy;
            $strategy->updated_by_id= auth()->user()->id;
            $strategy->created_by_id= auth()->user()->id;
            $strategy->objective_id= $data['objective_id']; 
            $strategy->save();
            // $strategy->created_at= new \DateTime();
            // $strategy->updated_at =new \DateTime();
            $strategy->save();
             foreach ($language as $key => $value) {
                // code...
                $strategy_translation = new StrategyTranslation;
                $strategy_translation ->translation_id=$strategy->id;
                $strategy_translation ->name = $data['name'.$value->locale];
                $strategy_translation ->locale = $value->locale;
                $strategy_translation ->discription = $data['description'.$value->locale];
                $strategy_translation->save();
         }

         return redirect()
            ->route('strategies.index', $strategy)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('strategy/new')->withErrors(['errors' => $e]);
            }

        return redirect()
            ->route('strategies.edit', $strategy)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Strategy $strategy): View
    {
        $this->authorize('view', $strategy);

        return view('app.strategies.show', compact('strategy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Strategy $strategy): View
    {
        $this->authorize('update', $strategy);

        $objectives = Objective::pluck('id', 'id');
        $users = User::pluck('name', 'id');

        return view(
            'app.strategies.edit',
            compact('strategy', 'objectives', 'users', 'users')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        StrategyUpdateRequest $request,
        Strategy $strategy
    ): RedirectResponse {
        $this->authorize('update', $strategy);

        $validated = $request->validated();

        $strategy->update($validated);

        return redirect()
            ->route('strategies.edit', $strategy)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Strategy $strategy
    ): RedirectResponse {
        $this->authorize('delete', $strategy);

        $strategy->delete();

        return redirect()
            ->route('strategies.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
