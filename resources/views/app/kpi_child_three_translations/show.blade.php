@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('kpi-child-three-translations.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Show Disaggregation Three
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        Name
                    </h5>
                    @forelse($kpiChildThreeTranslation->kpiChildThree->kpiChildThreeTranslations as $key => $three)
                    @if (app()->getLocale() == $three->locale)
                        <p class="">{{ $three->name ?? '-' }}</p>
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
                    @forelse($kpiChildThreeTranslation->kpiChildThree->kpiChildThreeTranslations as $key => $three)
                    @if (app()->getLocale() == $three->locale)
                        <p class="">{{ $three->description ?? '-' }}</p>
                    @endif
                @empty
                    <p>-</p>
                @endforelse
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('kpi-child-three-translations.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\KpiChildThreeTranslation::class)
                <a
                    href="{{ route('kpi-child-three-translations.create') }}"
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
