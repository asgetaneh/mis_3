@extends('layouts.app')
@section('title', 'Edit Reporting Period')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('reporting-periods.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.reporting_periods.edit_title')
            </h4>

            <x-form
                method="PUT"
                autocomplete="off"
                spellcheck="false"
                action="{{ route('reporting-periods.update', $reportingPeriod) }}"
                class="mt-4"
            >
                @include('app.reporting_periods.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('reporting-periods.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <a
                        href="{{ route('reporting-periods.create') }}"
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

<script>

    $(document).ready(function() {

        // sets the date input to Ethiopian using keith wood jquery plugin
        var calendar = $.calendars.instance('ethiopian', 'am');
        $('#start_date').calendarsPicker({calendar: calendar});
        $('#end_date').calendarsPicker({calendar: calendar});

    });
</script>

<script>
    $(document).ready(function() {

        $('.select2').select2();

    });
</script>

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/inputmask/jquery.inputmask.bundle.js') }}"></script>

    <script>

        $(":input[data-inputmask-mask]").inputmask();
        $(":input[data-inputmask-alias]").inputmask();
        $(":input[data-inputmask-regex]").inputmask("Regex");

    </script>

@endsection
