@php $editing = isset($suitableKpi) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="key_peformance_indicator_id"
            label="Key Peformance Indicator"
            required
        >
            @php $selected = old('key_peformance_indicator_id', ($editing ? $suitableKpi->key_peformance_indicator_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Key Peformance Indicator</option>
            @foreach($keyPeformanceIndicators as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="office_id" label="Office" required>
            @php $selected = old('office_id', ($editing ? $suitableKpi->office_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Office</option>
            @foreach($offices as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="planing_year_id" label="Planing Year" required>
            @php $selected = old('planing_year_id', ($editing ? $suitableKpi->planing_year_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Planing Year</option>
            @foreach($planingYears as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
