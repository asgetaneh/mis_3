@extends('layouts.app')

@section('content')
<div class="container">
    <div class="searchbar mt-0 mb-4">
        <div class="row">
            <div class="col-md-6">
                <form>
                    <div class="input-group">
                        <input
                            id="indexSearch"
                            type="text"
                            name="search"
                            placeholder="{{ __('crud.common.search') }}"
                            value="{{ $search ?? '' }}"
                            class="form-control"
                            autocomplete="off"
                        />
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon ion-md-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div> 
            <div class="col-md-6 text-right">
                @can('create', App\Models\SuitableKpi::class)
                 <x-form
                    method="POST"
                    action="{{ route('suitable-kpi',1) }}"
                    class="mt-4"
                >
                      @forelse ($suitableKpis as $suita)
                        <input type="hidden" value="{{ $suitableKpis[0]->office_id }}" name="user_managing_office"> 
                        <input type="hidden" value="{{ $suitableKpis[0]->planing_year_id }}" name="planing_year_id"> 
                        @empty

                        @endforelse
                        <button type="submit"  class="btn btn-warning" float-right">
                            <i class="icon ion-md-add"></i> Recover
                        </button>
                    </div>
                </x-form>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between;">
                <h4 class="card-title">
                    @lang('crud.suitable_kpis.index_title')
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th class="text-left">
                                @lang('crud.suitable_kpis.inputs.key_peformance_indicator_id')
                            </th>
                            <th class="text-left">
                                @lang('crud.suitable_kpis.inputs.office_id')
                            </th>
                            <th class="text-left">
                                @lang('crud.suitable_kpis.inputs.planing_year_id')
                            </th>
                            <th class="text-left">
                               Status
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suitableKpis as $suitableKpi)
                        <tr>
                            <td>
                             
                                 {{
                               $suitableKpi->keyPeformanceIndicator->keyPeformanceIndicatorTs[0]->name }}
                            </td>
                            <td>
                                {{ $suitableKpi->office->officeTranslations[0]->name ?? '-' }}
                            </td>
                            <td>
                                {{ $suitableKpi->planingYear->planingYearTranslations[0]->name ??
                                '-' }}
                            </td>
                            <td>
                                  <p class="bg-info text-white  text-center "> Suitable
                                  </p>
                            </td>
                            <td class="text-center" style="width: 134px;">
                                 
                                     @can('delete', $suitableKpi)
                                    <form
                                        action="{{ route('suitable-kpis.destroy', $suitableKpi) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ __('Are_you_sure to set KPI as not suitable') }}')"
                                    >
                                        @csrf @method('DELETE')
                                        <button  title="make un suitable"
                                            type="submit"
                                            class="btn btn-light text-danger"
                                        >
                                            <i class="icon ion-md-trash"> </i>
                                        </button>
                                    </form>
                                    @endcan
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
                    <tfoot>
                        <tr>
                            <td colspan="4">{!! $suitableKpis->render() !!}
                           
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
