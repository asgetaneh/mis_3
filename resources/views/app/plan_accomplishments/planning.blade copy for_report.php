@extends('layouts.app')

@section('style')
    <style>
        tr,
        th,
        td {
            padding: 10px;
        }

        table {
            border-collapse: collapse;
        }
    </style>
@endsection

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
                                <td>
                                     <a class="btn btn-white" href='{{ url("/get-objectives/{$goal->id}") }}' role="button" aria-expanded="false" aria-controls="collapseExample">
                                    {{
                                    optional($goal->goalTranslations[0])->name
                                    ?? '-' }}
                                </a>
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
                    </form>
                </div>
            </div>
        </div>

        @php
            $isIterated = false;
            $isContented = false;
            $objCounter = null;
            $appended = false;
        @endphp

        <div class="col-md-10">
            <div class="col-12">
                <div class="card card-primary card-outline card-outline-tabs fillable-objective">
                    {{-- @dd($allData) --}}
                    <div class="card-header p-0 border-bottom-0 objectives-list-tab">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            @forelse ($allData as $data)
                                {{-- @dd($data->objectiveTranslations) --}}



                                {{-- @forelse($data as $kpi) --}}
                                {{-- @dd($kpi->objective->objectiveTranslations) --}}

                                @forelse($data->objectiveTranslations as $obj_ts)
                                    @if (app()->getLocale() == $obj_ts->locale)
                                        @if ($isIterated)
                                            <li class="nav-item">
                                                <a class="nav-link" id="{{ $obj_ts->translation_id . '-tab' }}"
                                                    data-toggle="pill" href="{{ '#objective-' . $obj_ts->translation_id }}"
                                                    role="tab" aria-controls="{{ $obj_ts->translation_id }}"
                                                    aria-selected="false">{{ $obj_ts->name }}</a>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a class="nav-link active" id="{{ $obj_ts->translation_id . '-tab' }}"
                                                    data-toggle="pill" href="{{ '#objective-' . $obj_ts->translation_id }}"
                                                    role="tab" aria-controls="{{ $obj_ts->translation_id }}"
                                                    aria-selected="true">{{ $obj_ts->name }}</a>
                                            </li>

                                            @php
                                                $isIterated = true;
                                            @endphp
                                        @endif
                                    @endif
                                @empty
                                    {{-- <p>gufdgfhj</p> --}}
                                @endforelse

                                {{-- @empty
                        <p>hguyftdf</p>
                        @endforelse --}}

                            @empty
                            @endforelse
                        </ul>
                    </div>

                    {{-- <div class="card-body">
                        @forelse($reportingTypes as $key => $type)
                            @if ($type->reportingPeriodTypeTs[0]->name == 'Quarterly')
                                <p class=""><span class="bg-primary px-3 mr-2"></span>Quarterly</p>
                            @else
                                <p class=""><span class="bg-success px-3 mr-2"></span>Yearly</p>
                            @endif

                        @empty
                        @endforelse

                    </div> --}}

                    <div class="card-body">
                        {{-- <button class="btn" onclick="expandAll()"><h5><i class="fas fa-plus"></i> Expand All</h5></button> --}}
                        <div class="tab-content" id="custom-tabs-four-tabContent">

                            @forelse($allData as $data)
                                {{-- @dd($data) --}}
                                {{-- @forelse($data as $kpi) --}}

                                @if ($isContented)
                                    <div class="tab-pane fade" id="{{ 'objective-' . $data->id }}" role="tabpanel"
                                        aria-labelledby="{{ $data->id . '-tab' }}">
                                    @else
                                        <div class="tab-pane fade active show" id="{{ 'objective-' . $data->id }}"
                                            role="tabpanel" aria-labelledby="{{ $data->id . '-tab' }}">
                                            @php
                                                $isContented = true;
                                            @endphp
                                @endif

                                <form action="{{ route('plan.save') }}" method="POST">
                                    @csrf

                                {{-- @if ($data) --}}
                                @forelse($data->KeyPeformanceIndicators as $kpi)
                                    <div class="card collapsed-card p-2">
                                        <div class="card-header">
                                            <h3 class="card-title">KPI: {{ $kpi->KeyPeformanceIndicatorTs[0]->name }}</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body" style="display: none;">

                                        {{-- If KPI has Child ones (UG, PG) --}}
                                        @if (!$kpi->kpiChildOnes->isEmpty())

                                       {{-- <table class="table table-bordered">
                                                    <!-- <tr id="child-ones"> -->
                                                          @foreach ($kpi->kpiChildOnes as $one)
                                                            <tr>
                                                                <th rowspan="6" >{{ $one->kpiChildOneTranslations[0]->name }}
                                                                </th>
                                                                <th>
                                                                    @foreach ($one->kpiChildTwos as $kpiTwo)
                                                                    <tr>
                                                                    <th  rowspan="2" >{{ $kpiTwo->kpiChildTwoTranslations[0]->name }}
                                                                    </th>
                                                                    <th>
                                                                         @foreach ($kpiTwo->kpiChildThrees as $three)
                                                                            <tr>
                                                                            <th >{{ $three->kpiChildThreeTranslations[0]->name }}
                                                                            </th>
                                                                            </tr>
                                                                            @endforeach
                                                                    </th>
                                                                    </tr>
                                                                    @endforeach
                                                                </th>
                                                            </tr>
                                                         @endforeach
                                             </table>
                                            <table class="table table-bordered">
                                        <!-- <tr id="child-ones"> -->
                                                @foreach ($kpi->kpiChildOnes as $one)
                                                    @foreach ($one->kpiChildTwos as $kpiTwo)
                                                        @foreach ($kpiTwo->kpiChildThrees as $three)
                                                        <tr>
                                                        <th collspan="6">{{ $one->kpiChildOneTranslations[0]->name }}
                                                        </th>
                                                        <th >{{ $kpiTwo->kpiChildTwoTranslations[0]->name }}
                                                        </th>
                                                        <th >{{ $three->kpiChildThreeTranslations[0]->name }}
                                                        </th>
                                                        </tr>
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                             </table>--}}


                                            <table class="table table-bordered">

                                                <thead>
                                                    <!-- <tr id="child-ones"> -->
                                                    <tr>
                                                        <th rowspan="2"></th>
                                                        @foreach ($kpi->kpiChildOnes as $one)
                                                            <th colspan="{{ $one->kpiChildTwos ->count() }}" >{{ $one->kpiChildOneTranslations[0]->name }}
                                                            </th>
                                                         @endforeach
                                                    </tr>
                                                    <tr>
                                                         @foreach ($kpi->kpiChildOnes as $one)
                                                         @if($one->kpiChildTwos ->count()> 0)
                                                              @foreach ($one->kpiChildTwos as $kpiTwo)
                                                                
                                                                <th  >{{ $kpiTwo->kpiChildTwoTranslations[0]->name }}
                                                                </th>
                                                             @endforeach
                                                             @else
                                                                    <th  >{{ "-" }}</th>
                                                                @endif
                                                        @endforeach
                                                    </tr>
                                                    {{-- get count of level 3--}}
                                                    @foreach ($kpi->kpiChildOnes as $one)
                                                       @php $count3 =0 @endphp
                                                        @foreach ($one->kpiChildTwos as $kpiTwo)
                                                            @if($kpiTwo->kpiChildThrees->count() > $count3)
                                                                @php
                                                                 $count3 = $kpiTwo->kpiChildThrees->count();
                                                                @endphp
                                                            @endif
                                                         @endforeach
                                                    @endforeach

                                                    @for($i =1; $i<= $count3; $i++)
                                                     <tr>
                                                        @if($i==1)
                                                         <th  >Male </th>
                                                         @else
                                                          <th  >Female </th>
                                                         @endif
                                                         @foreach ($kpi->kpiChildOnes as $one)
                                                            @if($one->kpiChildTwos ->count()> 0)
                                                              @foreach ($one->kpiChildTwos as $kpiTwo)
                                                              
                                                               <td><input name="{{$kpi->id.$one->id.$kpiTwo->id.$i}}" class="form-control" type="number" required></td>
                                                              @endforeach
                                                              @else
                                                                <td><input name="{{$kpi->id.$one->id.$i}}" class="form-control" type="number" required></td>
                                                                @endif
                                                        @endforeach
                                                    </tr>
                                                    @endfor
                                                </thead>

                                            </table>

                                            {{-- KPI has no child one, which means just only plain input --}}
                                        @else
                                            <p class="mb-3">
                                                <input class="form-control" type="number" placeholder="Enter KPI value"
                                                    name="{{ $kpi->id}}" required>
                                            </p>
                                        @endif

                                        <textarea name="desc{{ $kpi->id}}" style="height: 100px;" class="form-control" name="" id="" placeholder="Narration here" required></textarea>

                                    </div>

                                    </div>

                                @empty
                                    <h4>No KPI registered for this Goal and Objective!</h4>
                                @endforelse
                                <button type="submit" class="btn btn-primary">Submit</button>

                            </form>
                        </div>

                    @empty
                        {{-- <p>ugyftrdy</p> --}}
                        @endforelse



                    </div>
                </div>

                <!-- /.card -->
            </div>
        </div>
    </div>

    </div>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    {{-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}

    {{-- <script>
        $(document).ready(function() {
            $('#goal-card').on('click', '.goal-list', function() {
                var goalId = $(this).attr('data-id');

                var url = "{{ route('get-objectives', [':id']) }}";
                url = url.replace(':id', goalId);

                $('.fillable-objective').empty();

                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(response) {

                        // foreach(response as r){
                        //     console.log(r)
                        // }
                        console.log(response);
                        $('.fillable-objective').html(response);
                    }
                });

            })

        })
    </script> --}}

    <script>
        function expandAll(){

        }
    </script>

@endsection
