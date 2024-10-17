{{-- level one (directores and same level) --}}
<table class="table table-bordered" style="background:#12cd4322;">
    <tr>
        <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 1 }} ">
            Offices: {{ $office->officeTranslations[0]->name }}
        </td>
         <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }}">
            @if (!$office->offices->isEmpty())
                <p>
                    <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAcc->Kpi->id}}" role="button"
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
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @php
                $childAndHimOffKpi_array = [];
                $childAndHimOffKpi = $office->offices;
                foreach ($childAndHimOffKpi as $key => $value) {
                    $childAndHimOffKpi_array[$key] = $value->id;
                }
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
               // $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
            @endphp
        @empty
        @endforelse

        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <tr>
        <td>
            {{ $one->kpiChildOneTranslations[0]->name }}
        </td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <td>
                @php
                   // $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id,false);
                    $planOne = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,$one->id,null,null);
                    $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
                    $office_level = $office->level;
                    if($office_level == 0) $office_level=1;
                @endphp
                @if($planOne[2] <= $office_level)
                     {{ $planOne[0] }} 
                @else
                    {{0}}
                @endif
                 <!-- {{ $planOne[0] }} -->
            </td>
        @empty
        @endforelse
    </tr>
    @endforeach
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))}}">
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
<div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
    <div class="card card-body">
        @php
            $offices_twos = $office->offices;
        @endphp
        @forelse ($offices_twos as $office)

            @include('app.plan_accomplishments.view-kpi11')
            <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                <div class="card card-body">
                    @php
                        $offices_threes = $office->offices;
                    @endphp
                    @forelse ($offices_threes as $office)
                        @include('app.plan_accomplishments.view-kpi11')
                        <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                            <div class="card card-body">
                                @php
                                    $offices_fours = $office->offices;
                                @endphp
                                @forelse ($offices_fours as $office)
                                    @include('app.plan_accomplishments.view-kpi11')
                                    <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                        <div class="card card-body">
                                            @php
                                                $offices_fives = $office->offices;
                                            @endphp
                                            @forelse ($offices_fives as $office)
                                                @include('app.plan_accomplishments.view-kpi11')
                                                <div class="collapse" id="off{{ $office->id }}{{$planAcc->Kpi->id}}">
                                                    <div class="card card-body">
                                                        @php
                                                            $offices_sixs = $office->offices;
                                                        @endphp
                                                        @forelse ($offices_sixs as $office)
                                                            @include('app.plan_accomplishments.view-kpi11')
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
