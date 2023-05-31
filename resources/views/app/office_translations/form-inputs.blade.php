@php $editing = isset($officeTranslation) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="translation_id" label="Office" required>
            @php $selected = old('translation_id', ($editing ? $officeTranslation->translation_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Office</option>
            @foreach($offices as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="name"
            label="Name"
            :value="old('name', ($editing ? $officeTranslation->name : ''))"
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
            >{{ old('description', ($editing ? $officeTranslation->description :
            '')) }}</x-inputs.textarea
        >
    </x-inputs.group>
</div>
