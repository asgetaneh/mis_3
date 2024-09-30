@php $editing = isset($kpiMeasurement) @endphp

<div class="row">
   {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.select name="kpiChildOne_id" label="Kpi Child One" required>
            @php $selected = old('kpiChildOne_id', ($editing ? $kpiChildOneTranslation->kpiChildOne_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Kpi Child One</option>
            @foreach($kpiChildOnes as $value => $label)
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
                value="{{ $measurementTranslations[$lang->locale][0]->name ?? '' }}"
                required
            ></x-inputs.text>
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.textarea
                name="{{'description_'.$lang->locale}}"
                label="{{'Description in '.$lang->name}}"
                maxlength=""
                placeholder="Description"
                >{{ $measurementTranslations[$lang->locale][0]->description ?? '' }}
                </x-inputs.textarea
            >
        </x-inputs.group>

        {{-- <x-inputs.group class="col-sm-12">
            <x-inputs.text
                name="{{'slug_'.$lang->locale}}"
                label="{{'Slug in '.$lang->name}}"
                maxlength="255"
                placeholder="{{'slug in '.$lang->name}}"
                value="{{ $measurementTranslations[$lang->locale][0]->slug ?? '' }}"
                required
            ></x-inputs.text>

            <span></span>
        </x-inputs.group> --}}

        <x-inputs.group class="col-sm-12">
            <x-inputs.select name="slug" label="Slug" class="form-control select2" required>
                @php $selected = old('slug', ($editing ? $kpiMeasurement->measurement->slug : '')) @endphp
                <option disabled {{ empty($selected) ? 'selected' : '' }} value="">Please select the measurement</option>
                <option value="number" {{ $editing ? ($kpiMeasurement->measurement->slug == 'number' ? 'selected' : '') : '' }}>Number</option>
                <option value="percent" {{ $editing ? ($kpiMeasurement->measurement->slug == 'percent' ? 'selected' : '') : '' }}>Percent</option>
                <option value="ratio" {{  $editing ? ($kpiMeasurement->measurement->slug == 'ratio' ? 'selected' : '') : ''  }}>Ratio</option>
             </x-inputs.select>
        </x-inputs.group>

    @endforeach

</div>
