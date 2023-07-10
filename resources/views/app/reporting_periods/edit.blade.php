@extends('layouts.app')

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

@endsection
