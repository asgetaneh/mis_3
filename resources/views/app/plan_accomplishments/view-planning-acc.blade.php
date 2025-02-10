@extends('layouts.app')
@section('title', 'View Plan')

@section('style')
    <style>
        tr,
        th,
        td {
            padding: 10px;
            border: 1px solid #289CD8;

        }

        table {
            border-collapse: collapse;
            border: 1px solid #289CD8;
        }

    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@section('content')
    @php
        $first=1;
        $userOffice = auth()->user()->offices[0];
        $loggedUser = auth()->user();
        $allOffices = getAllOffices();
        $allKpis = getAllKpi();
    @endphp
    <div class="justify-content-center">
        <div class="card card-primary card-outline card-outline-tabs fillable-objective">
            <div class="card-body">
                <form role="form" class="form-horizontal" method="get" action="{{ route('view-plan-accomplishment') }}">
                    <div class="row">
                        <div class="col-md-4">

                            {{-- If admin put all offices --}}
                            @if ($loggedUser->is_admin || $loggedUser->hasRole('super-admin'))
                                <label class="label" for="filters">Offices:</label>
                                <select class="form-control select2" name="office">
                                    <option disabled {{ old('office') == '' ? 'selected' : '' }} value="">Select
                                        Office</option>
                                    {{-- <option value="">ALL OFFICES</option> --}}
                                    @forelse($allOffices as $office)
                                        @if ($office->id == 1)
                                            @continue
                                        @endif
                                        <option {{ old('office') == $office->id ? 'selected' : '' }}
                                            value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            @else
                                {{-- If normal office, check and only avail the belonged offices --}}
                                <label class="label" for="filters">Offices:</label>
                                <select class="form-control select2" name="office">
                                    <option disabled {{ old('office') == '' ? 'selected' : '' }} value="">Select
                                        Office</option>
                                    {{-- <option value="">ALL OFFICES</option> --}}
                                    @forelse($allOffices as $office)
                                        @if ($office->parent_office_id == $userOffice->id || $office->id == $userOffice->id)
                                            <option {{ old('office') == $office->id ? 'selected' : '' }}
                                                value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
                                            </option>
                                        @endif
                                    @empty
                                    @endforelse
                                </select>
                            @endif

                        </div>
                        <div class="col-md-4">
                            <label class=" " for="filters">KPI:</label>
                            <select class="form-control select2" name="kpi">
                                <option disabled {{ old('kpi') == '' ? 'selected' : '' }} value="">Select KPI</option>
                                {{-- <option value="">ALL KPI</option> --}}
                                @forelse($allKpis as $kpi)
                                    <option {{ old('kpi') == $kpi->id ? 'selected' : '' }} value="{{ $kpi->id }}">
                                        {{ $kpi->keyPeformanceIndicatorTs[0]->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>

                        </div>
                        <div class="col-md-4">
                            <label class="label" for="action">Action</label>
                            <div id="action">
                                <button class="btn btn-primary" value="search" name="search" type="submit">Filter</button>
                                <span class="border border-right mx-3"></span>
                                <button class="btn btn-info" value="pdf" name="pdf" type="submit">PDF</button>
                                {{-- <button class="btn btn-success" value="excel" name="excel" type="submit">Excel</button> --}}
                                {{-- <button class="btn btn-primary" value="word" name="word" type="submit">Word</button> --}}
                                <button onclick="exportTableToExcel()">Export to Excel</button>
                                 {{-- <a href="{{ url('/export-excel') }}" class="btn btn-success">Export to Excel</a> --}}
                                <button onclick="window.print()">Print2</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>




    <div class="row justify-content-center"   id="yourTableId">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body" >
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @php
                            $kpi_repeat[0] = null;
                            $c = 1;
                            $objective_array = [];
                        @endphp
                        @php
                            $planAccomplishment = new App\Models\PlanAccomplishment();
                            $offices = $planAccomplishment->getOfficeFromKpiAndOfficeList($only_child_array, $off_level);
                        @endphp
                        @forelse($planAccomplishments as $planAcc)

                            @php $planAccKpi = $planAcc->Kpi; @endphp
                            @php $planAccKpiChildOne = $planAccKpi->kpiChildOnes; @endphp
                            @php $planAccKpiChildTwo = $planAccKpi->kpiChildTwos; @endphp
                            @php $planAccKpiChildThree = $planAccKpi->kpiChildThrees; @endphp
                            @php $planAccKpiReportingPeriodType = $planAccKpi->reportingPeriodType; @endphp
                            @php $getQuarter = getQuarter($planAccKpiReportingPeriodType->id); @endphp

                            @if (!in_array($planAccKpi->id, $kpi_repeat))
                                <div class="card collapsed-card p-2" >
                                    <div class="card-header" >
                                        <table class="table">
                                            <tr style="background:#87cdc6;">
                                                @forelse($planAccKpi->KeyPeformanceIndicatorTs as $kpiT)
                                                    @if (app()->getLocale() == $kpiT->locale)
                                                        @if (!in_array($planAccKpi->objective->id, $objective_array))
                                                            <th colspan="9" style="width:100%;"> Objective:
                                                                {{ $planAccKpi->objective->objectiveTranslations[0]->name }}
                                                            </th>
                                                        @endif
                                                    @endif
                                                @empty
                                                    <h4>No KPI name!</h4>
                                                @endforelse
                                                @php
                                                    $objective_array = array_merge($objective_array, [
                                                        $planAccKpi->objective->id,
                                                    ]);
                                                @endphp
                                            </tr>
                                            <tr style="background:#21212121;">
                                                <th rowspan="2"></th>
                                                <th rowspan="2" style="width:30%">
                                                    KPI: {{ $kpiT->name }}
                                                    @if ($kpiT->keyPeformanceIndicator->measurement)
                                                        {{ '( in ' }}{{ $kpiT->keyPeformanceIndicator->measurement['slug'] }}
                                                        {{ ')' }}
                                                    @endif
                                                </th>
                                                <th rowspan="">{{ 'Baseline' }}</th>
                                                @forelse($getQuarter as $period)
                                                    <th> {{ $period->reportingPeriodTs[0]->name }} </th>
                                                @empty
                                                @endforelse
                                                <th> Action </th>
                                            </tr>
                                            <tr>
                                                @php
                                                    $baseline_avarage_total = 0;
                                                    $baseline_denominator = 1;
                                                    if (!$planAcc->kpi_child_three_id == null) {
                                                        foreach ($planAccKpiChildThree as $key => $value3) {
                                                            foreach ($planAccKpiChildTwo as $key => $value2) {
                                                                foreach (
                                                                    $planAccKpiChildOne
                                                                    as $key => $value1
                                                                ) {
                                                                    $baseline_avarage = planBaseline(
                                                                        $planAccKpi->id,
                                                                        $imagen_off,
                                                                        $planning_year->id,
                                                                        $period->id,
                                                                        $value1->id,
                                                                        $value2->id,
                                                                        $value3->id,
                                                                    );
                                                                    //$baseline_avarage = $planAcc->KpiOTT($planAccKpi->id, $userOffice, $period->id, false, $planning_year->id, $value1->id,$value2->id,$value3->id);
                                                                    $baseline_avarage_total =
                                                                        $baseline_avarage_total + $baseline_avarage;
                                                                }
                                                            }
                                                        }
                                                        $denominator =
                                                            $planAccKpiChildOne->count() *
                                                            $planAccKpiChildTwo->count() *
                                                            $planAccKpiChildThree->count();
                                                    } elseif (!$planAcc->kpi_child_two_id == null) {
                                                        foreach ($planAccKpiChildTwo as $key => $value2) {
                                                            foreach ($planAccKpiChildOne as $key => $value1) {
                                                                $baseline_avarage = planBaseline(
                                                                    $planAccKpi->id,
                                                                    $imagen_off,
                                                                    $planning_year->id,
                                                                    $period->id,
                                                                    $value1->id,
                                                                    $value2->id,
                                                                    null,
                                                                );
                                                                $baseline_avarage_total =
                                                                    $baseline_avarage_total + $baseline_avarage;
                                                            }
                                                        }
                                                        $denominator =
                                                            $planAccKpiChildOne->count() *
                                                            $planAccKpiChildTwo->count();
                                                    } elseif (!$planAcc->kpi_child_one_id == null) {
                                                        foreach ($planAccKpiChildOne as $key => $value1) {
                                                            $baseline_avarage = planBaseline(
                                                                $planAccKpi->id,
                                                                $imagen_off,
                                                                $planning_year->id,
                                                                $period->id,
                                                                $value1->id,
                                                                null,
                                                                null,
                                                            );
                                                            $baseline_avarage_total =
                                                                $baseline_avarage_total + $baseline_avarage;
                                                        }
                                                        $denominator = $planAccKpiChildOne->count();
                                                    } else {
                                                        $baseline_avarage = planBaseline(
                                                            $planAccKpi->id,
                                                            $imagen_off,
                                                            $planning_year->id,
                                                            $period->id,
                                                            null,
                                                            null,
                                                            null,
                                                        );
                                                        $baseline_avarage_total =
                                                            $baseline_avarage_total + $baseline_avarage;
                                                        $denominator = 1;
                                                    }
                                                    //$baselineOfOfficePlan  = planBaseline($planAccKpi->id,$imagen_off, $planning_year->id, $period->id,$planAcc->kpi_child_one_id,$planAcc->kpi_child_two_id,$planAcc->kpi_child_three_id);
                                                @endphp
                                                <td>
                                                    @if ($planAccKpi->measurement && $planAccKpi->measurement->slug == 'percent' && $denominator > 0)
                                                        @php
                                                            $final_avarage_basline = number_format(
                                                                $baseline_avarage_total / $denominator,
                                                                2,
                                                                '.',
                                                                ',',
                                                            );
                                                        @endphp
                                                        {{ $final_avarage_basline }} {{ ' %' }}
                                                    @else
                                                        {{ $baseline_avarage_total }}
                                                    @endif
                                                </td>

                                                {{-- TODO: no calc --}}
                                                @forelse($getQuarter as $period)
                                                    @php
                                                        $avarage = 0;
                                                        $avarage_total = 0;
                                                        $denominator = 1;
                                                        if (!$planAcc->kpi_child_three_id == null) {
                                                            foreach ($planAccKpiChildThree as $key => $value3) {
                                                                foreach (
                                                                    $planAccKpiChildTwo
                                                                    as $key => $value2
                                                                ) {
                                                                    foreach (
                                                                        $planAccKpiChildOne
                                                                        as $key => $value1
                                                                    ) {
                                                                        $avarage = $planAcc->KpiOTT(
                                                                            $planAccKpi->id,
                                                                            $userOffice,
                                                                            $period->id,
                                                                            false,
                                                                            $planning_year->id,
                                                                            $value1->id,
                                                                            $value2->id,
                                                                            $value3->id,
                                                                        );
                                                                        $avarage_total = $avarage_total + $avarage[0];
                                                                    }
                                                                }
                                                            }
                                                            $denominator =
                                                                $planAccKpiChildOne->count() *
                                                                $planAccKpiChildTwo->count() *
                                                                $planAccKpiChildThree->count();
                                                        } elseif (!$planAcc->kpi_child_two_id == null) {
                                                            foreach ($planAccKpiChildTwo as $key => $value2) {
                                                                foreach (
                                                                    $planAccKpiChildOne
                                                                    as $key => $value1
                                                                ) {
                                                                    $avarage = $planAcc->KpiOTT(
                                                                        $planAccKpi->id,
                                                                        $userOffice,
                                                                        $period->id,
                                                                        false,
                                                                        $planning_year->id,
                                                                        $value1->id,
                                                                        $value2->id,
                                                                        null,
                                                                    );
                                                                    $avarage_total = $avarage_total + $avarage[0];
                                                                }
                                                            }
                                                            $denominator =
                                                                $planAccKpiChildOne->count() *
                                                                $planAccKpiChildTwo->count();
                                                        } elseif (!$planAcc->kpi_child_one_id == null) {
                                                            foreach ($planAccKpiChildOne as $key => $value1) {
                                                                $avarage = $planAcc->KpiOTT(
                                                                    $planAccKpi->id,
                                                                    $userOffice,
                                                                    $period->id,
                                                                    false,
                                                                    $planning_year->id,
                                                                    $value1->id,
                                                                    null,
                                                                    null,
                                                                );
                                                                $avarage_total = $avarage_total + $avarage[0];
                                                            }
                                                            $denominator = $planAccKpiChildOne->count();
                                                        } else {
                                                            $avarage = $planAcc->KpiOTT(
                                                                $planAccKpi->id,
                                                                $userOffice,
                                                                $period->id,
                                                                false,
                                                                $planning_year->id,
                                                                null,
                                                                null,
                                                                null,
                                                            );
                                                            $avarage_total = $avarage_total + $avarage[0];
                                                            $denominator = 1;
                                                        }
                                                        //$planOfOfficePlan = $planAcc->ForKpi($planAccKpi->id, $imagen_off, $period->id,false,$planning_year->id ?? NULL,$planAcc->kpi_child_one_id,$planAcc->kpi_child_two_id,$planAcc->kpi_child_three_id);
                                                        $office_level = $userOffice->level;
                                                        if ($office_level == 0) {
                                                            $office_level = 1;
                                                        }
                                                    @endphp
                                                    <td>
                                                        @if ($planAccKpi->measurement && $planAccKpi->measurement->slug == 'percent' && $denominator > 0)
                                                            @php
                                                                $final_avarage = number_format(
                                                                    $avarage_total / $denominator,
                                                                    2,
                                                                    '.',
                                                                    ',',
                                                                );
                                                            @endphp
                                                            {{ $final_avarage }} {{ ' %' }}
                                                        @else
                                                            {{ $avarage_total }}
                                                            <!-- {{ $planAcc->sum }} -->
                                                        @endif
                                                    </td>
                                                @empty
                                                @endforelse
                                                <th>
                                                    <button type="button" title="Expand"
                                                        class="btn btn-flat btn-tool bg-primary m-auto py-2 px-4"
                                                        data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>
                                                    <h6> <b> Major Activities </b> </h6>
                                                </th>
                                                @php
                                                    $narration = $planAcc->getNarration(
                                                    $planAccKpi->id,
                                                    $planning_year->id ?? null,
                                                    $imagen_off,
                                                );
                                                @endphp
                                                <td colspan="8">
                                                     @foreach ($narration as $key => $plannaration)
                                                        <p>
                                                            {!! html_entity_decode($plannaration->plan_naration) !!}
                                                        </p>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="card-body" style="display: none;">
                                        {{-- If KPI has Child ones (UG, PG) --}}
                                        {{-- <x-form method="POST" action="{{ route('approve-plan') }}" class="mt-4"> --}}

                                            @forelse($offices  as $office)
                                                @php
                                                    // $only_child_array = office_all_childs_ids($office);
                                                    $isOfficeBelongToKpi = isOfficeBelongToKpi(
                                                        $office,
                                                        $planAccKpi->id,
                                                    );
                                                    $officeOffices = $office->offices;
                                                    @endphp
                                                @if ($isOfficeBelongToKpi->count() > 0)
                                                    @if (!$planAccKpiChildOne->isEmpty())
                                                        {{-- <table class="table table-bordered">
                                                            <thead> --}}

                                                        @if (!$planAccKpiChildTwo->isEmpty())
                                                            @if (!$planAccKpiChildThree->isEmpty())
                                                                @include('app.plan_accomplishments.view-kpi123')
                                                                {{-- KPI has  child one and child two --}}
                                                                 {{-- <a  href='{{ route('plan-accomplishment-details-two', [$office->id,$planAcc->Kpi->id, $planning_year->id]) }}'> {{"."}}</a> --}}
                                                                 {{-- @include('app.plan_accomplishments.office-row123', ['office' => $office,'kpi' => $planAcc->Kpi->id, $planning_year->id,'level' => 0]) --}}
                                                            @else
                                                                {{-- @include('app.plan_accomplishments.view-kpi12') --}}
                                                                 <a  href='{{ route('plan-accomplishment-details-two', [$office->id,$planAccKpi->id, $planning_year->id]) }}'> {{"."}}</a>
                                                                 @include('app.plan_accomplishments.office-row12', ['office' => $office,'kpi' => $planAccKpi->id, $planning_year->id,'level' => 0])
                                                            @endif
                                                            {{-- KPI has  child one only --}}
                                                        @else
                                                            {{-- @include('app.plan_accomplishments.view-kpi1') --}}
                                                            <a  href='{{ route('plan-accomplishment-details', [$office->id,$planAccKpi->id, $planning_year->id]) }}'> {{"."}}</a>
                                                            @include('app.plan_accomplishments.office-row1', ['office' => $office,'planAcc' => $planAcc, 'level' => 0])
                                                        @endif

                                                        {{-- </thead>
                                                        </table> --}}
                                                        {{-- KPI has no child one, which means just only plain input --}}
                                                    @else
                                                     <a href='{{ route('plan-accomplishment-details', [$office->id,$planAccKpi->id, $planning_year->id]) }}'>  {{"."}} </a>
                                                        @include('app.plan_accomplishments.office-row', ['office' => $office,'planAcc' => $planAcc, 'level' => 0])
                                                    @endif
                                                @endif
                                            @empty
                                                <h4>No offices!</h4>
                                            @endforelse
                                            <tr>
                                                {{-- <td colspan="8">
                                            <button type="submit" class="btn-primary float-right">Appove</button>
                                        </td> --}}
                                            </tr>
                                        {{-- </x-form> --}}
                                    </div>
                                </div>

                                @php
                                    $kpi_repeat[$c] = $planAccKpi->id;
                                    $c++;

                                @endphp
                            @endif
                        @empty
                            {{-- <p>ugyftrdy</p> --}}
                        @endforelse

                        <div class="mt-4">
                            {{ $planAccomplishments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- @php
            $user_offices = $userOffice->offices;
        @endphp --}}

    </div>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.select2').select2();

        });
    </script>
    <script>
function exportTableToExcel() {
    // Get the table element by its ID
    const table = document.getElementById("yourTableId");

    // Convert the HTML table to a workbook
    const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });

    // Export the workbook to a file
    XLSX.writeFile(workbook, "table_data.xlsx");
}
</script>


    <script>
        function expandAll() {

        }
    </script>

@endsection
