<table class ="table table-bordered {{ isset($setter) ? 'mb-0' : '' }}">
    <tr>
    @if($first==1)

        {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
        @if(planStatusOffice(auth()->user()->offices[0], $planAcc->kpi_id, $planning_year[0]->id) !== auth()->user()->offices[0]->level)

            @if (isset($setter))
                <th class="bg-light">
                    {{-- <input class="form-check" type ="checkbox" name="approve[]" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year[0]->id}}"
                title="Appove for {{$office->officeTranslations[0]->name}}"/> --}}

                    <div class="icheck-success d-inline">
                        <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox"
                            id="{{ $planAcc->kpi_id }}-{{ auth()->user()->offices[0]->id }}"
                            value="{{ $planAcc->Kpi->id }}-{{ auth()->user()->offices[0]->id }}-{{ $planning_year[0]->id }}">
                        <label for="{{ $planAcc->kpi_id }}-{{ auth()->user()->offices[0]->id }}">
                            Select Office
                        </label>
                    </div>
                </th>
            @else
                <th class="bg-light">
                    <input class="hidden-self-input-{{ $planAcc->Kpi->id }}" type ="hidden" name="approve[]" value="{{$planAcc->Kpi->id}}-{{auth()->user()->offices[0]->id}}-{{$planning_year[0]->id}}"
                    title="Appove for {{auth()->user()->offices[0]->officeTranslations[0]->name}}"/>

                    <p class="badge badge-warning d-inline">NOT APPROVED</p>
                </td>
            @endif

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
           <td rowspan="2">{{auth()->user()->offices[0]->officeTranslations[0]->name}} <span class="mark">{{ isset($setter) ? '(Own Plan)' : '' }}</span></td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                $planOfOfficePlan
                = planSum($planAcc->Kpi->id,auth()->user()->offices[0], $period->id, 7);
               $narration = getNarration($planAcc->Kpi->id,$planning_year[0]->id, auth()->user()->offices[0], $period->id);
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
