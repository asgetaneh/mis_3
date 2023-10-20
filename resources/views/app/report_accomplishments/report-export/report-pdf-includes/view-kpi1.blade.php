{{-- level one (directores and same level) --}}
<table class="table table-bordered" border="1" class="table table-bordered" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    <tr>
        <th colspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 1 }} ">
            Offices: {{ $office->officeTranslations[0]->name }}
        </th>
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
                //$planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
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
                    $beforePlan = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year[0]->id ,$one->id, null, null);

                    $narration = $planAcc->getReportNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
                @endphp

                <div style="display: flex;">
                    <div style="flex: 1; padding: 5px; border: 1px solid #000; background-color: rgb(255, 255, 198);">
                        <span style="">{{ $beforePlan[0] }}</span>
                    </div>
                    <div style="flex: 1; padding: 5px; text-align: center; background-color: green; border: 1px solid #000;">
                        <span style="color: #fff;">{{ $planOne[1] }}</span>
                    </div>
                </div>
{{--
                <mark>{{ $beforePlan[0] }}</mark>
                {{ $planOne[1] }} --}}
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
                {!! html_entity_decode($plannaration->plan_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
</table>
