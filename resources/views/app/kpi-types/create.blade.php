@extends('layouts.app')
@section('title', 'Create KPI Type')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('types.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Create KPI Type
            </h4>
            <br>
            <x-form
                method="POST"
                action="{{ route('types.store') }}"
                class="mt-4"
            >
                @include('app.kpi-types.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('types.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <button type="submit" class="btn btn-primary float-right">
                        <i class="icon ion-md-save"></i>
                        @lang('crud.common.create')
                    </button>
                </div>
            </x-form>
        </div>
    </div>
</div>
@endsection
