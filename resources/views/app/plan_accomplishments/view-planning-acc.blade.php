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
@endsection

@section('content')
    @php     $first=1; @endphp
    <div class="justify-content-center">
        <div class="card card-primary card-outline card-outline-tabs fillable-objective">
            <div class="card-body">
                <form role="form" class="form-horizontal" method="get" action="{{ route('view-plan-accomplishment') }}">
                    <div class="row">
                        <div class="col-md-4">

                            {{-- If admin put all offices --}}
                            @if (auth()->user()->is_admin || auth()->user()->hasRole('super-admin'))
                                <label class="label" for="filters">Offices:</label>
                                <select class="form-control select2" name="office">
                                    <option disabled {{ old('office') == '' ? 'selected' : '' }} value="">Select
                                        Office</option>
                                    <option value="">ALL OFFICES</option>
                                    @forelse(getAllOffices() as $office)
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
                                    <option value="">ALL OFFICES</option>
                                    @forelse(getAllOffices() as $office)
                                        @if ($office->parent_office_id == auth()->user()->offices[0]->id || $office->id == auth()->user()->offices[0]->id)
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
                                <option value="">ALL KPI</option>
                                @forelse(getAllKpi() as $kpi)
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
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>




    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @php
                            $kpi_repeat[0] = null;
                            $c = 1;
                            $objective_array = [];
                        @endphp
                        @forelse($planAccomplishments as $planAcc)
                            @php
                                $offices = $planAcc->getOfficeFromKpiAndOfficeList($only_child_array, $off_level);
                                //dd($offices[0]->id);
                            @endphp

                            @if (!in_array($planAcc->Kpi->id, $kpi_repeat))
                                <div class="card collapsed-card p-2">
                                    <div class="card-header">
                                        <table class="table">
                                            <tr style="background:#87cdc6;">
                                                @forelse($planAcc->Kpi->KeyPeformanceIndicatorTs as $kpiT)
                                                    @if (app()->getLocale() == $kpiT->locale)
                                                        @if (!in_array($planAcc->Kpi->objective->id, $objective_array))
                                                            <th colspan="9" style="width:100%;"> Objective:
                                                                {{ $planAcc->Kpi->objective->objectiveTranslations[0]->name }}
                                                            </th>
                                                        @endif
                                                    @endif
                                                @empty
                                                    <h4>No KPI name!</h4>
                                                @endforelse
                                                @php
                                                    $objective_array = array_merge($objective_array, [
                                                        $planAcc->Kpi->objective->id,
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
                                                @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
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
                                                        foreach ($planAcc->Kpi->kpiChildThrees as $key => $value3) {
                                                            foreach ($planAcc->Kpi->kpiChildTwos as $key => $value2) {
                                                                foreach (
                                                                    $planAcc->Kpi->kpiChildOnes
                                                                    as $key => $value1
                                                                ) {
                                                                    $baseline_avarage = planBaseline(
                                                                        $planAcc->Kpi->id,
                                                                        $imagen_off,
                                                                        $planning_year->id,
                                                                        $period->id,
                                                                        $value1->id,
                                                                        $value2->id,
                                                                        $value3->id,
                                                                    );
                                                                    //$baseline_avarage = $planAcc->KpiOTT($planAcc->Kpi->id, auth()->user()->offices[0], $period->id, false, $planning_year->id, $value1->id,$value2->id,$value3->id);
                                                                    $baseline_avarage_total =
                                                                        $baseline_avarage_total + $baseline_avarage;
                                                                }
                                                            }
                                                        }
                                                        $denominator =
                                                            $planAcc->Kpi->kpiChildOnes->count() *
                                                            $planAcc->Kpi->kpiChildTwos->count() *
                                                            $planAcc->Kpi->kpiChildThrees->count();
                                                    } elseif (!$planAcc->kpi_child_two_id == null) {
                                                        foreach ($planAcc->Kpi->kpiChildTwos as $key => $value2) {
                                                            foreach ($planAcc->Kpi->kpiChildOnes as $key => $value1) {
                                                                $baseline_avarage = planBaseline(
                                                                    $planAcc->Kpi->id,
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
                                                            $planAcc->Kpi->kpiChildOnes->count() *
                                                            $planAcc->Kpi->kpiChildTwos->count();
                                                    } elseif (!$planAcc->kpi_child_one_id == null) {
                                                        foreach ($planAcc->Kpi->kpiChildOnes as $key => $value1) {
                                                            $baseline_avarage = planBaseline(
                                                                $planAcc->Kpi->id,
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
                                                        $denominator = $planAcc->Kpi->kpiChildOnes->count();
                                                    } else {
                                                        $baseline_avarage = planBaseline(
                                                            $planAcc->Kpi->id,
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
                                                    //$baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$imagen_off, $planning_year->id, $period->id,$planAcc->kpi_child_one_id,$planAcc->kpi_child_two_id,$planAcc->kpi_child_three_id);
                                                @endphp
                                                <td>
                                                    @if ($planAcc->Kpi->measurement && $planAcc->Kpi->measurement->slug == 'percent' && $denominator > 0)
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
                                                @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                                                    @php
                                                        $avarage = 0;
                                                        $avarage_total = 0;
                                                        $denominator = 1;
                                                        if (!$planAcc->kpi_child_three_id == null) {
                                                            foreach ($planAcc->Kpi->kpiChildThrees as $key => $value3) {
                                                                foreach (
                                                                    $planAcc->Kpi->kpiChildTwos
                                                                    as $key => $value2
                                                                ) {
                                                                    foreach (
                                                                        $planAcc->Kpi->kpiChildOnes
                                                                        as $key => $value1
                                                                    ) {
                                                                        $avarage = $planAcc->KpiOTT(
                                                                            $planAcc->Kpi->id,
                                                                            auth()->user()->offices[0],
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
                                                                $planAcc->Kpi->kpiChildOnes->count() *
                                                                $planAcc->Kpi->kpiChildTwos->count() *
                                                                $planAcc->Kpi->kpiChildThrees->count();
                                                        } elseif (!$planAcc->kpi_child_two_id == null) {
                                                            foreach ($planAcc->Kpi->kpiChildTwos as $key => $value2) {
                                                                foreach (
                                                                    $planAcc->Kpi->kpiChildOnes
                                                                    as $key => $value1
                                                                ) {
                                                                    $avarage = $planAcc->KpiOTT(
                                                                        $planAcc->Kpi->id,
                                                                        auth()->user()->offices[0],
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
                                                                $planAcc->Kpi->kpiChildOnes->count() *
                                                                $planAcc->Kpi->kpiChildTwos->count();
                                                        } elseif (!$planAcc->kpi_child_one_id == null) {
                                                            foreach ($planAcc->Kpi->kpiChildOnes as $key => $value1) {
                                                                $avarage = $planAcc->KpiOTT(
                                                                    $planAcc->Kpi->id,
                                                                    auth()->user()->offices[0],
                                                                    $period->id,
                                                                    false,
                                                                    $planning_year->id,
                                                                    $value1->id,
                                                                    null,
                                                                    null,
                                                                );
                                                                $avarage_total = $avarage_total + $avarage[0];
                                                            }
                                                            $denominator = $planAcc->Kpi->kpiChildOnes->count();
                                                        } else {
                                                            $avarage = $planAcc->KpiOTT(
                                                                $planAcc->Kpi->id,
                                                                auth()->user()->offices[0],
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
                                                        //$planOfOfficePlan = $planAcc->ForKpi($planAcc->Kpi->id, $imagen_off, $period->id,false,$planning_year->id ?? NULL,$planAcc->kpi_child_one_id,$planAcc->kpi_child_two_id,$planAcc->kpi_child_three_id);
                                                        $narration = $planAcc->getNarration(
                                                            $planAcc->Kpi->id,
                                                            $planning_year->id ?? null,
                                                            $imagen_off,
                                                        );
                                                        $userOffice = auth()->user()->offices[0];
                                                        $userOffice = auth()->user()->offices[0];
                                                        $office_level = $userOffice->level;
                                                        if ($office_level == 0) {
                                                            $office_level = 1;
                                                        }
                                                    @endphp
                                                    <td>
                                                        @if ($planAcc->Kpi->measurement && $planAcc->Kpi->measurement->slug == 'percent' && $denominator > 0)
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
                                                <td colspan="8">
                                                    <!-- @dump($narration); -->
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
                                                    $isOfficeBelongToKpi = isOfficeBelongToKpi(
                                                        $office,
                                                        $planAcc->Kpi->id,
                                                    );
                                                @endphp
                                                <!-- @dump($office); -->

                                                @if ($isOfficeBelongToKpi->count() > 0)
                                                    @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                                                        {{-- <table class="table table-bordered">
                                                            <thead> --}}
                                                        @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                                                            @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                                                @include('app.plan_accomplishments.view-kpi123')
                                                                {{-- KPI has  child one and child two --}}
                                                            @else
                                                                {{-- @include('app.plan_accomplishments.view-kpi12') --}}
                                                                 <a  href='{{ route('plan-accomplishment-details-two', [$office->id,$planAcc->Kpi->id, $planning_year->id]) }}'> {{"."}}</a>
                                                                 @include('app.plan_accomplishments.office-row12', ['office' => $office,'kpi' => $planAcc->Kpi->id, $planning_year->id,'level' => 0])
                                                            @endif
                                                            {{-- KPI has  child one only --}}
                                                        @else
                                                            {{-- @include('app.plan_accomplishments.view-kpi1') --}}
                                                            <a  href='{{ route('plan-accomplishment-details', [$office->id,$planAcc->Kpi->id, $planning_year->id]) }}'> {{"."}}</a>
                                                            @include('app.plan_accomplishments.office-row1', ['office' => $office, 'level' => 0])
                                                        @endif

                                                        {{-- </thead>
                                                        </table> --}}
                                                        {{-- KPI has no child one, which means just only plain input --}}
                                                    @else
                                                     <a href='{{ route('plan-accomplishment-details', [$office->id,$planAcc->Kpi->id, $planning_year->id]) }}'>  {{"."}} </a>
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
                                    $kpi_repeat[$c] = $planAcc->Kpi->id;
                                    $c++;

                                @endphp
                            @endif
                        @empty
                            {{-- <p>ugyftrdy</p> --}}
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @php
            $user_offices = auth()->user()->offices[0]->offices;
        @endphp 
    
<script>
   {{-- function attachExpandListeners() {
    document.querySelectorAll('.btn-expand').forEach(button => {
        button.addEventListener('click', function () {
            const officeId = this.getAttribute('data-id');
            let url = this.getAttribute('data-url');

            if (!url) {
                // Dynamically construct URL if `data-url` is missing
                url = `/smis/setting/offices/${officeId}/details`;
            }

            console.log('Office ID:', officeId);
            console.log('Fetch URL:', url);

            const detailsRow = document.getElementById(`details-${officeId}`);

            if (detailsRow.style.display === 'none') { 
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched data:', data);

                        const detailsTable = document.getElementById(`details-data-${officeId}`);
                        detailsTable.innerHTML = '';

                        data.forEach(child => {
                            detailsTable.innerHTML += `
                                <table> 
                                <thead>
                                    <tr>
                                        <th>Office Name</th>
                                        <th>KPI Value</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                     <td>${child.id}</td>
                                    <td>${child.name}</td>
                                    <td>
                                        <button class="btn btn-primary btn-expand" 
                                                data-id="${child.id}" 
                                                data-url="/smis/setting/offices/${child.id}/details">
                                            Expand
                                        </button>
                                    </td>
                                </tr>
                                <tr class="details-row" id="details-${child.id}" style="display: none;">
                                    <td colspan="3">
                                        <table class="table">
                                            <tbody id="details-data-${child.id}">
                                                <!-- Further Nested Data -->
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                                </table> 
                            `;
                        });

                        // Reattach event listeners to newly added buttons
                        attachExpandListeners();

                        detailsRow.style.display = '';
                    })
                    .catch(error => console.error('Fetch error:', error));
            } else {
                detailsRow.style.display = 'none';
            }
        });
    });
}

// Initial call to attach listeners to existing buttons
attachExpandListeners(); --}}


 </script>
    </div>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.select2').select2();

        });
    </script>

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

@endsection
