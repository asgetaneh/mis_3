@php $editing = isset($reportingPeriod) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="planing_year_id" label="Planing Year" required>
            @php $selected = old('planing_year_id', ($editing ? $reportingPeriod->planing_year_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Planing Year</option>
            @foreach($planingYears as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="start_date"
            label="Start Date"
            :value="old('start_date', ($editing ? $reportingPeriod->start_date : ''))"
            maxlength="255"
            placeholder="Start Date"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="end_date"
            label="End Date"
            :value="old('end_date', ($editing ? $reportingPeriod->end_date : ''))"
            maxlength="255"
            placeholder="End Date"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="reporting_period_type_id"
            label="Reporting Period Type"
            required
        >
            @php $selected = old('reporting_period_type_id', ($editing ? $reportingPeriod->reporting_period_type_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Reporting Period Type</option>
            @foreach($reportingPeriodTypes as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
