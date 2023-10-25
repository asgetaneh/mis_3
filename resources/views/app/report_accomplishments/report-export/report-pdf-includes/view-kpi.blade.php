<table class="table table-bordered" border="1" class="table table-bordered" style="border-collapse: collapse; margin-bottom: 15px; border: 1px solid #000;">
    @if ($first == '1')

        @php
            $activeQuarter = getReportingQuarter($planAcc->Kpi->reportingPeriodType->id);
        @endphp

        <tr>
            {{-- <th>
                Offices
            </th> --}}
            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
             {{ $period->reportingPeriodTs[0]->name }}
            </th>
            @empty
            @endforelse
        </tr>
    @endif
    <tr>
        {{-- <td rowspan="2">{{ $office->officeTranslations[0]->name }}</td> --}}
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                //$planOfOfficePlan = $planAcc->planSum($planAcc->Kpi->id, $office, $period->id,true,$planning_year);
                $activeQuarter = getReportingQuarter($planAcc->Kpi->reportingPeriodType->id);
                 $planOfOfficePlan =$planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,true,$planning_year[0]->id ,null,null,null);
                 $beforePlan = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year[0]->id ,null,null,null);

                $narration = $planAcc->getReportNarration($planAcc->Kpi->id, $planning_year[0]->id, $office, $period->id);
            @endphp
            <td>

                <div style="display: flex;">
                    <div style="flex: 1; padding: 5px; border: 1px solid #000; background-color: rgb(255, 255, 198);">
                        <span style="">{{ $beforePlan[0] }}</span>
                    </div>
                    <div style="flex: 1; padding: 5px; text-align: center; background-color: green; border: 1px solid #000;">
                        <span style="color: #fff;">{{ $planOfOfficePlan[1] }}</span>
                    </div>
                </div>

                {{-- <mark>{{ $beforePlan[0] }}</mark>
                {{ $planOfOfficePlan[1] }} --}}
            </td>
         @empty
        @endforelse
    </tr>
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="4">
             @foreach ($narration as $key => $plannaration)
                <p>
                    {!! html_entity_decode($plannaration->plan_naration) !!}
                </p>
            @endforeach
        </td>
    </tr>
</table>
