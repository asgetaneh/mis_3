<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Language;
use Illuminate\View\View;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use App\Models\ReportingPeriod;
use App\Models\ReportingPeriodT;
use App\Models\ReportingPeriodType;
use App\Models\ReportingPeriodTypeT;
use Illuminate\Http\RedirectResponse;
use App\Models\PlaningYearTranslation;
use App\Http\Requests\ReportingPeriodStoreRequest;
use App\Http\Requests\ReportingPeriodUpdateRequest;
use Andegna\DateTimeFactory;

class ReportingPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ReportingPeriod::class);

        $search = $request->get('search', '');

        $reportingPeriodTS = ReportingPeriodT::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.reporting_periods.index',
            compact('reportingPeriodTS', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ReportingPeriod::class);

        $planingYears = PlaningYearTranslation::all();
        $reportingPeriodTypes = ReportingPeriodTypeT::all();
        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $users = User::pluck('name', 'id');

        return view(
            'app.reporting_periods.create',
            compact('planingYears', 'reportingPeriodTypes', 'languages', 'users')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $this->authorize('create', ReportingPeriod::class);

        // $validated = $request->validated();

        // $reportingPeriod = ReportingPeriod::create($validated);

        $data = $request->input();

        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        $startDate = explode('/', $data['start_date']);
        $endDate = explode('/', $data['end_date']);

        $startDate = DateTimeFactory::of($startDate[2], $startDate[1], $startDate[0]);
        $startDate = $startDate->format('Y-m-d');

        $endDate = DateTimeFactory::of($endDate[2], $endDate[1], $endDate[0]);
        $endDate = $endDate->format('Y-m-d');

        $language = Language::all();
         //$lastGoal = Goal::select('id')->orderBy('id','desc')->first();
        try {
            $reportingPeriod = new ReportingPeriod;
            $reportingPeriod->planing_year_id= $data['planing_year_id'];
            $reportingPeriod->start_date= $startDate;
            $reportingPeriod->end_date= $endDate;
            $reportingPeriod->reporting_period_type_id= $data['reporting_period_type_id'];
            $reportingPeriod->save();

             foreach ($language as $key => $value) {
                // code...
                $reportingPeriodTranslation = new ReportingPeriodT;
                $reportingPeriodTranslation->reporting_period_id=$reportingPeriod->id;
                $reportingPeriodTranslation->name = $data['name'.$value->locale];
                $reportingPeriodTranslation->description = $data['description'.$value->locale];
                $reportingPeriodTranslation->locale = $value->locale;
                $reportingPeriodTranslation->save();
         }

         return redirect()
            ->route('reporting-periods.index', $reportingPeriod)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('reporting-periods/create')->withErrors(['errors' => $e]);
            }

        return redirect()
            ->route('reporting-periods.edit', $reportingPeriod)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): View {
        $this->authorize('view', $reportingPeriod);

        return view('app.reporting_periods.show', compact('reportingPeriod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): View {
        $this->authorize('update', $reportingPeriod);

        $planingYears = PlaningYear::pluck('id', 'id');
        $reportingPeriodTypes = ReportingPeriodType::pluck('id', 'id');

        return view(
            'app.reporting_periods.edit',
            compact('reportingPeriod', 'planingYears', 'reportingPeriodTypes')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReportingPeriodUpdateRequest $request,
        ReportingPeriod $reportingPeriod
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriod);

        $validated = $request->validated();

        $reportingPeriod->update($validated);

        return redirect()
            ->route('reporting-periods.edit', $reportingPeriod)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): RedirectResponse {
        $this->authorize('delete', $reportingPeriod);

        $reportingPeriod->delete();

        return redirect()
            ->route('reporting-periods.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
