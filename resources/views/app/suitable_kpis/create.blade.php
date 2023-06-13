@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('suitable-kpis.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Choose office and reporting Planing Year
            </h4>

            <x-form
                method="POST"
                action="{{ route('suitable-kpi',0) }}"
                class="mt-4"
            >
                @include('app.suitable_kpis.form-inputs')

                <div class="mt-4">
                    <abs
                        href="{{ route('suitable-kpis.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <button type="submit" class="btn btn-primary float-right">
                        <i class="icon ion-md-save"></i>
                       Choose
                    </button>
                </div>
            </x-form>
        </div>
    </div>
</div>
@endsection
