<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;
use App\Models\KpiChildOne;
use App\Models\KpiChildTwo;
use Illuminate\Http\Request;
use App\Models\KpiChildThree;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\KpiChildTwoTranslation;
use App\Http\Requests\KpiChildTwoStoreRequest;
use App\Http\Requests\KpiChildTwoUpdateRequest;

class KpiChildTwoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', KpiChildTwo::class);

        $search = $request->get('search', '');

        $kpiChildTwos = KpiChildTwo::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.kpi_child_twos.index',
            compact('kpiChildTwos', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KpiChildTwo::class);

        $kpiChildOnes = KpiChildOne::pluck('id', 'id');

        return view('app.kpi_child_twos.create', compact('kpiChildOnes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KpiChildTwoStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', KpiChildTwo::class);

        $validated = $request->validated();

        $kpiChildTwo = KpiChildTwo::create($validated);

        return redirect()
            ->route('kpi-child-twos.edit', $kpiChildTwo)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, KpiChildTwo $kpiChildTwo): View
    {
        $this->authorize('view', $kpiChildTwo);

        return view('app.kpi_child_twos.show', compact('kpiChildTwo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, KpiChildTwo $kpiChildTwo): View
    {
        $this->authorize('update', $kpiChildTwo);

        $kpiChildOnes = KpiChildOne::pluck('id', 'id');

        return view(
            'app.kpi_child_twos.edit',
            compact('kpiChildTwo', 'kpiChildOnes')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        KpiChildTwoUpdateRequest $request,
        KpiChildTwo $kpiChildTwo
    ): RedirectResponse {
        $this->authorize('update', $kpiChildTwo);

        $validated = $request->validated();

        $kpiChildTwo->update($validated);

        return redirect()
            ->route('kpi-child-twos.edit', $kpiChildTwo)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        KpiChildTwo $kpiChildTwo
    ): RedirectResponse {
        $this->authorize('delete', $kpiChildTwo);

        $kpiChildTwo->delete();

        return redirect()
            ->route('kpi-child-twos.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function kpiChainThree(Request $request, $id): View
    {
        $KpiChildTwo = KpiChildTwo::find($id);
        $KpiChildThree = KpiChildThree::all();

        // fix below code later
        // $childThreeAdds = $KpiChildTwo->kpiChildThrees;
        // dd($childThreeAdds);

        $languages = Language::all();

        return view(
            'app.kpi_child_two_translations.chain',
            compact(
                'KpiChildTwo',
                'KpiChildThree',
                // 'childThreeAdds',
                'languages'
            )
        );
    }

    public function kpiChainThreeStore(Request $request){
        // dd($request);

        $data = $request->input();
        $kpiChildTwo = $data['kpiChildTwoId'];
        $childThreeLists = $data['kpiThreeLists'];

        foreach($childThreeLists as $childThreeList){
            $kpiChildOneTwo = DB::insert('insert into kpi_child_three_kpi_child_two (kpi_child_three_id, kpi_child_two_id) values (?, ?)', [$childThreeList, $kpiChildTwo]);
        }

        $search = $request->get('search', '');

        $kpiChildTwoTranslations = KpiChildTwoTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return redirect()->
            route('kpi-child-two-translations.index', $kpiChildTwoTranslations)
        ->withSuccess(__('crud.common.created'));

    }

    // below code is to be worked later
    public function kpiChainThreeRemove($kpiChildOne, $childTwo,
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
