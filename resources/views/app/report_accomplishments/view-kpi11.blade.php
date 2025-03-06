<table class="table table-bordered" >
{{-- style="background:#34214322;" --}}
    <tr>
        <th colspan="{{ $getQuarter->count() + 1 }} style="width: 90%"">
            Offices: {{ $office->officeTranslations[0]->name }}
        </th>
         <td rowspan="{{ $planAccKpiChildOne->count() + 3 }}">
            @if (!$office->offices->isEmpty())
                <p>
                    <a class="btn btn-info" data-toggle="collapse" href="#off{{ $office->id }}{{$planAccKpi->id}}" role="button"
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
        <th>#</th>
        @forelse($getQuarter as $period)
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
              //  $planKpiOfficeYear = $planAcc->planSumOfKpi($planAccKpi->id, $office);
            @endphp
        @empty
        @endforelse

        @foreach ($planAccKpiChildOne as $one)
    <tr>
        <td style="width: 40%">
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
        <td colspan="7">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->report_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
</table>
