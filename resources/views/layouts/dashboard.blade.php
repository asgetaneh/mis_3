@extends('layouts.app')
@section('content')

 <!-- Small boxes (Stat box) -->
      {{-- <div class="row">
                <!-- <h1>{{ __('1.welcome') }}</h1> -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>150</h3>

              <p>KPI</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>16<sup style="font-size: 20px"></sup></h3>

              <p>Objectives</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div> --}}

      <div class="row">
		<div class="col-12 col-sm-6 col-md-3">
			<div class="info-box mb-3" id="" title="click to view detail">
				<span class="info-box-icon bg-success elevation-1">
					<i class="fas fa-link"></i>
				</span>

				<a href="{{ route('key-peformance-indicators.index') }}">
					<div class="info-box-content">
						<span class="info-box-text">KPI</span>
						<span class="info-box-number">{{ $totalKpis }}</span>
					</div>
				</a>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>
        <div class="col-12 col-sm-6 col-md-3">

			<div class="info-box " id="" title="click to view detail">
				<span class="info-box-icon bg-secondary elevation-1">
					<i class="fas fa-eye"></i>
				</span>
				<a href="{{ route('perspectives.index') }}">
					<div class="info-box-content">
						<span class="info-box-text">Perspectives</span>
						<span class="info-box-number">
							{{ $totalPerspectives }}
						</span>
					</div>
				</a>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>
		<div class="col-12 col-sm-6 col-md-3">

			<div class="info-box " id="" title="click to view detail">
				<span class="info-box-icon bg-warning elevation-1">
					<i class="fas fa-bullseye"></i>
				</span>
				<a href="{{ route('goals.index') }}">
					<div class="info-box-content">
						<span class="info-box-text">Goals</span>
						<span class="info-box-number">
							{{ $totalGoals }}
						</span>
					</div>
				</a>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>

		<div class="col-12 col-sm-6 col-md-3">

			<div class="info-box " id="" title="click to view detail">
				<span class="info-box-icon bg-info elevation-1">
					<i class="fas fa-list"></i>
				</span>
				<a href="{{ route('objectives.index') }}">
					<div class="info-box-content">
						<span class="info-box-text">Objectives</span>
						<span class="info-box-number">
							{{ $totalObjectives }}
						</span>
					</div>
				</a>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>

        {{-- Only admin --}}
        @if (auth()->user()->is_admin === true)
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3" id="" title="click to view detail">
                    <span class="info-box-icon bg-info elevation-1">
                        <i class="fas fa-building"></i>
                    </span>

                    <a href="{{ route('office-translations.index') }}">
                        <div class="info-box-content">
                            <span class="info-box-text">Offices</span>
                            <span class="info-box-number">{{ $totalOffices }}</span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3" id="" title="click to view detail">
                    <span class="info-box-icon bg-primary elevation-1">
                        <i class="fas fa-users"></i>
                    </span>
                    <a href="{{ route('users.index') }}">

                        <div class="info-box-content">
                            <span class="info-box-text">Users</span>
                            <span class="info-box-number">{{ $totalUsers }}</span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-12 col-sm-6 col-md-3">

                <div class="info-box " id="" title="click to view detail">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fas fa-check"></i>
                    </span>
                    <a href="{{ route('users.index') }}">
                        <div class="info-box-content">
                            <span class="info-box-text">Active Users</span>
                            <span class="info-box-number">
                                0
                            </span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3" id="" title="click to view detail">
                    <span class="info-box-icon bg-danger elevation-1">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>

                    <a href="{{ route('users.index') }}">
                        <div class="info-box-content">
                            <span class="info-box-text">Inactive Users</span>
                            <span class="info-box-number">0</span>
                        </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
        @endif
			<!-- /.info-box -->
		</div>
		<!-- /.col -->

		<!-- /.col -->
	</div>

 @endsection
