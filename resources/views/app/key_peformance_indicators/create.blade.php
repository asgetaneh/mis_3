@extends('layouts.app')
@section('title', 'Create KPI')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a
                    href="{{ route('key-peformance-indicators.index') }}"
                    class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.key_peformance_indicators.create_title')
            </h4>

            <x-form
                method="POST"
                action="{{ route('key-peformance-indicators.store') }}"
                class="mt-4"
            >
                @include('app.key_peformance_indicators.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('key-peformance-indicators.index') }}"
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

<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {

        $('.select2').select2();

    });
</script>
