@extends('layouts.app')
@section('title', 'Edit Reporting Period Type')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('reporting-period-types.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.reporting_period_types.edit_title')
            </h4>

            <x-form
                method="PUT"
                action="{{ route('reporting-period-types.update', $reportingPeriodType) }}"
                class="mt-4"
            >
                @include('app.reporting_period_types.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('reporting-period-types.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <a
                        href="{{ route('reporting-period-types.create') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-add text-primary"></i>
                        @lang('crud.common.create')
                    </a>

                    <button type="submit" class="btn btn-primary float-right">
                        <i class="icon ion-md-save"></i>
                        @lang('crud.common.update')
                    </button>
                </div>
            </x-form>
        </div>
    </div>
</div>
@endsection
