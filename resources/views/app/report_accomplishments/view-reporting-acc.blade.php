@extends('layouts.app')
@section('title', 'View Report')

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
    <script src="{{ asset('assets/dist/js/xlsx.full.min.js') }}"></script>
@endsection

@section('content')
    @php
        $first=1;
        $userOffice = auth()->user()->offices[0];
        $loggedUser = auth()->user();
        $allOffices = getAllOffices();
        $allKpis = getAllKpi();
    @endphp
    <div class="justify-content-center">
        @if ($planAccomplishments->count() == 0)
            <div class="callout callout-warning">
                <i class="icon fas fa-info-circle mr-2"></i>
                No report record found!
            </div>
        @else
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                    <form role="form" class="form-horizontal" method="get"
                        action="{{ route('view-report-accomplishment') }}">
                        <div class="row">
                            <div class="col-md-4">

                                {{-- If admin put all offices --}}
                                @if($loggedUser->is_admin || $loggedUser->hasRole('super-admin'))
                                    <label class="label" for="filters">Offices:</label>
                                    <select class="form-control select2" name="office">
                                        <option disabled {{ old('office') == "" ? 'selected' : '' }} value="">Select Office</option>
                                        {{-- <option value="">ALL OFFICES</option> --}}
                                        @forelse($allOffices as $office)
                                            @if ($office->id == 1)
                                                @continue
                                            @endif
                                            <option {{ old('office') == $office->id ? 'selected' : '' }} value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                @else
                                    {{-- If normal office, check and only avail the belonged offices --}}
                                    <label class="label" for="filters">Offices:</label>
                                    <select class="form-control select2" name="office">
                                        <option disabled {{ old('office') == "" ? 'selected' : '' }} value="">Select Office</option>
                                        {{-- <option value="">ALL OFFICES</option> --}}
                                        @forelse($allOffices as $office)
                                            @if ($office->parent_office_id == $userOffice->id || $office->id == $userOffice->id)
                                                <option {{ old('office') == $office->id ? 'selected' : '' }} value="{{ $office->id }}">{{ $office->officeTranslations[0]->name }}
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
                                    <option disabled {{ old('kpi') == "" ? 'selected' : '' }} value="">Select KPI</option>
                                    {{-- <option value="">ALL KPI</option> --}}
                                    @forelse($allKpis as $kpi)
                                        <option {{ old('kpi') == $kpi->id ? 'selected' : '' }} value="{{ $kpi->id }}">{{ $kpi->keyPeformanceIndicatorTs[0]->name }}
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
                                    <button id="exportBtn">Export to Excel</button>
                                    {{-- <button class="btn btn-success" value="excel" name="excel" type="submit">Excel</button> --}}
                                    {{-- <button class="btn btn-primary" value="word" name="word" type="submit">Word</button> --}}
                                    <button class="btn btn-outline-info" onclick="exportTableToExcel()">Export to Excel</button>
                                    <button class="btn btn-outline-info" onclick="window.print()">Print</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>




    <div class="row justify-content-center" id="yourTableId">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body"  id="tobeprintable">
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
                                @php $getReportingQuarter = getReportingQuarter($planAcc->Kpi->reportingPeriodType->id); @endphp

                                @if (!in_array($planAccKpi->id, $kpi_repeat))
                                    <div class="card collapsed-card p-2">
                                        <div class="card-header">
                                            @forelse($planAccKpi->KeyPeformanceIndicatorTs as $kpiT)
                                                @if (app()->getLocale() == $kpiT->locale)
                                                    <table class="table">
                                                        <tr style="background:#87cdc6;">
                                                            @if (!in_array($planAccKpi->objective->id, $objective_array))
                                                                <th colspan="8" style="width:100%;"> Objective:
                                                                    {{ $planAccKpi->objective->objectiveTranslations[0]->name }}
                                                                </th>
                                                            {{-- @forelse($getQuarter as $period)
                                                                    <th> {{ $period->reportingPeriodTs[0]->name }}
                                                                    </th>
                                                                @empty
                                                                @endforelse --}}
                                                                {{-- <th>   </th> --}}
                                                            @endif
                                                            @php
                                                                $objective_array = array_merge($objective_array, [$planAccKpi->objective->id]);
                                                            @endphp
                                                        </tr>
                                                        <tr style="background:#21212121;">
                                                            <th  rowspan="2"></th>
                                                            <td  rowspan="2" style="width:30%">
                                                                KPI:   {{$kpiT->name }}
                                                                @if ($kpiT->keyPeformanceIndicator ->measurement)
                                                                <b> {{"( in "}}{{$kpiT->keyPeformanceIndicator ->measurement['slug'] }} {{")" }}</b>
                                                                @endif
                                                            </td>
                                                            @forelse($getQuarter as $period)
                                                                <th> {{ $period->reportingPeriodTs[0]->name }} </th>
                                                            @empty
                                                            @endforelse
                                                            <th> Action  </th>
                                                        </tr>
                                                        <tr>
                                                            @forelse($getQuarter as $period)
                                                                @php
                                                                    $one =[];
                                                                    $three =[];
                                                                    $two =[];
                                                                    $report_avarage = 0;
                                                                    $report_avarage_total = 0;
                                                                    $denominator = 1;
                                                                    $kpi_child_one = $planAccKpi->kpiChildOnes;
                                                                    $kpi_child_twos = $planAccKpi->kpiChildTwos;
                                                                    $kpi_child_threes = $planAccKpi->kpiChildThrees;
                                                                    $display = 0;
                                                                    if($planAccKpi->measurement && $planAccKpi->measurement->slug == 'percent'){
                                                                        if(!$kpi_child_threes->isEmpty()){
                                                                            if(!$kpi_child_twos->isEmpty()){
                                                                                if(!$kpi_child_one->isEmpty()){
                                                                                    foreach ($kpi_child_threes as $key => $three) {
                                                                                        foreach ($kpi_child_twos as $key => $two) {
                                                                                            foreach ($kpi_child_one as $key => $one) {
                                                                                                $planOfOfficePlan = $planAcc->ForKpi($planAccKpi->id, $imagen_off, $period->id,true,$planning_year->id ?? NULL,$one->id ,$two->id ,$three->id);
                                                                                                $report_avarage = $report_avarage + $planOfOfficePlan[1];
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    $denominator =$kpi_child_threes->count() *$kpi_child_twos->count() *$kpi_child_one->count();
                                                                                }
                                                                            }
                                                                        }elseif(!$kpi_child_twos->isEmpty()){
                                                                            if(!$kpi_child_one->isEmpty()){
                                                                                foreach ($kpi_child_twos as $key => $two) {
                                                                                    foreach ($kpi_child_one as $key => $one) {
                                                                                        $planOfOfficePlan = $planAcc->ForKpi($planAccKpi->id, $imagen_off, $period->id,true,$planning_year->id ?? NULL,$one->id ,$two->id ,null);
                                                                                        $report_avarage = $report_avarage + $planOfOfficePlan[1];
                                                                                    }
                                                                                }
                                                                                $denominator =$kpi_child_twos->count() *$kpi_child_one->count();
                                                                            }
                                                                        }elseif(!$kpi_child_one->isEmpty()){
                                                                            foreach ($kpi_child_one as $key => $one) {
                                                                                $planOfOfficePlan = $planAcc->ForKpi($planAccKpi->id, $imagen_off, $period->id,true,$planning_year->id ?? NULL,$one->id ,null ,null);
                                                                                $report_avarage = $report_avarage + $planOfOfficePlan[1];
                                                                            }
                                                                                $denominator = $kpi_child_one->count();
                                                                        }else{
                                                                            $planOfOfficePlan = $planAcc->ForKpi($planAccKpi->id, $imagen_off, $period->id,true,$planning_year->id ?? NULL,null ,null ,null);
                                                                            $report_avarage = $planOfOfficePlan[1];
                                                                            $denominator = 1;
                                                                        }//dump($planOfOfficePlan[2]);
                                                                        if(auth()->user()->offices[0]->level == $planOfOfficePlan[2]){
                                                                            $report_avarage_total = $report_avarage / $denominator;
                                                                            $display = $report_avarage_total.'%';
                                                                        }

                                                                  }else{

                                                                        foreach ($kpi_child_one as $key => $kpi_child_onee) {
                                                                            $one = array_merge($one,array($kpi_child_onee->id));
                                                                        }
                                                                        foreach ($kpi_child_twos as $key => $kpi_child_two) {
                                                                            $two = array_merge($two,array($kpi_child_two->id));
                                                                        }
                                                                        foreach ($kpi_child_threes as $key => $kpi_child_three) {
                                                                            $three = array_merge($three,array($kpi_child_three->id));
                                                                        }
                                                                        $planOfOfficePlan = $planAcc->ForKpiTotalOnKpi($planAccKpi->id, $imagen_off, $period->id,true,$planning_year->id ?? NULL,$one ,$two ,$three);
                                                                        $display = $planOfOfficePlan[1];
                                                                    }
                                                                    $narration = $planAcc->getReportNarration($planAccKpi->id, $planning_year->id ?? NULL, $imagen_off, $period->id);
                                                                    $activeQuarter = getReportingQuarter($planAccKpiReportingPeriodType->id);
                                                                    //dump($planAccKpi->id);
                                                                @endphp
                                                                @forelse($activeQuarter as $aQ)
                                                                @if($period->id!= $aQ->id)
                                                                <td>
                                                                    {{ $display }}
                                                                </td>
                                                                @else
                                                                <td style="background:#99cd99;">
                                                                <span >  {{ $display }}</span>
                                                                </td>
                                                                @endif
                                                                @empty
                                                                @endforelse
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

                                                            <td colspan="7">
                                                            <h6>
                                                                Major Activities
                                                            </h6>
                                                                @foreach ($narration as $key => $plannaration)
                                                                    <p>
                                                                        {!! html_entity_decode($plannaration->report_naration) !!}
                                                                    </p>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    </table>
                                                @endif
                                            @empty
                                                <h4>No KPI name!</h4>
                                            @endforelse
                                        </div>
                                        <div class="card-body" style="display: none;">
                                            {{-- If KPI has Child ones (UG, PG) --}}
                                            <x-form method="POST" action="{{ route('approve-plan') }}" class="mt-4">

                                                @forelse($offices  as $office)
                                                    @php
                                                        // $only_child_array = office_all_childs_ids($office);
                                                        $isOfficeBelongToKpi = isOfficeBelongToKpi($office, $planAccKpi->id);
                                                        $officeOffices = $office->offices;
                                                    @endphp

                                                    @if ($isOfficeBelongToKpi->count() > 0)
                                                        @if (!$planAccKpi->kpiChildOnes->isEmpty())
                                                            {{-- <table class="table table-bordered">
                                                                <thead> --}}
                                                                    @if (!$planAccKpiChildTwo->isEmpty())
                                                                        @if (!$planAccKpiChildThree->isEmpty())
                                                                            @include('app.report_accomplishments.view-kpi123')
                                                                            {{-- KPI has  child one and child two --}}
                                                                        @else
                                                                            @include('app.report_accomplishments.view-kpi12')
                                                                        @endif
                                                                        {{-- KPI has  child one only --}}
                                                                    @else
                                                                        @include('app.report_accomplishments.view-kpi1')
                                                                    @endif

                                                                {{-- </thead>
                                                            </table> --}}
                                                            {{-- KPI has no child one, which means just only plain input --}}
                                                        @else
                                                            @include('app.report_accomplishments.view-kpi')
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
                                            </x-form>
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

    </div>
    <script>
        document.getElementById('exportBtn').addEventListener('click', function() {
            var table = document.getElementById('tobeprintable');
            var wb = XLSX.utils.table_to_book(table, { sheet: "Sheet 1" });

            // Save the Excel file with a specified name
            XLSX.writeFile(wb, 'users_data.xlsx');
        });
    </script>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.select2').select2();

        });
    </script>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}

    {{-- <script>
        $(document).ready(function() {
            $('#goal-card').on('click', '.goal-list', function() {
                var goalId = $(this).attr('data-id');

                var url = "{{ route('get-objectives', [':id']) }}";
                url = url.replace(':id', goalId);

                $('.fillable-objective').empty();

                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(response) {

                        // foreach(response as r){
                        //     console.log(r)
                        // }
                        console.log(response);
                        $('.fillable-objective').html(response);
                    }
                });

            })

        })
    </script> --}}

    <script>
        function expandAll() {

        }
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

@endsection
