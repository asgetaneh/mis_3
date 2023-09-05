@php $editing = isset($officeTranslation) @endphp
{{-- @dd($officeTranslation->office) --}}

<div class="row">
    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.select name="parent_name" label="Parent Office" required>
            @php $selected = old('translation_id', ($editing ? $officeTranslation->translation_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Office</option>
            @foreach($offices as $value => $label)
            <option value="{{ $label->id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->officeTranslations[0]->name }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group> --}}

    @if (isset($officeTranslation))
        <input type="hidden" value="{{ $officeTranslation->translation_id }}" name="officeId">
        <input type="hidden" value="{{ $officeTranslation->office->parent_office_id }}" name="parentOfficeId">
    @endif

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="parent_name" label="Parent Office" class="form-control select2" required>
            @php $selected = old('translation_id', ($editing ? $officeTranslation->office->parent_office_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }} value="">Please select the Office</option>
            <option value="">NO PARENT</option>

            @foreach($offices as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    @if ($editing && $officeTranslation->translation_id == $label->translation_id)
                        @continue
                    @else
                        <option value="{{ $label->translation_id }}" {{ $selected == $label->office->id ? 'selected' : '' }} >{{ $label->name }}</option>
                    @endif
                @endif
            @endforeach

        </x-inputs.select>
    </x-inputs.group>

    @foreach($languages as $key => $lang)


    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name_'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            value="{{ $officeTranslations[$lang->locale][0]->name ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group>



    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description_'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            maxlength=""
            placeholder="{{ 'description in '.$lang->name }}"
            required>{{ $officeTranslations[$lang->locale][0]->description ?? '' }}
              </x-inputs.textarea
        >
    </x-inputs.group>
 @endforeach
</div>
