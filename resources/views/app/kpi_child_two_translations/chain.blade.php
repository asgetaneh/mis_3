@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <a href="{{ route('kpi-child-one-translations.index') }}" class="mr-4"><i
                            class="icon ion-md-arrow-back"></i></a>
                    Add sub chain for Key performance indicator child ({{ $KpiChildTwo->kpiChildTwoTranslations[0]->name }})
                </h4>

                <x-form method="POST" action="{{ route('kpi-chain-three.store') }}" class="mt-4">
                    @include('app.kpi_child_two_translations.kpi-two-chain')

                    <div class="mt-4">
                        <a href="{{ route('kpi-child-one-translations.index') }}" class="btn btn-light">
                            <i class="icon ion-md-return-left text-primary"></i>
                            @lang('crud.common.back')
                        </a>

                        <button type="submit" class="btn btn-primary float-right">
                            <i class="icon ion-md-save"></i>
                            Add
                        </button>
                    </div>
                </x-form>

                {{-- below code to be worked later --}}
                <div class="table-responsive">
                    <h4 class="card-title"><u>
                        Add sub chain for Key performance indicator child ({{ $KpiChildTwo->kpiChildTwoTranslations[0]->name }})</u>
                    </h4>
                    <table class="table table-borderless table-hover">
                        <thead>
                            <tr>
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
                            @forelse($childThreeAdds as $childThreeAdd)
                                <tr>
                                    <td>
                                        {{ $childThreeAdd->kpiChildThreeTranslations[0]->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $childThreeAdd->kpiChildThreeTranslations[0]->description ?? '-' }}
                                    </td>
                                    <td>
                                        @can('delete', $childThreeAdd)
                                            <form
                                                action="{{ route('kpi-chain-three.remove', [$KpiChildTwo, $childThreeAdd]) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-light text-danger">
                                                    <i class="icon ion-md-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="">
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
