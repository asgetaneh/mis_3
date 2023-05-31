@php $editing = isset($inititive) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="key_peformance_indicator_id"
            label="Key Peformance Indicator"
            required
        >
            @php $selected = old('key_peformance_indicator_id', ($editing ? $inititive->key_peformance_indicator_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Key Peformance Indicator</option>
            @foreach($keyPeformanceIndicators as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
