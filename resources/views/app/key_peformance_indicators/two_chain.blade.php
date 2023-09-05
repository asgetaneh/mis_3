@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <a href="{{ route('key-peformance-indicators.index') }}" class="mr-4"><i
                            class="icon ion-md-arrow-back"></i></a>
                            Add Disaggregation Level Two for ({{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name }})
                </h4>
                <br>
                <x-form method="POST" action="{{ route('kpi-chain-two.store') }}" class="mt-4">
                    @include('app.key_peformance_indicators.two-chain_form-inputs')

                    <div class="mt-4">
                        <a href="{{ route('key-peformance-indicators.index') }}" class="btn btn-light">
                            <i class="icon ion-md-return-left text-primary"></i>
                            @lang('crud.common.back')
                        </a>

                        <button type="submit" class="btn btn-primary float-right">
                            <i class="icon ion-md-save"></i>
                            Add
                        </button>
                    </div>
                </x-form>
                <br>
                <div class="table-responsive">
                    <h4 class="card-title"><u>Added disaggregation list - ({{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name}})</u></u>
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
                                    Description
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
                            @forelse($childTwoAdds as $childTwoAdd)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        {{ $childTwoAdd->kpiChildTwoTranslations[0]->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $childTwoAdd->kpiChildTwoTranslations[0]->description ?? '-' }}
                                    </td>
                                    <td>
                                             <form
                                                action="{{ route('kpi-chain-two.remove', [$keyPeformanceIndicator, $childTwoAdd]) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-light text-danger">
                                                    <i class="icon ion-md-trash"></i>
                                                </button>
                                            </form>
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
