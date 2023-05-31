@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('objectives.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.objectives.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.objectives.inputs.goal_id')</h5>
                    <span>{{ optional($objective->goal)->id ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.objectives.inputs.perspective_id')</h5>
                    <span
                        >{{ optional($objective->perspective)->id ?? '-'
                        }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.objectives.inputs.created_by_id')</h5>
                    <span>{{ optional($objective->user)->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.objectives.inputs.updated_by_id')</h5>
                    <span>{{ optional($objective->user2)->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.objectives.inputs.weight')</h5>
                    <span>{{ $objective->weight ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('objectives.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Objective::class)
                <a
                    href="{{ route('objectives.create') }}"
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
