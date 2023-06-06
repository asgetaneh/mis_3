@php $editing = isset($kpiChildThreeTranslation) @endphp

<div class="row">
    @foreach($languages as $key => $lang)
        <x-inputs.group class="col-sm-12">
            <x-inputs.text
                name="{{'name'.$lang->locale}}"
                label="{{'Name in '.$lang->name}}"
                maxlength="255"
                placeholder="{{'name in '.$lang->name}}"
                required
                :value="old('name', ($editing ? $kpiChildThreeTranslation->name : ''))"
            ></x-inputs.text>
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.textarea
                name="{{'description'.$lang->locale}}"
                label="{{'Description in '.$lang->name}}"
                maxlength="255"
                required
                >{{ old('description', ($editing ?
                $kpiChildThreeTranslation->description : '')) }}</x-inputs.textarea
            >
        </x-inputs.group>
    @endforeach

    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="kpiChildThree_id"
            label="Kpi Child Three"
            required
        >
            @php $selected = old('kpiChildThree_id', ($editing ? $kpiChildThreeTranslation->kpiChildThree_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Kpi Child Three</option>
            @foreach($kpiChildThrees as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group> --}}
</div>
