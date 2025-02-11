<table class="table table-bordered" >
<tr>
<tr>
    <th colspan="{{ $getQuarter->count() + 3 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
    </th>
    <td rowspan="{{ $planAccKpiChildTwo->count() }} *{{count($getQuarter)}}+2">
        @if (!$office->offices->isEmpty())
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
                $childAndHimOffKpi = $office->offices;
                foreach ($childAndHimOffKpi as $key => $value) {
                    $childAndHimOffKpi_array[$key] = $value->id;
                }
                $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
            // $planKpiOfficeYear = $planAcc->planSumOfKpi($planAccKpi->id, $office);
                $narration = $planAcc->getReportNarration($planAccKpi->id, $planning_year->id ?? NULL, $office, $period->id);
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
                    {{-- @endforeach
            </tr>
        @endforeach --}}
        @empty
        @endforelse

    </tr>
    @endforeach
</tr>
@endforeach
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
