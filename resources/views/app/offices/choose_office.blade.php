@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">

                {{-- @lang('crud.offices.create_title') --}}
            </h4>

            <x-form
                method="POST"
                action="{{ route('assign-office') }}"
                class="mt-4"
            >
                     <input type="hidden" value="{{ $user->id }}" name="user">

                 <x-inputs.group class="col-sm-12">
                    <x-inputs.select name="urofficel" class="form-control select2" label="Office" required>

                        <option disabled value="" selected>Please select the Office</option>
                        @foreach($offices as $value => $label)
                            @if ($label->translation_id == 1)
                                @continue
                            @else
                                <option value="{{ $label->id }}"  >{{ $label->officeTranslations[0]->name }}</option>
                            @endif
                        @endforeach
                    </x-inputs.select>
                    @if (session()->has('error'))
                            <p class="text-danger mb-0">{{ session('error') }}</p>
                        @endif
                </x-inputs.group>
                 {{-- <x-inputs.group class="col-sm-12">
                    <x-inputs.select name="urofficell" label="Office">

                        <option>Please select the Office</option>
                        @foreach($ll_offices as $value => $label)
                        <option value="{{ $label->id }}"  >{{ $label->officeTranslations[0]->name }}</option>
                        @endforeach
                    </x-inputs.select>
                </x-inputs.group>
                 <x-inputs.group class="col-sm-12">
                    <x-inputs.select name="urofficelll" label="Office">

                        <option>Please select the Office</option>
                         @foreach($lll_offices as $value => $label)
                        <option value="{{ $label->id }}"  >{{ $label->officeTranslations[0]->name }}</option>
                        @endforeach
                    </x-inputs.select>
                </x-inputs.group> --}}

                <div class="mt-4">
                    {{-- <a
                        href="{{ route('login') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a> --}}

                    <button type="submit" class="btn btn-primary float-right">
                        <i class="icon ion-md-save"></i>
                        Choose
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
