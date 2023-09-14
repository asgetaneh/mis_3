@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('strategy-translations.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.strategy_translations.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.strategy_translations.inputs.name')</h5>
                    <span>{{ $strategyTranslation->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.strategy_translations.inputs.discription')
                    </h5>
                    <span>{{ $strategyTranslation->discription ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.strategy_translations.inputs.translation_id')
                    </h5>
                    <span
                        >{{ optional($strategyTranslation->strategy)->id ?? '-'
                        }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('strategy-translations.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\StrategyTranslation::class)
                <a
                    href="{{ route('strategy-translations.create') }}"
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
