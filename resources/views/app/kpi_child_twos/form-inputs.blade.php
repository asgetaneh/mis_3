@php $editing = isset($kpiChildTwo) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="kpi_child_one_id" label="Kpi Child One" required>
            @php $selected = old('kpi_child_one_id', ($editing ? $kpiChildTwo->kpi_child_one_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Kpi Child One</option>
            @foreach($kpiChildOnes as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
