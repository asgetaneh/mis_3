<table class ="table table-bordered">
    <tr>
    @if($first==1)

        {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
        @if(reportStatusOffice(auth()->user()->offices[0], $planAcc->kpi_id, $planning_year[0]->id) == null || reportStatusOffice(auth()->user()->offices[0], $planAcc->kpi_id, $planning_year[0]->id) == auth()->user()->offices[0]->level)
            <th class="bg-light">
                <input class="hidden-self-input-{{ $planAcc->Kpi->id }}" type ="hidden" name="approve[]" value="{{$planAcc->Kpi->id}}-{{auth()->user()->offices[0]->id}}-{{$planning_year[0]->id}}"
                title="Appove for {{auth()->user()->offices[0]->officeTranslations[0]->name}}"/>

                <p class="badge badge-warning d-inline">NOT APPROVED</p>
            </td>

        @else
            <th class="bg-light">
                <p class="badge badge-success d-inline">APPROVED</p>
            </th>
        @endif
    </tr>
    <tr>
        <th>
            Offices
        </th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
             <th>  {{ $period->reportingPeriodTs[0]->name }}   </th>
        @empty
        @endforelse
    </tr>
    @php $first =0; @endphp
    @endif
    <tr>
           <td rowspan="2">{{auth()->user()->offices[0]->officeTranslations[0]->name}}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                $planOfOfficePlan
                = reportSum($planAcc->Kpi->id,auth()->user()->offices[0], $period->id, 1);
               $narration = getReportNarration($planAcc->Kpi->id,$planning_year[0]->id, auth()->user()->offices[0], $period->id);
            @endphp
            <td>
               {{$planOfOfficePlan}}
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
              {!! html_entity_decode($plannaration->plan_naration) !!}
              @php
              echo "<br/>"
              @endphp
        @endforeach
      </td>
 </tr>
</table>
