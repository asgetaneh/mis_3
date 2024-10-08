@extends('layouts.app')
@section('title', 'Show KPI Level One')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('kpi-child-one-translations.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Show Disaggregation One
            </h4>
            <br>
            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        Name
                    </h5>
                    @forelse($kpiChildOneTranslation->kpiChildOne->kpiChildOneTranslations as $key => $one)
                            @if (app()->getLocale() == $one->locale)
                                <p class="">{{ $one->name ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
                <hr>
                <div class="mb-4">
                    <h5>
                        Description
                    </h5>
                    @forelse($kpiChildOneTranslation->kpiChildOne->kpiChildOneTranslations as $key => $one)
                            @if (app()->getLocale() == $one->locale)
                                <p class="">{{ $one->description ?? '-' }}</p>
                            @endif
                        @empty
                            <p>-</p>
                        @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('kpi-child-one-translations.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\KpiChildOneTranslation::class)
                <a
                    href="{{ route('kpi-child-one-translations.create') }}"
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
