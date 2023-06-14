@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <x-form
                method="POST"
                action="{{ route('kpi-Chain-save') }}"
                class="mt-4"
            >
                 @include('app.key_peformance_indicators.kpi-chain')

                <div class="mt-4">
                    <a
                        href="{{ route('key-peformance-indicators.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <button type="submit" class="btn btn-primary float-right">
                        <i class="icon ion-md-save"></i>
                        Add
                    </button>


                </div>
            </x-form>
             <div class="table-responsive">
                           <h4 class="card-title"><u>
                             Added sub chain for Key performance indicator({{ $keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name}})</u>
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
                                @forelse($child_one_adds as $child_one_add)
                                     <tr>
                                    <td>
                                        {{
                                        $child_one_add->kpiChildOneTranslations[0]->name
                                        ?? '-' }}
                                    </td>
                                    <td>
                                        {{
                                        $child_one_add->kpiChildOneTranslations[0]->description
                                        ?? '-' }}
                                    </td>
                                    <td>
                                         @can('delete',
                                        $child_one_add)
                                        <form
                                            action="{{ route('kpi-Chain-remove', [$keyPeformanceIndicator,$child_one_add]) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                        >
                                            @csrf @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn btn-light text-danger"
                                            >
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
