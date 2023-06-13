@php $editing = isset($suitableKpi) @endphp

<div class="row">
 
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="user_managing_office" label="Office" required>
            @php $selected = old('office_id', ($editing ? $suitableKpi->office_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Office</option>
            @foreach($user_managing_office as $value => $label)
            <option value="{{ $label->id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->officeTranslations[0]->name }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select required name="planing_year_id" label="Planing Year" >
             <option >Please select the Planing Year</option>
            @foreach($planingYears as $value => $label) 
            <option value="{{ $label->id }}" >{{ $label->planingYearTranslations[0]->name }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
