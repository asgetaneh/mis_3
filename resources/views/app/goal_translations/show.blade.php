@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('goal-translations.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.goal_translations.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.goal_translations.inputs.translation_id')
                    </h5>
                    <span
                        >{{ optional($goalTranslation->goal)->id ?? '-' }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.goal_translations.inputs.name')</h5>
                    <span>{{ $goalTranslation->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.goal_translations.inputs.out_put')</h5>
                    <span>{{ $goalTranslation->out_put ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.goal_translations.inputs.out_come')</h5>
                    <span>{{ $goalTranslation->out_come ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.goal_translations.inputs.description')</h5>
                    <span>{{ $goalTranslation->description ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.goal_translations.inputs.locale')</h5>
                    <span>{{ $goalTranslation->locale ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('goal-translations.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\GoalTranslation::class)
                <a
                    href="{{ route('goal-translations.create') }}"
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
