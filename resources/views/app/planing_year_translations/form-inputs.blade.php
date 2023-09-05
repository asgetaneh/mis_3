@php $editing = isset($planingYearTranslation) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="planing_year_id" label="Planing Year" required>
            @php $selected = old('planing_year_id', ($editing ? $planingYearTranslation->planing_year_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Planing Year</option>
            @foreach($planingYears as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="name"
            label="Name"
            :value="old('name', ($editing ? $planingYearTranslation->name : ''))"
            maxlength="255"
            placeholder="Name"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="description"
            label="Description"
            maxlength="255"
            required
            >{{ old('description', ($editing ?
            $planingYearTranslation->description : '')) }}</x-inputs.textarea
        >
    </x-inputs.group>
</div>
