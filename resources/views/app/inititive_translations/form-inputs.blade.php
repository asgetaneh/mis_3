@php $editing = isset($inititiveTranslation) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="inititive_id" label="Inititive" required>
            @php $selected = old('inititive_id', ($editing ? $inititiveTranslation->inititive_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Inititive</option>
            @foreach($inititives as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="name"
            label="Name"
            :value="old('name', ($editing ? $inititiveTranslation->name : ''))"
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
            $inititiveTranslation->description : '')) }}</x-inputs.textarea
        >
    </x-inputs.group>
</div>
