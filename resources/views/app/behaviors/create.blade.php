@extends('layouts.app')
@section('title', 'Create KPI Behavior')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('behaviors.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Create Behavior
            </h4>

            <x-form
                method="POST"
                action="{{ route('behaviors.store') }}"
                class="mt-4"
            >
                @include('app.behaviors.form-inputs')

                <div class="mt-4">
                    <a href="{{ route('behaviors.index') }}" class="btn btn-light">
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <button type="submit" class="btn btn-primary float-right">
                        <i class="icon ion-md-save"></i>
                        Create
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
