@extends('layouts.app')
@section('title', 'Create Task Measurements')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('TaskMeasurements.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
               {{"Create Task"}}
            </h4>

            <x-form
                method="POST"
                action="{{ route('TaskMeasurements.store') }}"
                class="mt-4"
            >
                @include('app.taskmeasurement.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('TaskMeasurements.index') }}"
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
<script>
    $(document).ready(function() {

        $('.select2').select2();

    });
</script>
@endsection
