@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('office-translations.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.office_translations.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>
                        @lang('crud.office_translations.inputs.translation_id')
                    </h5>
                    <span
                        >{{ optional($officeTranslation->office)->id ?? '-'
                        }}</span
                    >
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.office_translations.inputs.name')</h5>
                    <span>{{ $officeTranslation->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>
                        @lang('crud.office_translations.inputs.description')
                    </h5>
                    <span>{{ $officeTranslation->description ?? '-' }}</span>
                </div>

                <div class="mb-4">
                    <h5>
                        Manager
                    </h5>
                    {!! $officeTranslation->office->users[0]->name ?? '-' !!}
                    {{-- @if ($officeTranslation->office->users->count() > 0)
                        <form action="{{ route('office-manager.remove', $officeTranslation->office->users[0]->id) }}" class="d-inline" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-danger ml-3">Revoke</button>
                        </form>
                    @endif --}}
                </div>

            </div>

            <div class="mt-4">
                <a
                    href="{{ route('office-translations.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\OfficeTranslation::class)
                <a
                    href="{{ route('office-translations.create') }}"
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
