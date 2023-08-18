@extends('layouts.app')
@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-2">

        <div class="card">
            <div class="card-header">Goal List</div>
            <div class="card-body">
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <table class="table table-borderless table-hover">
                    <tbody>

                        @forelse($kpis['goal'] as $goal)
                        <tr>
                            {{-- <td> --}}
                                 <a class="border btn btn-light btn-block text-left {{ Request::is('smis/plan/get-objectives/'.$goal->id) ? 'bg-primary' : '' }}" href="{{ route('get-objectives', $goal->id) }}" role="button" aria-expanded="false" aria-controls="collapseExample">
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
            <div class="card-header">Management information systems (MIS)</div>
            <div class="card-body">
                   <table class="table table-borderless table-hover">
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
                </table>

            </div>
        </div>
    </div>
</div>

@endsection
