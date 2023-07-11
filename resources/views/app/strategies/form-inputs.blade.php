@php $editing = isset($strategy) @endphp

<div class="row">

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="objective_id" label="Objective" required>
            @php $selected = old('objective_id', ($editing ? $strategy->objective_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }} value="">Please select the Objective</option>
            @foreach($objectives as $value => $label)
                @if(app()->getLocale() ==$label->locale)
                    <option value="{{ $label->translation_id }}" {{ $selected == $label->translation_id ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
    @foreach($languages as $key => $lang)

    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.select name="created_by_id" label="User" required>
            @php $selected = old('created_by_id', ($editing ? $strategy->created_by_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the User</option>
            @foreach($users as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="updated_by_id" label="User2" required>
            @php $selected = old('updated_by_id', ($editing ? $strategy->updated_by_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the User</option>
            @foreach($users as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group> --}}

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name_'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
            maxlength="255"
            value="{{ $strategyTranslations[$lang->locale][0]->name ?? '' }}"
            placeholder="{{'name in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'discription_'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            maxlength=""
            placeholder="Description"
            required>{{ $strategyTranslations[$lang->locale][0]->discription ?? '' }}
              </x-inputs.textarea
        >
    </x-inputs.group>
@endforeach
</div>
