@php $editing = isset($keyPeformanceIndicator) @endphp

<div class="row">

    {{-- <h5 class="col-sm-12 mt-4">KPI name: {{ $KpiChildOne->kpiChildOneTranslations[0]->name}}</h5> --}}

    {{-- <x-inputs.group class="col-sm-12">

        <x-inputs.select class="select2" multiple="multiple" name="kpi_one_child[]" data-placeholder="Select KPI child" label="KPI child one">
                            <option value="{{ $KpiChildOne->id }}">{{ $KpiChildOne->kpiChildOneTranslations[0]->name }}</option>
        </x-inputs.select>

    </x-inputs.group> --}}

    <x-inputs.group class="col-sm-12">

        <input type="hidden" value="{{ $offices[0]->id }}" name="office">

        <x-inputs.select required class="select2" multiple="multiple" name="users[]" data-placeholder="Select office " label="Office list"> 
            @forelse($users as $key => $label)
                     <option value="{{ $label->id }}">{{ $label->name }}</option>
             @empty
                <option value="" disabled>No user list</option>
            @endforelse
        </x-inputs.select>

    </x-inputs.group>

</div>
