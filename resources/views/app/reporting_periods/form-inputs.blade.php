@php $editing = isset($reportingPeriod) @endphp

<div class="row">
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
    <div class="w-100 row pl-2">
    <x-inputs.group class="col">
        <x-inputs.date
            id="start_date"
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

   

</div>
<style>
<link rel="stylesheet" href="../assets/am/redmond.calendars.picker.css">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="../assets/am/jquery.plugin.js"></script>

<script src="../assets/am/jquery.calendars.js"></script>
<script src="../assets/am/jquery.calendars.plus.js"></script>
<script src="../assets/am/jquery.calendars.picker.js"></script>

<script src="../assets/am/jquery.calendars.ethiopian.js"></script>
/* <script src="../assets/am/jquery.calendars.ethiopian-am.js"></script>
<script type="text/javascript" src="../assets/am/jquery.calendars.picker-am.js"></script> */

<script src="{{ asset('assets/am/jquery.calendars.picker-am.js') }}">
<script src="{{ asset('assets/am/jquery.calendars.ethiopian-am.js') }}">
<script src="{{ asset('assets/am/jquery.calendars.ethiopian.js') }}">
<script src="{{ asset('assets/am/jquery.calendars.picker.js') }}">
<script src="{{ asset('assets/am/jquery.calendars.plus.js') }}">
<script src="{{ asset('assets/am/jquery.calendars.js') }}">
<script src="{{ asset('assets/am/jquery.plugin.js') }}">

    <link rel="stylesheet" href="{{ asset('assets/am/css/AdminLTE.min.css') }}">
</style>
<script>
$(function() {
     var calendar = $.calendars.instance('ethiopian','am');
    $('#start_date').calendarsPicker({calendar: calendar});
    $('#end_date').calendarsPicker({calendar: calendar});

    $('#inlineDatepicker').calendarsPicker({calendar: calendar, onSelect: showDate});
}); 
</script>
