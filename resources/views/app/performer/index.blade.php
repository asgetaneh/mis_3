@extends('layouts.app')
@section('title', 'KPI Chain Three')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    {{-- <a href="{{ route('key-peformance-indicators.index') }}" class="mr-4"><i
                            class="icon ion-md-arrow-back"></i></a> --}}
                            Add Performer  for ({{ $offices[0]->officeTranslations[0]->name }}) Office
                </h4>
                <br>
                <x-form method="POST" action="{{ route('performer-add-tooffices-save') }}" class="mt-4">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="icon ion-md-save"></i>
                            Add
                        </button>
                    @include('app.performer.form-input')

                    <div class="mt-4">
                        <a href="{{ route('key-peformance-indicators.index') }}" class="btn btn-light">
                            <i class="icon ion-md-return-left text-primary"></i>
                            @lang('crud.common.back')
                        </a>                       
                    </div>
                </x-form>
                <br>
                <div class="table-responsive">
                    <h4 class="card-title"><u>
                        Added performer list - ({{ $offices[0]->officeTranslations[0]->name }})</u>
                    </h4>
                    <div class="p-3"></div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                    Name
                                </th>
                               
                                <th class="text-left">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1; 
                            @endphp
                            @forelse($operformerAdds as $operformerAdd)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        {{ $operformerAdd->user->name ?? '-' }}
                                    </td>
                                     
                                    <td>
                                        @php
                                        $user = auth()->user();
                                        @endphp 
                                        @if($user->hasPermission('view keypeformanceindicators'))
                                             <form
                                                action="{{ route('performer-remove-from-office', $operformerAdd) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-light text-danger">
                                                    <i class="icon ion-md-trash"></i>
                                                </button>
                                            </form>
                                         @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('.select2').select2();

        });
    </script>
@endsection
