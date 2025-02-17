<table class="table table-bordered" style="background:#12cd4322;">
<tr>
<tr>
    <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 3 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
    </th>
    <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count()  * $planAcc->Kpi->kpiChildTwos->count()}}+3">
        @if (!$office->offices->isEmpty())
            <p>
                <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAcc->Kpi->id}}" role="button"
                    aria-expanded="false" aria-controls="collapseExample0">
                    Details
                </a>
            </p>
        @else
            {{ '' }}
        @endif
    </td>
</tr>
{{-- <td colspan="2  ">#</td>
@foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <td>
        {{ $one->kpiChildOneTranslations[0]->name }}
    </td>
@endforeach --}}
{{-- <th>Sum</th> --}}
{{-- </tr> --}}


    <tr>
        <th colspan="2">#</th>
        <th colspan="">Baseline</th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
        <th>
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse
    </tr>


    @forelse ($planAcc->Kpi->kpiChildOnes as $one)

    <tr>
        <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
            {{ $one->kpiChildOneTranslations[0]->name }}
        </th>
        @foreach ($planAcc->Kpi->kpiChildTwos as $two)
            <th>
                {{ $two->kpiChildTwoTranslations[0]->name }}
            </th>
            @php
                $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,$one->id,$two->id,null);
             @endphp
            <td>{{ $baselineOfOfficePlan }}</td>
            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                @php
                    $childAndHimOffKpi_array = [];
                    $childAndHimOffKpi = $office->offices;
                    foreach ($childAndHimOffKpi as $key => $value) {
                        $childAndHimOffKpi_array[$key] = $value->id;
                    }
                    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
                    //$planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
                    $narration = $planAcc->getNarration($planAcc->Kpi->id,$planning_year->id ?? NULL, $office, $period->id);
                @endphp
                {{-- <tr>
                    <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
                        {{ $period->reportingPeriodTs[0]->name }}
                    </th>
                    @foreach ($planAcc->Kpi->kpiChildTwos as $two)
                        <td>
                            {{ $two->kpiChildTwoTranslations[0]->name }}
                        </td>
                        @foreach ($planAcc->Kpi->kpiChildOnes as $one) --}}
                        @php
                            //$planOneTwo = $planAcc->planOneTwo($planAcc->Kpi->id, $one->id, $two->id, $office, $period->id,false);
                            $planOneTwo = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,$one->id, $two->id,null);
                             $office_level = $office->level;
                            if($office_level == 0) $office_level=1;
                        @endphp
                            <td>
                                @if($planOneTwo[2] <= $office_level)
                                     {{ $planOneTwo[0] }}
                                @else
                                    {{0}}
                                @endif
                                <!-- {{ $planOneTwo[0] }} -->
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

                                $planSumch2Total = $planAcc->planIndividualChTwoSum($planAcc->Kpi->id, $two->id, $planSumch2_array,$period->id);
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
    <tr>
        <th collapse="2">
            Major Activities
        </th>
        <td colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 3 }}">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->plan_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
    </table>

{{-- level two (directores and same level) --}}
{{-- <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
    <div class="card card-body">
        @php
            $offices_twos = $office->offices;
        @endphp
        @forelse ($offices_twos as $office)

            @include('app.plan_accomplishments.view-kpi122')
            <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                <div class="card card-body">
                    @php
                        $offices_threes = $office->offices;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.plan_accomplishments.view-kpi122')
                        <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                            <div class="card card-body">
                                @php
                                    $offices_fours = $office->offices;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.plan_accomplishments.view-kpi122')
                                    <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                        <div class="card card-body">
                                            @php
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.plan_accomplishments.view-kpi122')
                                                <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                                    <div class="card card-body">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.plan_accomplishments.view-kpi122')
                                                            <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
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
 --}}
