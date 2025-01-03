<table class ="table table-bordered">
    <tr>
    @if($first==1)

        {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
        {{-- @if(planStatusOffice($office, $planAcc->kpi_id, $planning_year->id ?? NULL) == 0) --}}
            <th class="bg-light">
                {{-- <input class="form-check" type ="checkbox" name="approve[]" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}"
                title="Appove for {{$office->officeTranslations[0]->name}}"/> --}}

                <div class="icheck-success d-inline">
                    <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox" id="{{ $planAcc->kpi_id }}-{{$office->id}}" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}">
                    <label for="{{ $planAcc->kpi_id }}-{{$office->id}}">
                        Select Office
                    </label>
                </div>
            </th>

        {{-- @else
            <th>
                <p class="badge badge-success d-inline">APPROVED</p>
            </th>
        @endif --}}

        <th>
            <a class="btn btn-sm float-right btn-info text-white write-comment ml-2"
                data-toggle="modal" data-target="#modal-lg"
                data-id="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year->id ?? NULL}}">
                <i class="fas fa fa-comments mr-1"></i> Write Comment
            </a>

                @if (commentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year->id ?? NULL, 2)->count() > 0)
                    <p class="float-right text-primary"><u>Waiting for reply!</u></p>
                @else
                    @if (!empty(commentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year->id ?? NULL, 1)) && commentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year->id ?? NULL, 1)->count() > 0)
                        <a class="btn btn-sm view-reply-comment text-primary float-right"
                            data-toggle="modal" data-target="#view-reply-comment"
                            data-id="{{ $office->id }}-{{ 1 }}-{{$planAcc->Kpi->id}}-{{$planning_year->id ?? NULL}}">
                            <u id="view-reply-tag"><mark>You've a reply! Click to view</mark></u>                        </a>
                    @endif
                @endif
        </th>

    </tr>
    <tr>
        <th>
            Offices
        </th>
        <th>
            Baseline
        </th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
             <th>  {{ $period->reportingPeriodTs[0]->name }}   </th>
        @empty
        @endforelse
    </tr>
    @php $first =0; 
        $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,null,null,null);
    @endphp
    @endif
    <tr>
           <td rowspan="2">{{$office->officeTranslations[0]->name}}</td>
           <td>{{$baselineOfOfficePlan }}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                $planOfOfficePlan
                = planSum($planAcc->Kpi->id,$office, $period->id, 1,$planning_year->id);
               $narration = getNarrationApproved($planAcc->Kpi->id,$planning_year->id ?? NULL, $office, $period->id);
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
    <td colspan="5">
         @foreach ($narration as $key => $plannaration)
              {!! html_entity_decode($plannaration->plan_naration) !!}
              @php
              echo "<br/>"
              @endphp
        @endforeach
      </td>
 </tr>
</table>
