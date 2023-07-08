<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use App\Models\Language;
use App\Models\Strategy;
use App\Models\Objective;
use Illuminate\View\View;
use App\Models\KpiChildOne;
use Illuminate\Http\Request;
use App\Models\OfficeTranslation;
use Illuminate\Support\Facades\DB;
use App\Models\ReportingPeriodType;
use App\Models\StrategyTranslation;
use App\Models\ObjectiveTranslation;
use App\Models\ReportingPeriodTypeT;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Models\KeyPeformanceIndicatorT;
use App\Models\KpiChildOneTranslation;
use App\Models\KpiChildTwoTranslation;
use App\Models\KpiChildThreeTranslation;



use App\Http\Requests\KeyPeformanceIndicatorStoreRequest;
use App\Http\Requests\KeyPeformanceIndicatorUpdateRequest;


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
            ->paginate(15)
            ->withQueryString();

        $kpiChildOneTranslations = KpiChildOneTranslation::search($search)
             ->latest()
            ->paginate(15)
            ->withQueryString();

             $kpiChildTwoTranslations = KpiChildTwoTranslation::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

            $kpiChildThreeTranslations = KpiChildThreeTranslation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.key_peformance_indicators.index',
            compact('keyPeformanceIndicator_ts','kpiChildOneTranslations', 'kpiChildTwoTranslations','kpiChildThreeTranslations','search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', KeyPeformanceIndicator::class);

        $objectives = ObjectiveTranslation::all();
        $strategies = StrategyTranslation::all();
        $users = User::pluck('name', 'id');
        $offices = OfficeTranslation::all();
        $reportingPeriodTypes = ReportingPeriodTypeT::all();
        // $offices = OfficeTranslation::whereHas('office', function ($query) {
        //     $query->where('parent_office_id', 1);
        // })->get();

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.key_peformance_indicators.create',
            compact('objectives', 'strategies', 'users', 'reportingPeriodTypes', 'languages', 'offices')
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
        $offices = $request->input('offices');
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
                $kpi_translation ->name = $data['name_'.$value->locale];
                $kpi_translation ->locale = $value->locale;
                $kpi_translation ->description = $data['description_'.$value->locale];
                $kpi_translation ->out_put = $data['out_put_'.$value->locale];
                $kpi_translation ->out_come = $data['out_come_'.$value->locale];
                $kpi_translation->save();
         }

        foreach($offices as $office){
            $kpiOffice = DB::insert('insert into kpi_office (office_id, kpi_id) values (?, ?)', [$office, $keyPeformanceIndicator->id]);
        }

         return redirect()
            ->route('key-peformance-indicators.index', $keyPeformanceIndicator)
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('key-peformance-indicators/create')->withErrors(['errors' => $e]);
            }

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

        $search = $request->get('search', '');
        $languages = Language::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $kpiTranslations = $keyPeformanceIndicator->keyPeformanceIndicatorTs->groupBy('locale');
        // dd($kpiTranslations);

        $objectives = ObjectiveTranslation::all();
        $strategies = StrategyTranslation::all();
        $offices = OfficeTranslation::all();
        $users = User::pluck('name', 'id');
        $reportingPeriodTypes = ReportingPeriodTypeT::all();
        $selectedOffices = $keyPeformanceIndicator->offices;

        return view(
            'app.key_peformance_indicators.edit',
            compact(
                'keyPeformanceIndicator',
                'objectives',
                'strategies',
                'users',
                'reportingPeriodTypes',
                'kpiTranslations',
                'offices',
                'languages',
                'selectedOffices'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        KeyPeformanceIndicator $keyPeformanceIndicator
    ): RedirectResponse {
        $this->authorize('update', $keyPeformanceIndicator);

        $offices = $request->input('offices');
        $keyPeformanceIndicator->offices()->sync($offices);

        $keyPeformanceIndicator->update([
            'objective_id' => $request->objective_id,
            'strategy_id' => $request->strategy_id,
            'reporting_period_type_id' => $request->reporting_period_type_id,
            'weight' => $request->weight,
            'updated_at' => new \DateTime(),
            // 'updated_by_id' => auth()->user()->id,
            // 'created_by_id' => $goal->created_by_id || '',
        ]);

        foreach ($request->except('_token', '_method', 'objective_id', 'strategy_id', 'reporting_period_type_id', 'weight', 'offices') as $key => $value) {

            $locale = str_replace(['name_', 'description_', 'out_put_', 'out_come_'], '', $key);

            $kpiTranslation = $keyPeformanceIndicator->keyPeformanceIndicatorTs->where('locale', $locale)->first();

            $column = str_replace('_'.$locale, '', $key);

            if ($kpiTranslation) {
                $kpiTranslation->update([
                    $column => $value
                ]);
            }
        }

        return redirect()
            ->route('key-peformance-indicators.index', $keyPeformanceIndicator)
            ->withSuccess(__('crud.common.updated'));
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

     public function kpiChain( Request $request, $id ): View {
         $keyPeformanceIndicator = KeyPeformanceIndicator::find($id);
        $kpiChildOneList = [];
        foreach ($keyPeformanceIndicator->kpiChildOnes as $ones){
            array_push($kpiChildOneList, $ones->id);
        }

        $KpiChildOne = KpiChildOne::whereNotIn('id', $kpiChildOneList)->get();
        $child_one_adds = $keyPeformanceIndicator->kpiChildOnes;

         return view(
            'app.key_peformance_indicators.chain',
            compact(
                'keyPeformanceIndicator',
                'KpiChildOne',
                 'child_one_adds'
            )
        );
    }
    public function kpiChainSave(
        Request $request
    ): View {
        $data = $request->input();
        $keyPeformanceIndicator = $data['keyPeformanceIndicator'];
        $chaild_one_lists = $data['kpi_one_child'];

         foreach($chaild_one_lists as $chaild_one_list){
            $kpi_chaild_one = DB::insert('insert into key_peformance_indicator_kpi_child_one (kpi_child_one_id, key_peformance_indicator_id) values (?, ?)', [$chaild_one_list, $keyPeformanceIndicator]);
        }
        $kpiChildOneList = [];
        $keyPeformanceIndicator = KeyPeformanceIndicator::find($keyPeformanceIndicator);
        foreach ($keyPeformanceIndicator->kpiChildOnes as $ones){
            array_push($kpiChildOneList, $ones->id);
        }
         $KpiChildOne = KpiChildOne::whereNotIn('id', $kpiChildOneList)->get();
        $child_one_adds = $keyPeformanceIndicator->kpiChildOnes;
             return view(
            'app.key_peformance_indicators.chain',
            compact(
                'keyPeformanceIndicator',
                'KpiChildOne',
                 'child_one_adds'
            )
        );
            return redirect()->route('kpi-Chain', [$keyPeformanceIndicator]);

             return redirect()->back()->with(
            [
                'keyPeformanceIndicator' => $keyPeformanceIndicator,
             ]

        );

    }
     public function kpiChainRemove($kpi, $child_one,
        Request $request
    ): View {
        $keyPeformanceIndicator = KeyPeformanceIndicator::find($kpi);

        $keyPeformanceIndicator->find($kpi)->kpiChildOnes()->detach();

       $KpiChildOne = KpiChildOne::all();
        $keyPeformanceIndicator = KeyPeformanceIndicator::find($kpi);

        $child_one_adds = $keyPeformanceIndicator->kpiChildOnes;

         return view(
            'app.key_peformance_indicators.chain',
            compact(
                'keyPeformanceIndicator',
                'KpiChildOne',
                 'child_one_adds'
            )
        );

    }
    public function kpiChainTwo(Request $request, $id): View
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::find($id);

        $kpiChildTwoList = [];
        foreach ($keyPeformanceIndicator->kpiChildTwos as $twos){
            array_push($kpiChildTwoList, $twos->id);
        }

        $KpiChildTwo_t = KpiChildTwoTranslation::whereNotIn('kpi_child_two_id', $kpiChildTwoList)->get();

        $childTwoAdds = $keyPeformanceIndicator->kpiChildTwos;

        $languages = Language::all();

        return view(
            'app.key_peformance_indicators.two_chain',
            compact(
                'keyPeformanceIndicator',
                'KpiChildTwo_t',
                'childTwoAdds',
                'languages'
            )
        );
    }

    public function kpiChainTwoStore(Request $request){
        // dd($request);

        $data = $request->input();
        $keyPeformanceIndicator = $data['kpiId'];
        $childTwoLists = $data['kpiTwoLists'];

        foreach($childTwoLists as $childOneList){
            $kpiChildOneTwo = DB::insert('insert into key_peformance_indicator_kpi_child_two (key_peformance_indicator_id, kpi_child_two_id) values (?, ?)', [$keyPeformanceIndicator, $childOneList]);
        }

        $search = $request->get('search', '');

        $keyPeformanceIndicators = KeyPeformanceIndicatorT::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();
             return redirect()->back()->with(
            [
                'keyPeformanceIndicator' => $keyPeformanceIndicator,
             ]

        );

    }

    // below code is to be worked later
    public function kpiChainTwoRemove($keyPeformanceIndicator_id, $childTwo,
        Request $request
    ) {

        $keyPeformanceIndicator = KeyPeformanceIndicator::find($keyPeformanceIndicator_id);

        $keyPeformanceIndicator->find($keyPeformanceIndicator_id)->kpiChildTwos()->detach($childTwo);

        $kpiChildTwoList = [];
        foreach ($keyPeformanceIndicator->kpiChildTwos as $twos){
            array_push($kpiChildTwoList, $twos->id);
        }

        $KpiChildTwo = KpiChildTwoTranslation::whereNotIn('kpi_child_two_id', $kpiChildTwoList)->get();

        $childTwoAdds = $keyPeformanceIndicator->kpiChildTwos;

        return redirect()->back()->with(
            [
                'keyPeformanceIndicator' => $keyPeformanceIndicator,
                'KpiChildTwo' => $KpiChildTwo,
                'childTwoAdds' => $childTwoAdds
            ]

        );

    }

    public function kpiChainThree(Request $request, $id): View
    {
        $keyPeformanceIndicator = KeyPeformanceIndicator::find($id);

        $kpiChildThreeList = [];
        foreach ($keyPeformanceIndicator->kpiChildThrees as $three){
            array_push($kpiChildThreeList, $three->id);
        }

        $KpiChildThree_t = KpiChildThreeTranslation::whereNotIn('kpiChildThree_id', $kpiChildThreeList)->get();

        $childThreeAdds = $keyPeformanceIndicator->kpiChildThrees;

        $languages = Language::all();

        return view(
            'app.key_peformance_indicators.three_chain',
            compact(
                'keyPeformanceIndicator',
                'KpiChildThree_t',
                'childThreeAdds',
                'languages'
            )
        );
    }
    public function kpiChainThreeStore(Request $request){
        // dd($request);

        $data = $request->input();
        $keyPeformanceIndicator = $data['kpiId'];
        $childThreeLists = $data['kpiThreeLists'];

        foreach($childThreeLists as $childThreeList){
            $kpiChildThree = DB::insert('insert into key_peformance_indicator_kpi_child_three (kpi_child_three_id, key_peformance_indicator_id) values (?, ?)', [$childThreeList, $keyPeformanceIndicator]);
        }

        $search = $request->get('search', '');
        $keyPeformanceIndicators = KeyPeformanceIndicatorT::search($search)
            ->latest()
            ->paginate(15)
            ->withQueryString();
             return redirect()->back()->with(
            [
                'keyPeformanceIndicator' => $keyPeformanceIndicator,
             ]

        );


    }

    // below code is to be worked later
    public function kpiChainThreeRemove($keyPeformanceIndicator_id, $childThree,
        Request $request
    ) {

        $keyPeformanceIndicator = KeyPeformanceIndicator::find($keyPeformanceIndicator_id);

        $keyPeformanceIndicator->find($keyPeformanceIndicator_id)->kpiChildThrees()->detach($childThree);

        $kpiChildThreeList = [];
        foreach ($keyPeformanceIndicator->kpiChildThrees as $threes){
            array_push($kpiChildThreeList, $threes->id);
        }

        $KpiChildThree = KpiChildThreeTranslation::whereNotIn('kpiChildThree_id', $kpiChildThreeList)->get();

        $childThreeAdds = $keyPeformanceIndicator->kpiChildThrees;

        return redirect()->back()->with(
            [
                'keyPeformanceIndicator' => $keyPeformanceIndicator,
                'KpiChildThree' => $KpiChildThree,
                'childThreeAdds' => $childThreeAdds
            ]

        );

    }

}
