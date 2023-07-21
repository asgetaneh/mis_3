@php $editing = isset($officeTranslation) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="parent_name" label="Parent Office" required>
            @php $selected = old('translation_id', ($editing ? $officeTranslation->translation_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Office</option>
            @foreach($offices as $value => $label)
            <option value="{{ $label->id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->officeTranslations[0]->name }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    @foreach($languages as $key => $lang)


    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            value="{{ $officeTranslations[$lang->locale][0]->name ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group>



    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            value="{{ $officeTranslations[$lang->locale][0]->name ?? '' }}"
            maxlength="255"
            required>
              </x-inputs.textarea
        >
    </x-inputs.group>
 @endforeach
</div>
