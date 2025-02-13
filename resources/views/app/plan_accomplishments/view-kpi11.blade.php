<table class="table table-bordered" >
{{-- style="background:#34214322;" --}}
    <tr>
        <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 2 }} " style="width:90%">
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
                {{ '' }}
            @endif
        </td>
    </tr>
    <tr>
        <th style="width:20%">#</th>
        <th  rowspan="">{{"Baseline"}}</th>
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
                //$planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
            @endphp
        @empty
        @endforelse

        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <tr>
        <td style="width:20%">
            {{ $one->kpiChildOneTranslations[0]->name }}
        </td>
        @php
            $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,$one->id,null,null);
            //dump($office);
        @endphp
        <td>
            {{ $baselineOfOfficePlan }}
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
        <th>
            Major Activities
        </th>
        <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+1}}">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->plan_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
     </tr>
</table>
