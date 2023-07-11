@php $editing = isset($reportingPeriodType) @endphp
@foreach ($languages as $key => $lang)
    <x-inputs.group class="col-sm-12">
        <x-inputs.text name="{{ 'name_' . $lang->locale }}" label="{{ 'Name in ' . $lang->name }}" maxlength="255" value="{{ $reportingTypeTranslations[$lang->locale][0]->name ?? '' }}"
            placeholder="{{ 'name in ' . $lang->name }}" required></x-inputs.text>
    </x-inputs.group>

    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.text name="{{ 'output' . $lang->locale }}" label="{{ 'Output in ' . $lang->name }}" maxlength="255"
            placeholder="{{ 'output in ' . $lang->name }}" required></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text name="{{ 'outcome' . $lang->locale }}" label="{{ 'Outcome in ' . $lang->name }}" maxlength="255"
            placeholder="{{ 'outcome in ' . $lang->name }}" required></x-inputs.text>
    </x-inputs.group> --}}

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea name="{{ 'description_' . $lang->locale }}" label="{{ 'Description in ' . $lang->name }}" placeholder="Description"
             maxlength="" required>{{ $reportingTypeTranslations[$lang->locale][0]->description ?? '' }}
        </x-inputs.textarea>
    </x-inputs.group>
@endforeach
<div class="row"></div>
