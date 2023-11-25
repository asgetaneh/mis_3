@php $editing = isset($objective) @endphp

<div class="row">
    
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="kpi" label="Key Performance Indicator" class="form-control select2" required>
            @php $selected = old('kpi', ($editing ? $task->kpi : '')) @endphp
            <option  {{ empty($selected) ? 'selected' : '' }} value="">Please select the Kpi</option>
            @foreach($kpis as $value => $label)
            <option value="{{ $label->id }}" {{ $selected == $label->translation_id ? 'selected' : '' }} >{{ $label->keyPeformanceIndicatorTs[0]->name }}
            </option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
     <x-inputs.group class="col-sm-12">
        <x-inputs.select name="reporting_period" label="Reporting period" class="form-control select2" required>
            @php $selected = old('reporting_period', ($editing ? $task->reporting_period : '')) @endphp
            <option  {{ empty($selected) ? 'selected' : '' }} value="">Please select the reporting period</option>

            @foreach($reporting_periods as $value => $label)  
             <option value="{{ $label->id }}" {{ $selected == $label->translation_id ? 'selected' : '' }} >{{ $label->reportingPeriodTs[0]->name }}</option>
             @endforeach
        </x-inputs.select>
    </x-inputs.group>

     @foreach($languages as $key => $lang)
     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name_'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            value="{{ $objectiveTranslations[$lang->locale][0]->name ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group>
    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description_'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            class="form-control summernote" 
            maxlength=""
            placeholder="Description"
            required>
            {{ $objectiveTranslations[$lang->locale][0]->description ?? '' }}
        </x-inputs.textarea>
    </x-inputs.group> 
 @endforeach

    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="weight"
            label="Weight"
            :value="old('weight', ($editing ? $objective->weight : ''))"
            max="255"
            step="0.01"
            placeholder="Weight"
            required
        ></x-inputs.number>
    </x-inputs.group>

     <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 150
            });
            $('.dropdown-toggle').dropdown()
        });
    </script>
</div>
