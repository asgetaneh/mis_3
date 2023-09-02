@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('strategies.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.strategies.edit_title')
            </h4>

            <x-form
                method="PUT"
                action="{{ route('strategies.update', $strategy) }}"
                class="mt-4"
            >
                @include('app.strategies.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('strategies.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <a
                        href="{{ route('strategies.create') }}"
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

        $('.select2').select2();

    });
</script>
@endsection
