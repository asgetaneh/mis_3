@extends('layouts.app')
@section('title', 'Edit Objective')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('objectives.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.objectives.edit_title')
            </h4>

            <x-form
                method="PUT"
                action="{{ route('objectives.update', $objective) }}"
                class="mt-4"
            >
                @include('app.objectives.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('objectives.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <a
                        href="{{ route('objectives.create') }}"
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
