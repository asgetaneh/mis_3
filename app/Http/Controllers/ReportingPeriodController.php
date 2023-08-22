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
use Illuminate\Support\Facades\DB;


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
            ->paginate(25)
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
             // $reportingPeriod->planing_year_id= $data['planing_year_id'];
            // $reportingPeriod->start_date= $data['start_date'];
            // $reportingPeriod->end_date= $data['end_date'];
            //  $reportingPeriod->planing_year_id= $data['planing_year_id'];
            $reportingPeriod->start_date= $startDate;
            $reportingPeriod->end_date= $endDate;
            $reportingPeriod->reporting_period_type_id= $data['reporting_period_type_id'];
            $reportingPeriod->save();
            // to update
            $no_r_period = ReportingPeriod::select('reporting_periods.id')
                      ->where('reporting_period_type_id','=', $reportingPeriod->reporting_period_type_id)->get();
            $reportingPeriodslug =count($no_r_period);
            $updated = tap(DB::table('reporting_periods') 
            ->where('reporting_periods.id' , '=',$reportingPeriod->id))
            ->update(['slug' => (string)$reportingPeriodslug])
            ->first();
             foreach ($language as $key => $value) {
                // code...
                $reportingPeriodTranslation = new ReportingPeriodT;
                $reportingPeriodTranslation->reporting_period_id=$reportingPeriod->id;
                $reportingPeriodTranslation->name = $data['name_'.$value->locale];
                $reportingPeriodTranslation->description = $data['description_'.$value->locale];
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
        $reportingPeriodTypes = ReportingPeriodTypeT::all();
        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $periodTranslations = $reportingPeriod->reportingPeriodTs->groupBy('locale');

        return view(
            'app.reporting_periods.edit',
            compact('reportingPeriod', 'planingYears', 'reportingPeriodTypes', 'languages', 'periodTranslations')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        ReportingPeriod $reportingPeriod
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriod);

        $data = $request->input();

        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        if(str_contains($startDate, '-')){

        }else{
            $startDate = explode('/', $startDate);
            $startDate = DateTimeFactory::of($startDate[2], $startDate[1], $startDate[0]);
            $startDate = $startDate->format('Y-m-d');

            $reportingPeriod->update([
                'start_date' => $startDate,
            ]);
        }

        if(str_contains($endDate, '-')){

        }else{
            $endDate = explode('/', $data['end_date']);
            $endDate = DateTimeFactory::of($endDate[2], $endDate[1], $endDate[0]);
            $endDate = $endDate->format('Y-m-d');

            $reportingPeriod->update([
                'end_date' => $endDate,
            ]);
        }

        $reportingPeriod->update([
            // 'start_date' => $startDate,
            // 'end_date' => $endDate,
            'reporting_period_type_id' => $request->reporting_period_type_id,
            'updated_at' => new \DateTime(),
        ]);

        foreach ($request->except('_token', '_method', 'start_date', 'end_date', 'reporting_period_type_id') as $key => $value) {

            $locale = str_replace(['name_', 'description_'], '', $key);

            $periodTranslation = $reportingPeriod->reportingPeriodTs->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($periodTranslation) {
                $periodTranslation->update([
                    $column => $value
                ]);
            }
        }

        return redirect()
            ->route('reporting-periods.index', $reportingPeriod)
            ->withSuccess(__('crud.common.updated'));
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
