@extends('layouts.app')
@section('title', 'KPI Chain Three')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <a href="{{ route('key-peformance-indicators.index') }}" class="mr-4"><i
                            class="icon ion-md-arrow-back"></i></a>
                            Add Offices for ({{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name }})
                </h4>
                <br>
                <x-form method="POST" action="{{ route('kpi-assign-tooffices-save') }}" class="mt-4">
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="icon ion-md-save"></i>
                            Add
                        </button>
                    @include('app.key_peformance_indicators.office-assign-form-input')

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
                        Added Office list - ({{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name }})</u>
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
                                    parent offices
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
                            @forelse($officesAdds as $officesAdd)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        {{ $officesAdd->officeTranslations[0]->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $officesAdd->office->officeTranslations[0]->name ?? '-' }}
                                    </td>
                                    <td>

                                        @if(auth()->user() && auth()->user()->hasPermission('view keypeformanceindicators'))
                                        @php
                                            $office_has_plan =  getOfficePlanForCascadeRemove($keyPeformanceIndicator->id, $officesAdd->id,$planning_year->id);
                                        @endphp
                                            @if($office_has_plan)
                                             <form
                                                action="{{ route('kpi-remove-from-office', [$keyPeformanceIndicator, $officesAdd]) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-light text-danger">
                                                    <i class="icon ion-md-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="submit" class="btn btn-light text-danger" disabled title="Office has plan">
                                                <i class="icon ion-md-trash"></i>
                                            @endif
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
