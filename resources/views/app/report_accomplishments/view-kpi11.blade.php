<table class="table table-bordered" > 
{{-- style="background:#34214322;" --}}
    <tr>
        <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }} ">
            Offices: {{ $office->officeTranslations[0]->name }}
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
              //  $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
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
                    //$planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id, true);
                    $planOne = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,true,$planning_year[0]->id ,$one->id,null,null);
                    $narration = $planAcc->getReportNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
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
        <td colspan="2">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->plan_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
        <td>
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
</table>
