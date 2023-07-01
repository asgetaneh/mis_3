@php $editing = isset($keyPeformanceIndicator) @endphp

<div class="row">
    <h4 class="card-title">
        <a
            href="{{ route('key-peformance-indicators.index') }}"
            class="mr-4"
            ><i class="icon ion-md-arrow-back"></i
        ></a>
        Add sub chain for Key performance indicator({{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name}})
    </h4>
    <x-inputs.group class="col-sm-12">
     <input type="hidden" value="{{ $keyPeformanceIndicator->id }}" name="keyPeformanceIndicator">

        <x-inputs.select class="select2 {{ $errors->has('locations') ? 'is-invalid' : '' }}" name="kpi_one_child[]" id="locations" multiple>
                    @foreach($KpiChildOne as $key => $value)
                        <option value="{{ $value->id }}" {{ in_array($key, old('locations', [])) ? 'selected' : '' }}>{{ $value-> kpiChildOneTranslations[0]->name }}</option>

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
</div>
