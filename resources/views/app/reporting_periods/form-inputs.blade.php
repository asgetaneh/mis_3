@php $editing = isset($reportingPeriod) @endphp

<div class="row">
     @foreach ($languages as $key => $lang)
        <x-inputs.group class="col-sm-12">
            <x-inputs.text name="{{ 'name_' . $lang->locale }}" label="{{ 'Name in ' . $lang->name }}" maxlength="255" value="{{ $periodTranslations[$lang->locale][0]->name ?? '' }}"
                placeholder="{{ 'name in ' . $lang->name }}" required></x-inputs.text>
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.textarea name="{{ 'description_' . $lang->locale }}" label="{{ 'Description in ' . $lang->name }}"
                maxlength="" placeholder="Description" required>{{ $periodTranslations[$lang->locale][0]->description ?? '' }}
            </x-inputs.textarea>
        </x-inputs.group>
    @endforeach
    <div class="w-100 row pl-2">
    <x-inputs.group class="col">

        <x-inputs.date
            label="Start Date"
            autocomplete="off" class="form-control col" data-inputmask-alias="dd/mm/yyyy"
            data-inputmask="'yearrange': { 'minyear': '{{ \Andegna\DateTimeFactory::now()->getYear() }}'}"
            data-val="true" data-val-required="Required" id="start_date" name="start_date"
            placeholder="date/month/year" type="text" :value="old('start_date', ($editing ? date('d/m/Y', strtotime($reportingPeriod->start_date)) : ''))"
            spellcheck="false" required pattern="[0-9\/]*" oninvalid="setCustomValidity('Please enter a valid date!')" onchange="try{setCustomValidity('')}catch(e){}">
        </x-inputs.date>

        {{-- <x-inputs.date
            id="start_date"
            inputmode='none'
            type="text"
            name="start_date"
            label="Start Date"
            :value="old('start_date', ($editing ? $reportingPeriod->start_date : ''))"
            maxlength="255"
            placeholder="Start Date"
            required
        ></x-inputs.date> --}}
    </x-inputs.group>

    <x-inputs.group class="col">

        <x-inputs.date
            label="End Date"
            autocomplete="off" class="form-control col" data-inputmask-alias="dd/mm/yyyy"
            data-inputmask="'yearrange': { 'minyear': '{{ \Andegna\DateTimeFactory::now()->getYear() }}'}"
            data-val="true" data-val-required="Required" id="end_date" name="end_date"
            placeholder="date/month/year" type="text" :value="old('end_date', ($editing ? date('d/m/Y', strtotime($reportingPeriod->end_date)) : ''))"
            spellcheck="false" required pattern="[0-9\/]*" oninvalid="setCustomValidity('Please enter a valid date!')" onchange="try{setCustomValidity('')}catch(e){}">
        </x-inputs.date>

        {{-- <x-inputs.date

             id="end_date"
            inputmode='none'
            type="text"
            name="end_date"
            label="End Date"
            :value="old('end_date', ($editing ? $reportingPeriod->end_date : ''))"
            maxlength="255"
            placeholder="End Date"
            required
        ></x-inputs.date> --}}
    </x-inputs.group>
</div>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="reporting_period_type_id"
            label="Reporting Period Type"
            class="form-control select2"
            required
        >
            @php $selected = old('reporting_period_type_id', ($editing ? $reportingPeriod->reporting_period_type_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }} value="">Please select the Reporting Period Type</option>
            @foreach($reportingPeriodTypes as $value => $label)
                @if(app()->getLocale() ==$label->locale)
                    <option value="{{ $label->reporting_period_type_id }}" {{ $selected == $label->reporting_period_type_id ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>



</div>
