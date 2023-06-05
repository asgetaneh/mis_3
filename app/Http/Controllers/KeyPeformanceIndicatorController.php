<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Language;
use App\Models\Strategy;
use App\Models\Objective;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Http\Requests\KeyPeformanceIndicatorStoreRequest;
use App\Http\Requests\KeyPeformanceIndicatorUpdateRequest;
use App\Models\KeyPeformanceIndicatorT;
use App\Models\ReportingPeriodTypeT;

class KeyPeformanceIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KeyPeformanceIndicator::class);

        $search = $request->get('search', '');

        $keyPeformanceIndicator_ts = KeyPeformanceIndicatorT::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.key_peformance_indicators.index',
            compact('keyPeformanceIndicator_ts', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $objectives = Objective::all();
        $strategies = Strategy::all();
        $users = User::pluck('name', 'id');
        $reportingPeriodTypes = ReportingPeriodType::all();
        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.key_peformance_indicators.create',
            compact('objectives', 'strategies', 'users', 'reportingPeriodTypes', 'languages')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $this->authorize('create', KeyPeformanceIndicator::class);

        // $validated = $request->validated();

        // $keyPeformanceIndicator = KeyPeformanceIndicator::create($validated);

        $data = $request->input();
        $language = Language::all();
         //$lastGoal = Goal::select('id')->orderBy('id','desc')->first();
        try {
            $keyPeformanceIndicator = new KeyPeformanceIndicator;
            $keyPeformanceIndicator->weight= $data['weight'];
            $keyPeformanceIndicator->objective_id= $data['objective_id'];
            $keyPeformanceIndicator->strategy_id= $data['strategy_id'];
            $keyPeformanceIndicator->reporting_period_type_id= $data['reporting_period_type_id'];
            $keyPeformanceIndicator->created_by_id= auth()->user()->id;
            $keyPeformanceIndicator->save();
             foreach ($language as $key => $value) {
                // code...
                $kpi_translation = new KeyPeformanceIndicatorT;
                $kpi_translation ->translation_id=$keyPeformanceIndicator->id;
                $kpi_translation ->name = $data['name'.$value->locale];
                $kpi_translation ->locale = $value->locale;
                $kpi_translation ->description = $data['description'.$value->locale];
                $kpi_translation ->out_put = $data['output'.$value->locale];
                $kpi_translation ->out_come = $data['outcome'.$value->locale];
                $kpi_translation->save();
         }

         return redirect()
            ->route('key-peformance-indicators.index', $keyPeformanceIndicator)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('key-peformance-indicators/create')->withErrors(['errors' => $e]);
            }

        return redirect()
            ->route('key-peformance-indicators.edit', $keyPeformanceIndicator)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): View {
        $this->authorize('view', $keyPeformanceIndicator);

        return view(
            'app.key_peformance_indicators.show',
            compact('keyPeformanceIndicator')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): View {
        $this->authorize('update', $keyPeformanceIndicator);

        $objectives = Objective::pluck('id', 'id');
        $strategies = Strategy::pluck('id', 'id');
        $users = User::pluck('name', 'id');
        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.key_peformance_indicators.edit',
            compact(
                'keyPeformanceIndicator',
                'objectives',
                'strategies',
                'users',
                'reportingPeriodTypes'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KeyPeformanceIndicatorUpdateRequest $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): RedirectResponse {
        $this->authorize('update', $keyPeformanceIndicator);

        $validated = $request->validated();

        $keyPeformanceIndicator->update($validated);

        return redirect()
            ->route('key-peformance-indicators.edit', $keyPeformanceIndicator)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): RedirectResponse {
        $this->authorize('delete', $keyPeformanceIndicator);

        $keyPeformanceIndicator->delete();

        return redirect()
            ->route('key-peformance-indicators.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
