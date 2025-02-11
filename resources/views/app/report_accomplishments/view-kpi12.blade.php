<table class="table table-bordered" style="background:#12cd4322;">
<tr>
<tr>
    <th colspan="{{ $getQuarter->count() + 3 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
    </th>
    <td rowspan="{{ $planAccKpiChildTwo->count() }} *{{count($getQuarter)}}+2">
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
{{-- <td colspan="2  ">#</td>
@foreach ($planAccKpiChildOne as $one)
    <td>
        {{ $one->kpiChildOneTranslations[0]->name }}
    </td>
@endforeach --}}
{{-- <th>Sum</th> --}}
</tr>


    <tr>
        <th colspan="2">#</th>

        @forelse($getQuarter as $period)
        <th>
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse
    </tr>


    @forelse ($planAccKpiChildOne as $one)

    <tr>
        <th rowspan="{{ $planAccKpiChildTwo->count() }}">
            {{ $one->kpiChildOneTranslations[0]->name }}
        </th>
        @foreach ($planAccKpiChildTwo as $two)
            <th>
                {{ $two->kpiChildTwoTranslations[0]->name }}
            </th>

            @forelse($getQuarter as $period)
                @php
                    $childAndHimOffKpi_array = [];
                    $childAndHimOffKpi = $officeOffices;
                    foreach ($childAndHimOffKpi as $key => $value) {
                        $childAndHimOffKpi_array[$key] = $value->id;
                    }
                    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
                // $planKpiOfficeYear = $planAcc->planSumOfKpi($planAccKpi->id, $office);
                    $narration = $planAcc->getReportNarration($planAccKpi->id,$planning_year->id ?? NULL, $office, $period->id);
                @endphp
                {{-- <tr>
                    <th rowspan="{{ $planAccKpiChildTwo->count() }}">
                        {{ $period->reportingPeriodTs[0]->name }}
                    </th>
                    @foreach ($planAccKpiChildTwo as $two)
                        <td>
                            {{ $two->kpiChildTwoTranslations[0]->name }}
                        </td>
                        @foreach ($planAccKpiChildOne as $one) --}}
                            <td>
                                @php
                                    //$planOneTwo = $planAcc->planOneTwo($planAccKpi->id, $one->id, $two->id, $office, $period->id,true);
                                    $planOneTwo = $planAcc->KpiOTT($planAccKpi->id, $office, $period->id,true,$planning_year->id ?? NULL ,$one->id, $two->id,null);
                                @endphp
                                {{ $planOneTwo[1] }}
                            </td>
                        {{-- @endforeach --}}
                        {{-- total ch2 --}}
                        {{-- <td>
                            @php
                                $planSumch2_array = [];
                                $planSumch2 = $office->offices;
                                foreach ($planSumch2 as $key => $value) {
                                    $planSumch2_array[$key] = $value->id;
                                }
                                $planSumch2_array = array_merge($planSumch2_array, [$office->id]);

                                $planSumch2Total = $planAcc->planIndividualChTwoSum($planAccKpi->id, $two->id, $planSumch2_array,$period->id);
                            @endphp
                            {{ $planSumch2Total }}
                        </td> --}}
                        {{-- end total ch2 --}}
                {{-- </tr>
            @endforeach --}}
        @empty
            @endforelse
        </tr>
        @endforeach
    </tr>
@endforeach
    {{-- total ch1ch3 --}}
    {{-- <tr>
        <th colspan='2' style="background:#ffeecc;">
            {{ 'Total' }}
            </td>
            @foreach ($planAccKpiChildOne as $one)
        <td>
            @php
                $offices_array = [];
                $userChild = $office->offices;
                foreach ($userChild as $key => $value) {
                    $offices_array[$key] = $value->id;
                }
                $offices_array = array_merge($offices_array, [$office->id]);

                $planSumch1ch3 = $planAcc->planIndividualChOnech($planAccKpi->id, $one->id, $two->id, $offices_array);
            @endphp
            {{ $planSumch1ch3 }}
        </td>
        @endforeach
        <td> {{ $planKpiOfficeYear }}</td>
    </tr>
    <tr>
       <td>
        Major Activities
    </td>
    <td colspan="5">
        @foreach ($narration as $key => $plannaration)
              {!! html_entity_decode($plannaration->plan_naration) !!}
              @php
              echo "<br/>"
              @endphp
        @endforeach
    </td>
    </tr> --}}
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="{{ $planAccKpiChildOne->count() + 1 }}">
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

            @include('app.report_accomplishments.view-kpi122')
            <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                <div class="card card-body">
                    @php
                        $offices_threes = $office->offices;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.report_accomplishments.view-kpi122')
                        <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                            <div class="card card-body">
                                @php
                                    $offices_fours = $office->offices;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.report_accomplishments.view-kpi122')
                                    <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                        <div class="card card-body">
                                            @php
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.report_accomplishments.view-kpi122')
                                                <div class="collapse" id="off{{ $office->id }}{{$planAccKpi->id}}">
                                                    <div class="card card-body">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.report_accomplishments.view-kpi122')
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

