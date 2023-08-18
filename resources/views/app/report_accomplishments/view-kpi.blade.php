<table class ="table table-bordered">
    @if($first=='1')
     @php $first =0; @endphp
    <tr>
        <th>
            Offices  
        </th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
             <th>  {{ $period->reportingPeriodTs[0]->name }}   </th>
        @empty
        @endforelse
    </tr>
    @endif
    <tr> 
           <td rowspan="2">{{$office->officeTranslations[0]->name}}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                $planOfOfficePlan
                = $planAcc->planSum($planAcc->Kpi->id,$office, $period->id);
               $narration = $planAcc->getNarration($planAcc->Kpi->id,$planning_year[0]->id, $office, $period->id);
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
