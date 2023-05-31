@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('languages.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.languages.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.languages.inputs.name')</h5>
                    <span>{{ $language->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.languages.inputs.description')</h5>
                    <span>{{ $language->description ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.languages.inputs.locale')</h5>
                    <span>{{ $language->locale ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.languages.inputs.created_by_id')</h5>
                    <span>{{ optional($language->user)->name ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('languages.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Language::class)
                <a href="{{ route('languages.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
