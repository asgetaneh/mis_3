<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Behavior;
use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\BehaviorTranslation;

class BehaviorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Behavior::class);

        $search = $request->get('search', '');
        $behavior_ts = BehaviorTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('app.behaviors.index', compact('behavior_ts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Behavior::class);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.behaviors.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Behavior::class);

        $data = $request->input();
        $language = Language::all();

        try {
            $behavior = new Behavior;
            $behavior->created_at = new \DateTime();
            $behavior->updated_at = new \DateTime();
            $behavior->save();
            foreach ($language as $key => $value) {
                // code...
                $behavior_translation = new BehaviorTranslation;
                $behavior_translation->translation_id = $behavior->id;
                $behavior_translation->name = $data['name_' . $value->locale];
                $behavior_translation->slug = $data['slug_' . $value->locale];
                $behavior_translation->description = $data['description_' . $value->locale];
                $behavior_translation->locale = $value->locale;
                $behavior_translation->save();
            }
            return redirect()
                ->route('behaviors.index', $behavior)
                ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Behavior $behavior): View
    {
        $this->authorize('view', $behavior);

        return view('app.behaviors.show', compact('behavior'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Behavior $behavior)
    {
        $this->authorize('update', $behavior);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $behaviorTranslations = $behavior->behaviorTranslations->groupBy('locale');

        return view('app.behaviors.edit', compact('behavior', 'languages', 'behaviorTranslations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Behavior $behavior)
    {
        $this->authorize('update', $behavior);

        $behavior->update([
            'updated_at' => new \DateTime(),
        ]);

        foreach ($request->except('_token', '_method') as $key => $value) {

            $locale = str_replace(['name_', 'description_', 'slug_'], '', $key);

            $behaviorTranslation = $behavior->behaviorTranslations->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($behaviorTranslation) {
                $behaviorTranslation->update([
                    $column => $value
                ]);
            }
        }

        return redirect()
            ->route('behaviors.index', $behavior)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Behavior $behavior)
    {
        $this->authorize('delete', $behavior);

        $behavior->delete();

        return redirect()
            ->route('behaviors.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
