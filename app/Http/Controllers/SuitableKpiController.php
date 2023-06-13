<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\View\View;
use App\Models\SuitableKpi;
use App\Models\PlaningYear;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\KeyPeformanceIndicator;
use App\Http\Requests\SuitableKpiStoreRequest;
use App\Http\Requests\SuitableKpiUpdateRequest;
use Illuminate\Support\Facades\DB;
 
class SuitableKpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', SuitableKpi::class);
       
        $search = $request->get('search', '');

        $suitableKpis = SuitableKpi::search($search)
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view(
            'app.suitable_kpis.index',
            compact('suitableKpis', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', SuitableKpi::class);
        $user = auth()->user();
        $user_managing_office = $user->offices;
        $search = $request->get('search', '');
        $planingYears = PlaningYear::search($search)
            ->latest()->paginate(50)->withQueryString();

        return view(
            'app.suitable_kpis.create',
            compact('user_managing_office', 'planingYears')
        );
    }
     public function officeSuitableKpi(Request $request, $recover_request =null){
        $data = $request->input();
        $m_office = auth()->user()->offices[0]->id;
        $planyear = PlaningYear::select('id')->orderBy('id','desc')->first();
        $planyear= $planyear->id;
         if (isset($_POST['user_managing_office'])) {
             $m_office = $data['user_managing_office'];
              $planyear = $data['planing_year_id'];
        }
        $search = $request->get('search', '');
       
         $suitableKpis = SuitableKpi::where('office_id' , '=', $m_office)->where('planing_year_id' , '=', $planyear)    ->paginate(5);
          if ($recover_request =="1" ||$suitableKpis->isEmpty() ) { 
           $offfice = Office::find($m_office);
            $kpis = $offfice->keyPeformanceIndicators;//dd($kpis);
            $planyear = PlaningYear::find($planyear);
            $m_office = Office::find($m_office);
            return view(
                'app.suitable_kpis.list_kpi',
                compact('kpis', 'planyear','m_office', 'search')
            );
           
            
         }
         else{
            return view(
                'app.suitable_kpis.index',
                compact('suitableKpis', 'planyear','m_office', 'search')
            );
         } 

    }
    public function selectOfficeSuitableKpi(Request $request){
        $data = $request->input();
         $search = $request->get('search', '');
        $Kpi = $data['kpi'];
        $office = $data['office'];
        
         
        if($data['nonsuitable']){
             foreach ($Kpi as $key => $value) {
                $suitable_Kpi = SuitableKpi::where('key_peformance_indicator_id' , '=', $value)->where('office_id' , '=', $office);
                 $suitable_Kpi-> delete($suitable_Kpi);

             }
             return redirect()->to('suitable-kpis');
        }
        else{//dd($data);
            foreach ($Kpi as $key => $value){
                $suitnew = new SuitableKpi;
                $suitnew->key_peformance_indicator_id = $value;
                $suitnew-> office_id = $data['office'];
                $suitnew-> planing_year_id = $data['planyear'];
                $suitnew->save();
            }
            $m_office = $data['office'];
            $planyear = $data['planyear'];
            $suitableKpis = SuitableKpi::where('office_id' , '=', $m_office)->where('planing_year_id' , '=', $planyear)    ->paginate(5);
             return view(
                'app.suitable_kpis.index',
                compact('suitableKpis', 'planyear','m_office', 'search')
            );
        }
       $suitableKpis = SuitableKpi::all();
         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuitableKpiStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', SuitableKpi::class);

        $validated = $request->validated();

        $suitableKpi = SuitableKpi::create($validated);

        return redirect()
            ->route('suitable-kpis.edit', $suitableKpi)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, SuitableKpi $suitableKpi): View
    {
        $this->authorize('view', $suitableKpi);

        return view('app.suitable_kpis.show', compact('suitableKpi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, SuitableKpi $suitableKpi): View
    {
        $this->authorize('update', $suitableKpi);

        $keyPeformanceIndicators = KeyPeformanceIndicator::pluck('id', 'id');
        $offices = Office::pluck('id', 'id');
        $planingYears = PlaningYear::pluck('id', 'id');

        return view(
            'app.suitable_kpis.edit',
            compact(
                'suitableKpi',
                'keyPeformanceIndicators',
                'offices',
                'planingYears'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        SuitableKpiUpdateRequest $request,
        SuitableKpi $suitableKpi
    ): RedirectResponse {
        $this->authorize('update', $suitableKpi);

        $validated = $request->validated();

        $suitableKpi->update($validated);

        return redirect()
            ->route('suitable-kpis.edit', $suitableKpi)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        SuitableKpi $suitableKpi
    ): RedirectResponse {
        $this->authorize('delete', $suitableKpi);

        $suitableKpi->delete();

        return redirect()
            ->route('suitable-kpis.index')
            ->withSuccess(__('crud.common.removed'));
    }
   

}
