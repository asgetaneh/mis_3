@php $editing = isset($keyPeformanceIndicatorT) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="name"
            label="Name"
            :value="old('name', ($editing ? $keyPeformanceIndicatorT->name : ''))"
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
            $keyPeformanceIndicatorT->description : '')) }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="out_put"
            label="Out Put"
            maxlength="255"
            required
            >{{ old('out_put', ($editing ? $keyPeformanceIndicatorT->out_put :
            '')) }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="out_come"
            label="Out Come"
            maxlength="255"
            required
            >{{ old('out_come', ($editing ? $keyPeformanceIndicatorT->out_come :
            '')) }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="translation_id"
            label="Key Peformance Indicator"
            required
        >
            @php $selected = old('translation_id', ($editing ? $keyPeformanceIndicatorT->translation_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Key Peformance Indicator</option>
            @foreach($keyPeformanceIndicators as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>