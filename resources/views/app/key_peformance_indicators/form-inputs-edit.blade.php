@php $editing = isset($keyPeformanceIndicator) @endphp

<div class="row">

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="objective_id" class="form-control select2" label="Objective" required>
            @php $selected = old('objective_id', ($editing ? $keyPeformanceIndicator->objective_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Objective</option>
            @foreach($objectives as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->objective->id }}" {{ $selected == $label->translation_id ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="strategy_id" class="form-control select2" label="Strategy" required>
            @php $selected = old('strategy_id', ($editing ? $keyPeformanceIndicator->strategy_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Strategy</option>
            @foreach($strategies as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->strategy->id }}" {{ $selected == $label->translation_id ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select class="offices select2" multiple="multiple" data-placeholder="Select office" name="offices[]" label="Office" required>
            {{-- @php $selected = old('office_id', ($editing ? $keyPeformanceIndicator->offices[0]->id : '')) @endphp --}}
            {{-- <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Office</option> --}}
            @foreach($offices as $value => $label)
                @if(app()->getLocale() == $label->locale)
                @if ($label->translation_id == 1)
                    @continue
                @else
                    <option value="{{ $label->translation_id }}"
                        @if(in_array($label->translation_id, $selectedOffices->pluck('id')->toArray())) selected @endif>
                        {{ $label->name }}
                    </option>
                @endif

                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="behavior_id"
            label="Behavior Type"
            class="form-control select2"
            required
        >
            @php $selected = old('behavior_id', ($editing ? $keyPeformanceIndicator->behavior_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Behavior Type</option>
            @foreach($behaviors as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->translation_id }}" {{ $selected == $label->translation_id ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <!-- <label class="form-controll">Operational Office</label>

    <select id="oper" name="oper" class="oper " required="required"></select>

<select name="select1" id="select1">
  <option value="" id="noww"></option>
  <option value="1">car</option>
  <option value="2">phone</option>
  <option value="3" id="not">tv</option>
</select>


<select name="select2" id="select2">
  <option value=""></option>
  <option value="1">toyota</option>
  <option value="1">nissan</option>
  <option value="1">bmw</option>
  <option value="2">Iphone</option>
  <option value="2">LG</option>
  <option value="2">Samsung</option>
  <option value="3">Philips</option>
  <option value="3">Samsung</option>
</select> -->




    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="reporting_period_type_id"
            label="Reporting Period Type"
            class="form-control select2"
            required
        >
            @php $selected = old('reporting_period_type_id', ($editing ? $keyPeformanceIndicator->reporting_period_type_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Reporting Period Type</option>
            @foreach($reportingPeriodTypes as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->reporting_period_type_id }}" {{ $selected == $label->reporting_period_type_id ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select
            name="kpi_type_id"
            label="KPI Type"
            class="form-control select2"
            required
        >
            @php $selected = old('kpi_type_id', ($editing ? $keyPeformanceIndicator->kpi_type_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the KPI Type</option>
            @foreach($kpiTypes as $value => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->type_id }}" {{ $selected == $label->type_id ? 'selected' : '' }} >{{ $label->name }}</option>
                @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.select name="created_by_id" label="User" required>
            @php $selected = old('created_by_id', ($editing ? $keyPeformanceIndicator->created_by_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the User</option>
            @foreach($users as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group> --}}

    @foreach($languages as $key => $lang)


    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name_'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            value="{{ $kpiTranslations[$lang->locale][0]->name ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group>

     {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'out_put_'.$lang->locale}}"
            label="{{'Output in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'output in '.$lang->name}}"
            value="{{ $kpiTranslations[$lang->locale][0]->out_put ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group>

     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'out_come_'.$lang->locale}}"
            label="{{'Outcome in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'outcome in '.$lang->name}}"
            value="{{ $kpiTranslations[$lang->locale][0]->out_come ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group> --}}

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description_'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            maxlength=""
            required>{{ $kpiTranslations[$lang->locale][0]->description ?? '' }}
              </x-inputs.textarea
        >
    </x-inputs.group>
 @endforeach


    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="weight"
            label="Weight"
            :value="old('weight', ($editing ? $keyPeformanceIndicator->weight : ''))"
            max="255"
            step="0.01"
            placeholder="Weight"
            {{-- required --}}
        ></x-inputs.number>
    </x-inputs.group>
</div>
{{--
@foreach($offices as $value => $label)
                @if(app()->getLocale() == $label->locale)
                   <select id="main[{{$label->id}}]" class="main">
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                    <select>
                @endif

                <select id='sub[{{$label->id}}]' class='sub'>
   <option value="1">1</option>
   <option value="2">2</option>
</select>
            @endforeach



<select id="options">
  <option value="" disabled selected>Select an option</option>
  <option value="Option 1">Option 1</option>
  <option value="Option 2">Option 2</option>
  <option value="Option 3">Option 3</option>
</select>

<select id="choices">
  <option value="" disabled selected>Please select an option</option>
</select>


 <script>


// Map your choices to your option value
var lookup = {
   'Option 1': ['Option 1 - Choice 1', 'Option 1 - Choice 2', 'Option 1 - Choice 3'],
   'Option 2': ['Option 2 - Choice 1', 'Option 2 - Choice 2'],
   'Option 3': ['Option 3 - Choice 1'],
};

// When an option is changed, search the above for matching choices
$('#options').on('change', function() {
   // Set selected option as variable
   var selectValue = $(this).val();

   // Empty the target field
   $('#choices').empty();
   //alert(selectValue);
   // For each chocie in the selected option
             // console.log(principal)

    $.ajax({
    url:"/officefetch",
    method: "POST",
    data: {
    principal: selectValue
    },
     success: function (data) {
      $('#choices').html(data);
    }
    });
});



		$(() => {
            $('.offices').trigger('change')
            })
            $('.offices').change(function () {
            //$('.choose').prop('disabled', true);

            var principal = $('.offices').val()
            alert(principal);
            // console.log(principal)
            var select = $('.oper')

            $.ajax({
            url:"{{ route('officefetch') }}",
            method: "POST",
            data: {
            principal: principal
            },
            dataType: "json",
            success: function (data) {
            $('.oper').children().remove()
            for (let [key, value] of Object.entries(data)) {
                select.append('<option>' + value.name + '</option>');
                $("#oper").prop("required", true)


            }
            }
            });
            })


$('.offices2').change(function() {
    var id = $(this).val(); //get the current value's option
    $.ajax({
        type:'POST',
        url:'<path>/getCountries',
        data:{'id':id},
        success:function(data){
            //in here, for simplicity, you can substitue the HTML for a brand new select box for countries
            //1.
            $(".countries_container").html(data);

           //2.
           // iterate through objects and build HTML here
        }
    });
});




$('.main').change(function() {
    var options = '';
    if($(this).val() == 'a') {
        options = '<option value="1">1</option>';
    }
    else if ($(this).val() == 'b'){
        options = '<option value="2">2</option>';
    }

    $('.sub').html(options);
});


     $("#select1").change(function() {

  if ($(this).data('options') == undefined) {
    /*Taking an array of all options-2 and kind of embedding it on the select1*/
    $(this).data('options', $('#select2 option').clone());
  }
  var id = $(this).val();
  alert(id);
  var options = $(this).data('options').filter('[value=' + id + ']');
  $('#select2').html(options);
});


</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}
