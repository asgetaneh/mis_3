{{-- level one (directores and same level) --}}
<table class="table table-bordered" style="background:#12cd4322;">
    <tr>
        <th colspan="{{ $getQuarter->count() + 1 }} ">
            Offices: {{ $office->officeTranslations[0]->name }}
        </th>
         <td rowspan="{{ $planAccKpiChildOne->count() + 3 }}">
            @if (!$officeOffices->isEmpty())
                <p>
                    <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAccKpi->id}}" role="button"
                        aria-expanded="false" aria-controls="collapseExample0">
                        Details
                    </a>
                </p>
            @else
                {{ 'no child ' }}
            @endif
        </td>
    </tr>
    <tr>
        <th>#</th>
        @forelse($getQuarter as $period)
            <th>
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @php
                $childAndHimOffKpi_array = [];
                $childAndHimOffKpi = $officeOffices;
                foreach ($childAndHimOffKpi as $key => $value) {
                    $childAndHimOffKpi_array[$key] = $value->id;
                }
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
                //$planKpiOfficeYear = $planAcc->planSumOfKpi($planAccKpi->id, $office);
            @endphp
        @empty
        @endforelse

        @foreach ($planAccKpiChildOne as $one)
    <tr>
        <td>
            {{ $one->kpiChildOneTranslations[0]->name }}
        </td>
        @forelse($getQuarter as $period)
            <td>
                @php
                    //$planOne = $planAcc->planOne($planAccKpi->id, $one->id, $office, $period->id, true);
                    $planOne = $planAcc->KpiOTT($planAccKpi->id, $office, $period->id,true,$planning_year->id ?? NULL ,$one->id,null,null);
                    $narration = $planAcc->getReportNarration($planAccKpi->id, $planning_year->id ?? NULL, $office, $period->id);
                @endphp
                {{ $planOne[1] }}
            </td>
        @empty
        @endforelse
    </tr>
    @endforeach
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="4">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->report_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
</table>
{{-- level two (directores and same level) --}}
<div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
    <div class="card card-body">
        @php
            $offices_twos = $officeOffices;
        @endphp
        @forelse ($offices_twos as $office)

            @include('app.report_accomplishments.view-kpi11')
            <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                <div class="card card-body">
                    @php
                        $offices_threes = $office->offices;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.report_accomplishments.view-kpi11')
                        <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                            <div class="card card-body">
                                @php
                                    $offices_fours = $office->offices;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.report_accomplishments.view-kpi11')
                                    <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                        <div class="card card-body">
                                            @php
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.report_accomplishments.view-kpi11')
                                                <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                                    <div class="card card-body">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.report_accomplishments.view-kpi11')
                                                            <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                                                <div class="card card-body">

                                                                </div>
                                                            </div>
                                                        @empty
                                                            <h4>on child!</h4>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            @empty
                                                <h4>on child!</h4>
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <h4>on child!</h4>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <h4>on child!</h4>
                    @endforelse
                </div>
            </div>
        @empty
            <h4>on child!</h4>
        @endforelse
    </div>
</div>
