@php $editing = isset($keyPeformanceIndicator) @endphp

<div class="row">

    {{-- <h5 class="col-sm-12 mt-4">KPI name: {{ $KpiChildOne->kpiChildOneTranslations[0]->name}}</h5> --}}

    {{-- <x-inputs.group class="col-sm-12">

        <x-inputs.select class="select2" multiple="multiple" name="kpi_one_child[]" data-placeholder="Select KPI child" label="KPI child one">
                            <option value="{{ $KpiChildOne->id }}">{{ $KpiChildOne->kpiChildOneTranslations[0]->name }}</option>
        </x-inputs.select>

    </x-inputs.group> --}}

    <x-inputs.group class="col-sm-12">

        <input type="hidden" value="{{ $KpiChildTwo->id }}" name="kpiChildTwoId">

        <x-inputs.select class="select2" multiple="multiple" name="kpiThreeLists[]" data-placeholder="Select KPIs" label="KPI child three">
            @forelse($KpiChildThree as $key => $label)
                @if(app()->getLocale() == $label->kpiChildThreeTranslations[0]->locale)
                    <option value="{{ $label->kpiChildThreeTranslations[0]->kpiChildThree_id }}">{{ $label->kpiChildThreeTranslations[0]->name }}</option>
                @endif
            @empty
                <option value="" disabled>No kpi child three</option>
            @endforelse
        </x-inputs.select>

    </x-inputs.group>

</div>
