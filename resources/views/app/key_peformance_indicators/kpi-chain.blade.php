@php $editing = isset($keyPeformanceIndicator) @endphp

<div class="row">

    <x-inputs.group class="col-sm-12">
     <input type="hidden" value="{{ $keyPeformanceIndicator }}" name="keyPeformanceIndicator"> {{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name}}

     <select class="form-control select2 {{ $errors->has('locations') ? 'is-invalid' : '' }}" name="kpi_one_child[]" id="locations" multiple>
                    @foreach($KpiChildOne as $id => $kpi_one)
                        <option value="{{ $kpi_one->id }}" {{ in_array($id, old('locations', [])) ? 'selected' : '' }}>{{ $kpi_one-> kpiChildOneTranslations[0]->name }}</option>

                    @endforeach
                </select>
      
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
