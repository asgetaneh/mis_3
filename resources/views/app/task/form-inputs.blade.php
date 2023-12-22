@php $editing = isset($task) @endphp

<div class="row">

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="kpi" label="Key Performance Indicator" class="form-control select2" required>
            @php $selected = old('kpi', ($editing ? $task->kpi_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }} value="">Please select the Kpi</option>
            @foreach($kpis as $value => $label)
            <option value="{{ $label->id }}" {{ $selected == $label->id ? 'selected' : '' }} >{{ $label->keyPeformanceIndicatorTs[0]->name }}
            </option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
     <x-inputs.group class="col-sm-12">
        <x-inputs.select name="reporting_period" label="Reporting period" class="form-control select2" required>
            @php $selected = old('reporting_period', ($editing ? $task->period_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }} value="">Please select the reporting period</option>

            @foreach($reporting_periods as $value => $label)
             <option value="{{ $label->id }}" {{ $selected == $label->id ? 'selected' : '' }} >{{ $label->reportingPeriodTs[0]->name }} - {{ $label->reportingPeriodType->reportingPeriodTypeTs[0]->name }}</option>
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
            value="{{ $task->name ?? '' }}"
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
            >
            {{ $task->description ?? '' }}
        </x-inputs.textarea>
    </x-inputs.group>
 @endforeach
<x-inputs.group class="col-sm-12">
        <x-inputs.select class="offices select2" multiple="multiple" data-placeholder="Select task measurement" name="measurements[]" label="Measurement" required>
            {{-- @dd($keyPeformanceIndicator->offices) --}}
            @php $selected = old('task_measurement_id', ($editing ? $task->taskMeasurement : '')) @endphp
            {{-- <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Office</option> --}}
           
            @foreach($task_measurements as $value => $label) 
                <option value="{{ $label->id }}" @if(in_array($label->id, $label->pluck('id')->toArray()))  @endif>
                {{ $label->name }}
                </option>
             @endforeach
        </x-inputs.select>
    </x-inputs.group>
    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="weight"
            label="Weight"
            :value="old('weight', ($editing ? $task->weight : ''))"
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
