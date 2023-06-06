@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('kpi-child-two-translations.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.kpi_child_two_translations.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.kpi_child_two_translations.inputs.name')
                    </h5>
                    <span>{{ $kpiChildTwoTranslation->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.kpi_child_two_translations.inputs.description')
                    </h5>
                    <span
                        >{{ $kpiChildTwoTranslation->description ?? '-' }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.kpi_child_two_translations.inputs.kpi_child_two_id')
                    </h5>
                    <span
                        >{{ optional($kpiChildTwoTranslation->kpiChildTwo)->id
                        ?? '-' }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('kpi-child-two-translations.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\KpiChildTwoTranslation::class)
                <a
                    href="{{ route('kpi-child-two-translations.create') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
