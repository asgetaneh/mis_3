@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('goals.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.goals.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.goals.inputs.is_active')</h5>
                    <span>{{ $goal->is_active ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.goals.inputs.created_by_id')</h5>
                    <span>{{ $goal->created_by_id ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('goals.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Goal::class)
                <a href="{{ route('goals.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
