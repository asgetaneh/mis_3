@php $editing = isset($reportingPeriod) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="planing_year_id" label="Planing Year" required>
            @php $selected = old('planing_year_id', ($editing ? $reportingPeriod->planing_year_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Planing Year</option>
            @foreach($planingYears as $value => $label)
                @if(app()->getLocale() ==$label->locale)
                    <option value="{{ $label->planing_year_id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <div class="w-100 row pl-2">
    <x-inputs.group class="col">
        <x-inputs.date
            id="start_date"
            inputmode='none'
            type="text"
            name="start_date"
            label="Start Date"
            :value="old('start_date', ($editing ? $reportingPeriod->start_date : ''))"
            maxlength="255"
            placeholder="Start Date"
            required
        ></x-inputs.date>
    </x-inputs.group>

    <x-inputs.group class="col">
        <x-inputs.date

             id="end_date"
            inputmode='none'
            type="text"
            name="end_date"
            label="End Date"
            :value="old('end_date', ($editing ? $reportingPeriod->end_date : ''))"
            maxlength="255"
            placeholder="End Date"
            required
        ></x-inputs.date>
    </x-inputs.group>
</div>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="reporting_period_type_id"
            label="Reporting Period Type"
            required
        >
            @php $selected = old('reporting_period_type_id', ($editing ? $reportingPeriod->reporting_period_type_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Reporting Period Type</option>
            @foreach($reportingPeriodTypes as $value => $label)
                @if(app()->getLocale() ==$label->locale)
                    <option value="{{ $label->id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    @foreach ($languages as $key => $lang)
        <x-inputs.group class="col-sm-12">
            <x-inputs.text name="{{ 'name' . $lang->locale }}" label="{{ 'Name in ' . $lang->name }}" maxlength="255"
                placeholder="{{ 'name in ' . $lang->name }}" required></x-inputs.text>
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.textarea name="{{ 'description' . $lang->locale }}" label="{{ 'Description in ' . $lang->name }}"
                maxlength="255" required>
            </x-inputs.textarea>
        </x-inputs.group>
    @endforeach

</div>
