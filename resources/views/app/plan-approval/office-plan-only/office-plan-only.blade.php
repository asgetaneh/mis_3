<table class ="table table-bordered">
    <tr>
    @if($first==1)

        {{-- check if current office is approved or not so that show the select or an APPROVED badge --}}
        @if(planStatusOffice($office, $planAcc->kpi_id, $planning_year[0]->id) > auth()->user()->offices[0]->level)
            <th class="bg-light">
                {{-- <input class="form-check" type ="checkbox" name="approve[]" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year[0]->id}}"
                title="Appove for {{$office->officeTranslations[0]->name}}"/> --}}

                <div class="icheck-success d-inline">
                    <input class="office-checkbox-kpi-{{ $planAcc->kpi_id }}" name="approve[]" type="checkbox" id="{{$office->id}}" value="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year[0]->id}}">
                    <label for="{{$office->id}}">
                        Select Office
                    </label>
                </div>
            </th>

        @else
            <th>
                <p class="badge badge-success d-inline">APPROVED</p>
            </th>
        @endif
        <th>
            @if (empty(commentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year[0]->id)))
                <a class="btn btn-sm float-right btn-info text-white write-comment"
                    data-toggle="modal" data-target="#modal-lg"
                    data-id="{{$planAcc->Kpi->id}}-{{$office->id}}-{{$planning_year[0]->id}}">
                    <i class="fas fa fa-comments mr-1"></i> Write Comment
                </a>
            @else
                @if (!empty(commentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year[0]->id)) && commentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year[0]->id)->reply_comment !== null)
                    <a class="btn btn-sm float-right btn-info text-white view-reply-comment"
                        data-toggle="modal" data-target="#view-reply-comment"
                        data-id="{{ $office->id }}-{{ commentorTextStatus($office, auth()->user()->offices[0]->id, $planAcc->kpi_id, $planning_year[0]->id)->id }}-{{$planAcc->Kpi->id}}-{{$planning_year[0]->id}}">
                        <i class="fas fa fa-eye mr-1"></i> View Reply
                    </a>
                @else
                    <p class="float-right text-primary"><u>Waiting for reply!</u></p>
                @endif
            @endif
        </th>
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
           <td rowspan="2">{{$office->officeTranslations[0]->name}}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                $planOfOfficePlan
                = planSum($planAcc->Kpi->id,$office, $period->id, 2);
               $narration = getNarration($planAcc->Kpi->id,$planning_year[0]->id, $office, $period->id);
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