@extends('layouts.app')
@section('title', 'Edit Task')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('tasks.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                Edit Task
            </h4>

            <x-form
                method="PUT"
                action="{{ route('tasks.update', $task) }}"
                class="mt-4"
            >
                @include('app.task.edit-form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('tasks.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <a
                        href="{{ route('tasks.create') }}"
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
