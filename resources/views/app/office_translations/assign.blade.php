@extends('layouts.app')
@section('title', 'Office Assign')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <a href="{{ route('office-translations.index') }}" class="mr-4"><i
                            class="icon ion-md-arrow-back"></i></a>
                    Assign Manager
                </h4>

                <x-form method="POST" action="{{ route('office-assign.store') }}" class="mt-4">

                    <div class="row mt-5">
                        <x-inputs.group class="col-sm-12">
                            <x-inputs.select name="office" class="form-control select2" label="Office" required>
                                {{-- @php $selected = old('translation_id', ($editing ? $officeTranslation->translation_id : '')) @endphp --}}

                                <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Office</option>
                                @foreach ($offices as $value => $office)
                                    @if (app()->getLocale() == $office->locale)
                                        <option value="{{ $office->translation_id }}">
                                            {{ $office->name }}</option>
                                    @endif
                                @endforeach
                            </x-inputs.select>

                        </x-inputs.group>
                        <x-inputs.group class="col-sm-12">
                            <x-inputs.select name="user" class="form-control select2" label="User" required>
                                {{-- @php $selected = old('translation_id', ($editing ? $officeTranslation->translation_id : '')) @endphp --}}

                                <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the User</option>
                                @foreach ($users as $value => $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}</option>
                                @endforeach
                            </x-inputs.select>
                            @if (session()->has('error'))
                            <p class="text-danger mb-0">{{ session('error') }}</p>
                        @endif
                        </x-inputs.group>

                    </div>


                    <div class="mt-4">
                        <a href="{{ route('office-translations.index') }}" class="btn btn-light">
                            <i class="icon ion-md-return-left text-primary"></i>
                            @lang('crud.common.back')
                        </a>

                        <button type="submit" class="btn btn-success float-right">
                            {{-- <i class="icon ion-md-save"></i> --}}
                            Assign
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
