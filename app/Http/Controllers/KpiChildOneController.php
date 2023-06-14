<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;
use App\Models\KpiChildOne;
use App\Models\KpiChildTwo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Http\Requests\KpiChildOneStoreRequest;
use App\Http\Requests\KpiChildOneUpdateRequest;
use App\Models\KpiChildOneTranslation;

class KpiChildOneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KpiChildOne::class);

        $search = $request->get('search', '');

        $kpiChildOnes = KpiChildOne::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.kpi_child_ones.index',
            compact('kpiChildOnes', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KpiChildOne::class);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.kpi_child_ones.create',
            compact('keyPeformanceIndicators')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KpiChildOneStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', KpiChildOne::class);

        $validated = $request->validated();

        $kpiChildOne = KpiChildOne::create($validated);

        return redirect()
            ->route('kpi-child-ones.edit', $kpiChildOne)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, KpiChildOne $kpiChildOne): View
    {
        $this->authorize('view', $kpiChildOne);

        return view('app.kpi_child_ones.show', compact('kpiChildOne'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, KpiChildOne $kpiChildOne): View
    {
        $this->authorize('update', $kpiChildOne);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');

        return view(
            'app.kpi_child_ones.edit',
            compact('kpiChildOne', 'keyPeformanceIndicators')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KpiChildOneUpdateRequest $request,
        KpiChildOne $kpiChildOne
    ): RedirectResponse {
        $this->authorize('update', $kpiChildOne);

        $validated = $request->validated();

        $kpiChildOne->update($validated);

        return redirect()
            ->route('kpi-child-ones.edit', $kpiChildOne)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KpiChildOne $kpiChildOne
    ): RedirectResponse {
        $this->authorize('delete', $kpiChildOne);

        $kpiChildOne->delete();

        return redirect()
            ->route('kpi-child-ones.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function kpiChainTwo(Request $request, $id): View
    {
        $KpiChildOne = KpiChildOne::find($id);
        $KpiChildTwo = KpiChildTwo::all();

        // fix below code later
        // $childTwoAdds = $KpiChildOne->kpiChildTwos;
        // dd($childTwoAdds);

        $languages = Language::all();

        return view(
            'app.kpi_child_one_translations.chain',
            compact(
                'KpiChildOne',
                'KpiChildTwo',
                // 'childTwoAdds',
                'languages'
            )
        );
    }

    public function kpiChainTwoStore(Request $request){
        // dd($request);

        $data = $request->input();
        $kpiChildOne = $data['kpiChildOneId'];
        $childTwoLists = $data['kpiTwoLists'];

        foreach($childTwoLists as $childOneList){
            $kpiChildOneTwo = DB::insert('insert into kpi_child_one_kpi_child_two (kpi_child_one_id, kpi_child_two_id) values (?, ?)', [$kpiChildOne, $childOneList]);
        }

        $search = $request->get('search', '');

        $kpiChildOneTranslations = KpiChildOneTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.kpi_child_one_translations.index',
            compact('kpiChildOneTranslations', 'search')
        );

    }

    // below code is to be worked later
    public function kpiChainTwoRemove($kpiChildOne, $childTwo,
        Request $request
    ): View {

        $kpiChildOne = KpiChildOne::find($kpiChildOne);

        $kpiChildOne->find($kpiChildOne)->kpiChildTwos()->detach();

        $KpiChildTwo = KpiChildTwo::all();
        $kpiChildOne = KpiChildOne::find($kpiChildOne);

        $childTwoAdds = $kpiChildOne->kpiChildTwos;

        return view(
            'app.key_peformance_indicators.chain',
            compact(
                'kpiChildOne',
                'KpiChildTwo',
                'childTwoAdds'
            )
        );

    }

}
