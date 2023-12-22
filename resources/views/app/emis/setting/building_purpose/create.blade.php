@extends('layouts.app')
@section('title', 'Create Perspective')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('emis.setting.building.purpose') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                > </a>
               {{"Create Building Purpose"}}
            </h4>

            <x-form
                method="POST"
                action="{{ route('emis.setting.building.purpose.store') }}"
                class="mt-4"
            >
                @include('app.emis.setting.building_purpose.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('perspectives.index') }}"
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
