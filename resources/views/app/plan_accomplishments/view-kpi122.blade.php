<table class="table table-bordered" > 
<tr>
<tr>
    <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
    </th>
</tr>
<td colspan="2  ">#</td>
@foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <td>
        {{ $one->kpiChildOneTranslations[0]->name }}
    </td>
@endforeach
{{-- <th>Sum</th> --}}
</tr>
@forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
    @php
        $childAndHimOffKpi_array = [];
        $childAndHimOffKpi = $office->offices;
        foreach ($childAndHimOffKpi as $key => $value) {
            $childAndHimOffKpi_array[$key] = $value->id;
        }
        $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
        $planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
        $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
    @endphp
    <tr>
        <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
            {{ $period->reportingPeriodTs[0]->name }}
        </th>
        @foreach ($planAcc->Kpi->kpiChildTwos as $two)
            <td>
                {{ $two->kpiChildTwoTranslations[0]->name }}
            </td>
            @foreach ($planAcc->Kpi->kpiChildOnes as $one)
                <td>
                    @php
                        $planOneTwo = $planAcc->planOneTwo($planAcc->Kpi->id, $one->id, $two->id, $office, $period->id);
                    @endphp
                    {{ $planOneTwo[0] }}
                </td>
            @endforeach
    </tr>
@endforeach
@empty
@endforelse
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
                <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}" role="button"
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