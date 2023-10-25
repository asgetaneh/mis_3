@php $editing = isset($keyPeformanceIndicator) @endphp

<div class="row">

    {{-- <h5 class="col-sm-12 mt-4">KPI name: {{ $KpiChildOne->kpiChildOneTranslations[0]->name}}</h5> --}}

    {{-- <x-inputs.group class="col-sm-12">

        <x-inputs.select class="select2" multiple="multiple" name="kpi_one_child[]" data-placeholder="Select KPI child" label="KPI child one">
                            <option value="{{ $KpiChildOne->id }}">{{ $KpiChildOne->kpiChildOneTranslations[0]->name }}</option>
        </x-inputs.select>

    </x-inputs.group> --}}

    <x-inputs.group class="col-sm-12">

        <input type="hidden" value="{{ $keyPeformanceIndicator->id }}" name="kpiId">

        <x-inputs.select required class="select2" multiple="multiple" name="kpiofficeLists[]" data-placeholder="Select office " label="Office list"> 
            @forelse($office_t as $key => $label)
                @if(app()->getLocale() == $label->locale)
                    <option value="{{ $label->office->id }}">{{ $label->name }}</option>
                @endif
            @empty
                <option value="" disabled>No offices list</option>
            @endforelse
        </x-inputs.select>

    </x-inputs.group>

</div>
