<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ReportingPeriodType;
use App\Models\ReportingPeriodTypeT;
use Illuminate\Http\RedirectResponse;
use App\Models\ReportingPeriodTypeTranslation;
use App\Http\Requests\ReportingPeriodTypeStoreRequest;
use App\Http\Requests\ReportingPeriodTypeUpdateRequest;

class ReportingPeriodTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', ReportingPeriodType::class);

        $search = $request->get('search', '');

        $reportingPeriodTypeTS = ReportingPeriodTypeT::search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'app.reporting_period_types.index',
            compact('reportingPeriodTypeTS', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', ReportingPeriodType::class);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $users = User::pluck('name', 'id');
        return view('app.reporting_period_types.create', compact('users', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $this->authorize('create', ReportingPeriodType::class);

        // $validated = $request->validated();

        // $reportingPeriodType = ReportingPeriodType::create($validated);

        $data = $request->input();
        $language = Language::all();

        try {
            $reportingPeriodType = new ReportingPeriodType;
            $reportingPeriodType->save();
             foreach ($language as $key => $value) {
                // code...
                $reportingPeriodTypeTS = new ReportingPeriodTypeT;
                $reportingPeriodTypeTS ->reporting_period_type_id=$reportingPeriodType->id;
                $reportingPeriodTypeTS ->name = $data['name_'.$value->locale];
                $reportingPeriodTypeTS ->locale = $value->locale;
                $reportingPeriodTypeTS ->description = $data['description_'.$value->locale];
                $reportingPeriodTypeTS->save();
         }
         return redirect()
            ->route('reporting-period-types.index', $reportingPeriodType)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('reporting-period-ts/create')->withErrors(['errors' => $e]);
            }

        return redirect()
            ->route('reporting-period-types.edit', $reportingPeriodType)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): View {
        $this->authorize('view', $reportingPeriodType);

        return view(
            'app.reporting_period_types.show',
            compact('reportingPeriodType')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): View {
        $this->authorize('update', $reportingPeriodType);

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $reportingTypeTranslations = $reportingPeriodType->reportingPeriodTypeTs->groupBy('locale');

        return view(
            'app.reporting_period_types.edit',
            compact('reportingPeriodType', 'reportingTypeTranslations', 'languages')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): RedirectResponse {
        $this->authorize('update', $reportingPeriodType);

        foreach ($request->except('_token', '_method') as $key => $value) {

            $locale = str_replace(['name_', 'description_'], '', $key);

            $reportingTypeTranslation = $reportingPeriodType->reportingPeriodTypeTs->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($reportingTypeTranslation) {
                $reportingTypeTranslation->update([
                    $column => $value
                ]);
            }
        }

        return redirect()
            ->route('reporting-period-types.index', $reportingPeriodType)
            ->withSuccess(__('crud.common.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        ReportingPeriodType $reportingPeriodType
    ): RedirectResponse {
        $this->authorize('delete', $reportingPeriodType);

        $reportingPeriodType->delete();

        return redirect()
            ->route('reporting-period-types.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
