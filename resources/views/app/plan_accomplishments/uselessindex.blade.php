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
                @can('create', App\Models\PlanAccomplishment::class)
                <a
                    href="{{ route('plan-accomplishments.create') }}"
                    class="btn btn-primary"
                >
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between;">
                <h4 class="card-title">
                    @lang('crud.plan_accomplishments.index_title')
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th class="text-left">
                                @lang('crud.plan_accomplishments.inputs.kpi')
                            </th>
                            <th class="text-left">
                                @lang('crud.plan_accomplishments.inputs.reporting_period_id')
                            </th>
                            <!-- <td>
                             @forelse($planAccomplishments as $planAccomplishment)
                               @forelse($planAccomplishment->Kpi-> kpiChildOnes as $choneT)
                                         {{$choneT->id}}
                                 @empty
                             @endforelse
                             @empty
                             @endforelse
                            </td> -->
                            <th class="text-right">
                                @lang('crud.plan_accomplishments.inputs.plan_value')
                            </th>
                            <th class="text-right">
                                @lang('crud.plan_accomplishments.inputs.accom_value')
                            </th>
                            <th class="text-left">
                                @lang('crud.plan_accomplishments.inputs.plan_status')
                            </th>
                            <th class="text-left">
                                @lang('crud.plan_accomplishments.inputs.accom_status')
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($planAccomplishments as $planAccomplishment)
                        <tr>
                            <td>
                              @forelse($planAccomplishment->Kpi-> keyPeformanceIndicatorTs as $kpi)
                                @if(app()->getLocale() == $kpi->locale) 
                                        {{$kpi->name}}
                                @endif
                                @empty
                             @endforelse
                            </td>
                               @forelse($planAccomplishment->Kpi->kpiChildOnes as $chone)
                               <tr>
                               <td>
                                 @forelse($chone->kpiChildOneTranslations as $choneT)
                                  @if(app()->getLocale() == $choneT->locale) 
                                        {{$choneT->name}}
                                @endif
                                  @empty
                             @endforelse
                            </td>
                            </tr>
                              @empty
                             @endforelse
                            <td>
                            @forelse($planAccomplishment->reportingPeriod->reportingPeriodTs as $rperiod)
                                @if(app()->getLocale() == $rperiod->locale) 
                                        {{$rperiod->name}}
                                @endif
                                @empty
                             @endforelse
                             </td>
                             
                            <td>
                                {{ $planAccomplishment->plan_value ?? '-' }}
                            </td>
                            <td>
                                {{ $planAccomplishment->accom_value ?? '-' }}
                            </td>
                            <td>
                                {{ $planAccomplishment->plan_status ?? '-' }}
                            </td>
                            <td>
                                {{ $planAccomplishment->accom_status ?? '-' }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    @can('update', $planAccomplishment)
                                    <a
                                        href="{{ route('plan-accomplishments.edit', $planAccomplishment) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $planAccomplishment)
                                    <a
                                        href="{{ route('plan-accomplishments.show', $planAccomplishment) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan @can('delete', $planAccomplishment)
                                    <form
                                        action="{{ route('plan-accomplishments.destroy', $planAccomplishment) }}"
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
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                {!! $planAccomplishments->render() !!}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
