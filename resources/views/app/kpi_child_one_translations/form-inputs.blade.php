@php $editing = isset($kpiChildOneTranslation) @endphp

<div class="row">
   <!--  <x-inputs.group class="col-sm-12">
        <x-inputs.select name="kpiChildOne_id" label="Kpi Child One" required>
            @php $selected = old('kpiChildOne_id', ($editing ? $kpiChildOneTranslation->kpiChildOne_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Kpi Child One</option>
            @foreach($kpiChildOnes as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group> -->
     @foreach($languages as $key => $lang)

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name_'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            value="{{ $childOneTranslations[$lang->locale][0]->name ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
         <x-inputs.textarea
            name="{{'description_'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            maxlength=""
            placeholder="Description"
            required>{{ $childOneTranslations[$lang->locale][0]->description ?? '' }}
              </x-inputs.textarea
        >
    </x-inputs.group>
     @endforeach

</div>
