@php $editing = isset($keyPeformanceIndicator) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
     <input type="hidden" value="{{ $keyPeformanceIndicator->id }}" name="keyPeformanceIndicator">

        <x-inputs.select class="select2 {{ $errors->has('locations') ? 'is-invalid' : '' }}" data-placeholder="Select disaggregation level one" name="kpi_one_child[]" id="locations" multiple>
                    @foreach($KpiChildOne as $key => $value)
                    @if(app()->getLocale() == $value->locale)
                        <option value="{{ $value->kpiChildOne_id }}" {{ in_array($key, old('locations', [])) ? 'selected' : '' }}>{{ $value->name }}</option>
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
</div>
