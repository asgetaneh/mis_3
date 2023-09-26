@extends('layouts.app')
@section('title', 'Reporting Goals')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-2">

        <div class="card border border-secondary">
            <div class="card-header bg-info h5">Goal List</div>
            <div class="card-body border rounded" style="background-color: #e9ecef;">
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <table class="table table-borderless table-hover">
                    <tbody>

                        @forelse($kpis['goal'] as $goal)
                        <tr>
                            {{-- <td> --}}
                                 <a class="border border-secondary btn btn-light btn-block text-left {{ Request::is('smis/report/get-objectives/'.$goal->id) ? 'bg-primary' : '' }}" href="{{ route('get-objectives-reporting', $goal->id) }}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                {{
                                optional($goal->goalTranslations[0])->name
                                ?? '-' }}
                            </a>
                            {{-- </td> --}}


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
                             </td>
                        </tr>
                    </tfoot>
                </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-10">

         <div class="card" class="collapse">
            <div class="card-header">Management information systems (MIS) - Reporting Area</div>
            <div class="card-body">
                   {{-- <table class="table table-borderless table-hover">
                    <tbody>

                        @forelse($suitableKpis as $suitable)
                        <tr>
                            <td>
                                <a>
                                    {{
                                    optional($suitable->keyPeformanceIndicator->keyPeformanceIndicatorTs[0])->name
                                    ?? '-' }}
                                </a>
                            </td>

                        </tr>
                        <tr>
                            <td>

                            @if($suitable->keyPeformanceIndicator->kpiChildOnes->isEmpty())
                                {{
                                optional($suitable->keyPeformanceIndicator->kpiChildOnes)->id
                                ?? '-' }}
                                @else

                                 <table class="table table-stripe ">
                                     <tr>

                                      @forelse($suitable->keyPeformanceIndicator->kpiChildOnes as $kpiChildOne)
                                             <tr>
                                                <td></td>
                                                @forelse($kpiChildOne->kpiChildTwos as $kpiChildTwo)

                                                    <td>
                                                        {{$kpiChildTwo->kpiChildTwoTranslations[0]->name}}
                                                    </td>
                                                @empty
                                                @endforelse
                                                </tr>


                                        <tr>
                                        <td>
                                            {{$kpiChildOne->kpiChildOneTranslations[0]->name}}
                                        </td>
                                        </tr>
                                    @empty
                                    @endforelse

                                     </tr>


                                 </table>


                                @endif
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
                                </td>
                        </tr>
                    </tfoot>
                </table> --}}

                @if (count($kpis['goal']) > 0)
                    <div class="callout callout-info">
                        {{-- <h5>I am an info callout!</h5> --}}
                        <p>Please select a Goal from left menu for reporting!</p>
                    </div>
                    {{-- <h4 class="">Please select a Goal from left menu to start planning!</h4> --}}
                @else
                    <div class="callout callout-info">
                        {{-- <h5>I am an info callout!</h5> --}}
                        <p>Nothing to show here!</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
