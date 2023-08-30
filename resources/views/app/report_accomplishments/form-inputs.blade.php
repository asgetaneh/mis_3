@php $editing = isset($planAccomplishment) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="suitable_kpi_id" label="Suitable Kpi" required>
            @php $selected = old('suitable_kpi_id', ($editing ? $planAccomplishment->suitable_kpi_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Suitable Kpi</option>
            @foreach($suitableKpis as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="reporting_period_id"
            label="Reporting Period"
            required
        >
            @php $selected = old('reporting_period_id', ($editing ? $planAccomplishment->reporting_period_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Reporting Period</option>
            @foreach($reportingPeriods as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="plan_value"
            label="Plan Value"
            :value="old('plan_value', ($editing ? $planAccomplishment->plan_value : ''))"
            max="255"
            step="0.01"
            placeholder="Plan Value"
            required
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="accom_value"
            label="Accom Value"
            :value="old('accom_value', ($editing ? $planAccomplishment->accom_value : ''))"
            max="255"
            step="0.01"
            placeholder="Accom Value"
            required
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="plan_status"
            label="Plan Status"
            :value="old('plan_status', ($editing ? $planAccomplishment->plan_status : ''))"
            maxlength="255"
            placeholder="Plan Status"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="accom_status"
            label="Accom Status"
            :value="old('accom_status', ($editing ? $planAccomplishment->accom_status : ''))"
            maxlength="255"
            placeholder="Accom Status"
            required
        ></x-inputs.text>
    </x-inputs.group>
</div>
