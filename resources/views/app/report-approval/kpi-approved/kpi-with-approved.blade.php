<table class ="table table-bordered">
    <tr>
    @if($first==1)

        {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
        {{-- @if(planStatusOffice($office, $planAcc->kpi_id, $planning_year->id ?? NULL) == 0)
            <th class="bg-light">

                <div class="icheck-success d-inline">
                    <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox" id="{{$office->id}}" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}">
                    <label for="{{$office->id}}">
                        Select Office
                    </label>
                </div>
            </th>

        @else --}}
            <th>
                <p class="badge badge-success d-inline" id="any-approved-{{ $planAcc->Kpi->id }}">APPROVED</p>
            </th>
        {{-- @endif --}}
    </tr>
    <tr>
        <th>
            Offices
        </th>
        @forelse(getReportingQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
             <th>  {{ $period->reportingPeriodTs[0]->name }}   </th>
        @empty
        @endforelse
    </tr>
    @php $first =0; @endphp
    @endif
    <tr>
           <td rowspan="2">{{$office->officeTranslations[0]->name}}</td>
        @forelse(getReportingQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
               // $planOfOfficePlan  = reportSum($planAcc->Kpi->id,$office, $period->id, 3);
                $planOfOfficePlan = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $active_period[0]->id, true, $planning_year->id,  null,null,null);
                $narration = getReportNarration($planAcc->Kpi->id,$planning_year->id ?? NULL, $office, $period->id);
            @endphp
            <td>
               {{$planOfOfficePlan[1]}}
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
              {!! html_entity_decode($plannaration->report_naration) !!}
              @php
              echo "<br/>"
              @endphp
        @endforeach
      </td>
 </tr>
</table>
