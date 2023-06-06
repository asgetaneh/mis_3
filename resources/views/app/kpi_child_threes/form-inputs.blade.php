@php $editing = isset($kpiChildThree) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="kpi_child_two_id" label="Kpi Child Two" required>
            @php $selected = old('kpi_child_two_id', ($editing ? $kpiChildThree->kpi_child_two_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Kpi Child Two</option>
            @foreach($kpiChildTwos as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
