@php $editing = isset($keyPeformanceIndicator) @endphp

<div class="row">

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="objective_id" label="Objective" required>
            @php $selected = old('objective_id', ($editing ? $keyPeformanceIndicator->objective_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Objective</option>
            @foreach($objectives as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->objective->id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="strategy_id" label="Strategy" required>
            @php $selected = old('strategy_id', ($editing ? $keyPeformanceIndicator->strategy_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Strategy</option>
            @foreach($strategies as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->strategy->id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="reporting_period_type_id"
            label="Reporting Period Type"
            required
        >
            @php $selected = old('reporting_period_type_id', ($editing ? $keyPeformanceIndicator->reporting_period_type_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Reporting Period Type</option>
            @foreach($reportingPeriodTypes as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.select name="created_by_id" label="User" required>
            @php $selected = old('created_by_id', ($editing ? $keyPeformanceIndicator->created_by_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the User</option>
            @foreach($users as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group> --}}

    @foreach($languages as $key => $lang)


    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'output'.$lang->locale}}"
            label="{{'Output in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'output in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'outcome'.$lang->locale}}"
            label="{{'Outcome in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'outcome in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            maxlength="255"
            required>
              </x-inputs.textarea
        >
    </x-inputs.group>
 @endforeach


    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="weight"
            label="Weight"
            :value="old('weight', ($editing ? $keyPeformanceIndicator->weight : ''))"
            max="255"
            step="0.01"
            placeholder="Weight"
            required
        ></x-inputs.number>
    </x-inputs.group>
</div>
