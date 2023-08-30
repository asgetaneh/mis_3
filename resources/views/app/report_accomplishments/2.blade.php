@php
$plan_naration = getSavedPlanNaration($planning_year[0]->id,$kpi->id , auth()->user()->offices[0]->id);
@endphp
@if($plan_naration)
<label for="summernote">Major Activities</label> 
<input type ="hidden" name="type" value="yes">
<textarea name="desc{{ $kpi->id}}" style="height: 100px;" class="form-control summernote" name="" id="summernote" placeholder="Narration here" required>{!! $plan_naration !!}</textarea>
@else
<label for="summernote">Major Activities</label> 
<textarea name="desc{{ $kpi->id}}" style="height: 100px;" class="form-control summernote" name="" id="summernote" placeholder="Narration here" required></textarea>
@endif