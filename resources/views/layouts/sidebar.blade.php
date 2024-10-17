<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-1">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link" style="background: #086098; color: white;">
        <img src="{{ asset('images/logo.png') }}" alt="Vemto Logo" class="brand-image">
        <span class="brand-text">JU MIS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <hr>
        @php
            $user = auth()->user();
            if (!$user->offices->isEmpty()) {
                $office = $user->offices[0];
                $childAndHimOffKpi = $office->offices;
            }

        @endphp
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                @auth
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon  fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>


                    @if (!$user->offices->isEmpty())
                        <li class="nav-item {{ Request::is('emis/*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-sitemap"></i>
                                <p>
                                    EMIS
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon icon fas fa fa-caret-right"></i>
                                        <p>
                                            Setting
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        {{-- @can('view-any', App\Models\User::class) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('emis.setting.student-id') }}" class="nav-link">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Student Id</p>
                                                </a>
                                            </li>
                                        {{-- @endcan --}}
                                    </ul>

                                    <ul class="nav nav-treeview">
                                        {{-- @can('view-any', App\Models\User::class) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('users.index') }}" class="nav-link">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Staff Id</p>
                                                </a>
                                            </li>
                                        {{-- @endcan --}}
                                    </ul>
                                     <ul class="nav nav-treeview">
                                        {{-- @can('view-any', App\Models\User::class) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('emis.setting.campus') }}" class="nav-link">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Campus</p>
                                                </a>
                                            </li>
                                        {{-- @endcan --}}
                                    </ul>
                                     <ul class="nav nav-treeview">
                                        {{-- @can('view-any', App\Models\User::class) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('emis.setting.building.purpose') }}" class="nav-link">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Buiding purpose</p>
                                                </a>
                                            </li>
                                        {{-- @endcan --}}
                                    </ul>

                                </li>



                                {{--  --}}

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon icon fas fa fa-caret-right"></i>
                                        <p>
                                            Institutions
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{route('emis.institution.building')}}" class="nav-link">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Buildings</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>


                                <li class="nav-item {{ Request::is('emis/student/*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon icon fas fa fa-caret-right"></i>
                                        <p>
                                            Student
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{route('emis.student.applicant.index')}}" class="nav-link {{ Request::is('emis/student/applicant') || Request::is('emis/student/applicant/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Applicants</p>
                                            </a>
                                            <a href="{{route('emis.student.overview.index')}}" class="nav-link {{ Request::is('emis/student/overview') || Request::is('emis/student/overview/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Overview</p>
                                            </a>
                                            <a href="{{route('emis.student.enrollment.index')}}" class="nav-link {{ Request::is('emis/student/enrollment') || Request::is('emis/student/enrollment/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Enrollment</p>
                                            </a>
                                            <a href="{{route('emis.student.results.index')}}" class="nav-link {{ Request::is('emis/student/results') || Request::is('emis/student/results/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Results</p>
                                            </a>
                                            <a href="{{route('emis.student.graduates.index')}}" class="nav-link {{ Request::is('emis/student/graduates') || Request::is('emis/student/graduates/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Graduates</p>
                                            </a>
                                            <a href="{{route('emis.student.attrition.index')}}" class="nav-link {{ Request::is('emis/student/attrition') || Request::is('emis/student/attrition/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Attritions</p>
                                            </a>
                                            <a href="{{route('emis.student.internship.index')}}" class="nav-link {{ Request::is('emis/student/internship') || Request::is('emis/student/internship/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Intership</p>
                                            </a>
                                            <a href="{{route('emis.student.employment.index')}}" class="nav-link {{ Request::is('emis/student/employment') || Request::is('emis/student/employment/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Employment</p>
                                            </a>
                                            <a href="{{route('emis.student.others.index')}}" class="nav-link {{ Request::is('emis/student/others') || Request::is('emis/student/others/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Other Reports</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item {{ Request::is('emis/staff/*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon icon fas fa fa-caret-right"></i>
                                        <p>
                                            Staff
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('emis.staff.overview.index') }}" class="nav-link {{ Request::is('emis/staff/overview') || Request::is('emis/staff/overview/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Overview</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('emis.staff.assignment.index') }}" class="nav-link {{ Request::is('emis/staff/assignment') || Request::is('emis/staff/assignment/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Assignment</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('emis.staff.development.index') }}" class="nav-link {{ Request::is('emis/staff/development') || Request::is('emis/staff/development/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Development</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('emis.staff.attrition.index') }}" class="nav-link {{ Request::is('emis/staff/attrition') || Request::is('emis/staff/attrition/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Attrition</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('emis.staff.awards.index') }}" class="nav-link {{ Request::is('emis/staff/awards') || Request::is('emis/staff/awards/*') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Awards</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                {{--  --}}
                            </ul>
                        </li>
                        <li class="nav-item {{ Request::is('smis/*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    SMIS
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item {{ Request::is('smis/setting/*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-wrench"></i>
                                        <p>
                                            Setting
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @can('view-any', App\Models\Language::class)
                                            <li class="nav-item">
                                                <a href="{{ route('languages.index') }}"
                                                    class="nav-link {{ Request::is('smis/setting/languages') || Request::is('smis/setting/languages/*') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Languages</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @if ($user->hasPermission('view keypeformanceindicators'))
                                            <li class="nav-item">
                                                <a href="{{ route('perspectives.index') }}"
                                                    class="nav-link {{ Request::is('smis/setting/perspectives/*') || Request::is('smis/setting/perspectives') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Perspectives</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($user->hasPermission('view keypeformanceindicators'))
                                            <li class="nav-item">
                                                <a href="{{ route('goals.index') }}"
                                                    class="nav-link {{ Request::is('smis/setting/goals/*') || Request::is('smis/setting/goals') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Goals</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if ($user->hasPermission('view keypeformanceindicators'))
                                            <li class="nav-item">
                                                <a href="{{ route('objectives.index') }}"
                                                    class="nav-link {{ Request::is('smis/setting/objectives/*') || Request::is('smis/setting/objectives') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Objectives</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($user->hasPermission('view keypeformanceindicators'))
                                            <li class="nav-item">
                                                <a href="{{ route('strategies.index') }}"
                                                    class="nav-link {{ Request::is('smis/setting/strategies/*') || Request::is('smis/setting/strategies') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Strategies</p>
                                                </a>
                                            </li>
                                        @endif


                                        @can('view-any', App\Models\Office::class)
                                            <li class="nav-item">
                                                <a href="{{ route('office_translations.index') }}"
                                                    class="nav-link
                                            {{ Request::is('smis/setting/office_translations') || Request::is('smis/setting/office_translations/*') || Request::is('smis/setting/office-translations') || Request::is('smis/setting/office-translations/*') ? 'active' : '' }}
                                            ">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Offices</p>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('view-any', App\Models\PlaningYear::class)
                                            <li class="nav-item">
                                                <a href="{{ route('planing-years.index') }}"
                                                    class="nav-link {{ Request::is('smis/setting/planing-years') || Request::is('smis/setting/planing-years/*') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Planing Years</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('view-any', App\Models\ReportingPeriodType::class)
                                            <li class="nav-item">
                                                <a href="{{ route('reporting-period-types.index') }}"
                                                    class="nav-link {{ Request::is('smis/setting/reporting-period-types') || Request::is('smis/setting/reporting-period-types/*') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Reporting Period Types</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('view-any', App\Models\ReportingPeriod::class)
                                            <li class="nav-item">
                                                <a href="{{ route('reporting-periods.index') }}"
                                                    class="nav-link {{ Request::is('smis/setting/reporting-periods') || Request::is('smis/setting/reporting-periods/*') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Reporting Periods</p>
                                                </a>
                                            </li>
                                        @endcan

                                        @if ($user->hasPermission('view keypeformanceindicators'))
                                            <li
                                                class="nav-item {{ Request::is('smis/setting/kpi/*') ? 'menu-open' : '' }}">
                                                <a href="#" class="nav-link">
                                                    <i class="nav-icon fas fa-link"></i>
                                                    <p>
                                                        KPI
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">

                                                    @can('view-any', App\Models\Measurement::class)
                                                        <li class="nav-item">
                                                            <a href="{{ route('kpi-measurements.index') }}"
                                                                class="nav-link {{ Request::is('smis/setting/kpi/kpi-measurements/*') || Request::is('smis/setting/kpi/kpi-measurements') ? 'active' : '' }}">
                                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                                <p>Measurement</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('view-any', App\Models\Behavior::class)
                                                        <li class="nav-item">
                                                            <a href="{{ route('types.index') }}"
                                                                class="nav-link {{ Request::is('smis/setting/kpi/types/*') || Request::is('smis/setting/kpi/types') ? 'active' : '' }}">
                                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                                <p>KPI Type</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('view-any', App\Models\Behavior::class)
                                                        <li class="nav-item">
                                                            <a href="{{ route('behaviors.index') }}"
                                                                class="nav-link {{ Request::is('smis/setting/kpi/behaviors/*') || Request::is('smis/setting/kpi/behaviors') ? 'active' : '' }}">
                                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                                <p>KPI Behavior</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('view-any', App\Models\Inititive::class)
                                                        <li class="nav-item">
                                                            <a href="{{ route('kpi-child-one-translations.index') }}"
                                                                class="nav-link {{ Request::is('smis/setting/kpi/kpi-child-one-translations/*') || Request::is('smis/setting/kpi/kpi-child-one-translations') ? 'active' : '' }}">
                                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                                <p>Disaggregation Level One</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('view-any', App\Models\Inititive::class)
                                                        <li class="nav-item">
                                                            <a href="{{ route('kpi-child-two-translations.index') }}"
                                                                class="nav-link {{ Request::is('smis/setting/kpi/kpi-child-two-translations/*') || Request::is('smis/setting/kpi/kpi-child-two-translations') ? 'active' : '' }}">
                                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                                <p>Disaggregation Level Two</p>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('view-any', App\Models\Inititive::class)
                                                        <li class="nav-item">
                                                            <a href="{{ route('kpi-child-three-translations.index') }}"
                                                                class="nav-link {{ Request::is('smis/setting/kpi/kpi-child-three-translations/*') || Request::is('smis/setting/kpi/kpi-child-three-translations') ? 'active' : '' }}">
                                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                                <p>Disaggregation Level Three</p>
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    <li class="nav-item">
                                                        <a href="{{ route('key-peformance-indicators.index') }}"
                                                            class="nav-link
                                {{ Request::is('smis/setting/kpi/key-peformance-indicators/*') || Request::is('smis/setting/kpi/key-peformance-indicators') || Request::is('smis/setting/kpi//kpi_chain/*') || Request::is('smis/setting/kpi/kpi-chain-two/*') || Request::is('smis/setting/kpi/kpi-chain-three/*') ? 'active' : '' }}

                                ">
                                                            <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                            <p>Key Peformance Indicators</p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        @endcan

                                        {{--
                            @can('view-any', App\Models\SuitableKpi::class)
                            <li class="nav-item">
                                <a href="{{ route('suitable-kpis.index') }}" class="nav-link">
                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                    <p>Suitable Kpis</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\GenderTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('gender-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                    <p>Gender Translations</p>
                                </a>
                            </li>
                            @endcan --}}
                                        {{-- @dd(auth()->user()->roles->first()->permi); --}}

                                </ul>
                            </li>

                            <li class="nav-item {{ Request::is('smis/plan/*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="far fa-credit-card  nav-icon"></i>
                                    <p>
                                        Plan
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                {{-- dd(Auth::user()->getPermissionsViaRoles()->pluck('name'));
                                dd($user->hasAnyRole(['writer', 'reader'])); --}}

                                {{-- Badges to be shown on planning and plan approval menus --}}
                                @php
                                    $currentLoggedInOffice = auth()->user()->offices[0]->id;
                                    $planCommentCounter = 0;
                                    $planCounter = 0;
                                    $kpiPlanName = '';

                                    $obj_office = App\Models\Office::find($currentLoggedInOffice);
                                    $all_child_and_subchild = office_all_childs_ids($obj_office);
                                    $all_office_list = $all_child_and_subchild;
                                    $only_child = $obj_office->offices;
                                    $only_child_array = [];

                                    foreach ($only_child as $key => $value) {
                                        $only_child_array[$key] = $value->id;
                                    }

                                    if ($obj_office->offices->isEmpty()) {
                                        $all_office_list = array($currentLoggedInOffice);
                                    }

                                    $planAccomplishments = App\Models\PlanAccomplishment::select('kpi_id')
                                        ->where('office_id', $currentLoggedInOffice)
                                        ->distinct('kpi_id')
                                        ->get();

                                    $planStatus = App\Models\PlanAccomplishment::select('kpi_id', 'office_id', 'plan_status')
                                        ->whereIn('office_id', $all_office_list)
                                        ->distinct('kpi_id')
                                        ->get();
                                    $planning_year = App\Models\PlaningYear::where('is_active', true)->first();
                                    $planSubmited = App\Models\PlanAccomplishment::select('approved_by_id')
                                        ->where('planning_year_id', '=', $planning_year->id)
                                        ->where('plan_status', '=', 1)
                                        ->distinct('approved_by_id')
                                         ->get();
                                    $count_planSubmited = count($planSubmited);
 
                                @endphp

                                {{-- @dump($planAccomplishments) --}}

                                {{-- If comment exist for current office --}}
                                @forelse ($planAccomplishments as $planAcc)
                                    @php
                                        $hasOfficeComment = hasOfficeActiveComment($currentLoggedInOffice, $planAcc->kpi_id, $planning_year->id ?? NULL);
                                    @endphp

                                    @if ($hasOfficeComment->count() > 0)
                                        @php
                                            $planCommentCounter++;
                                        @endphp
                                    @endif
                                @empty

                                @endforelse

                                @if($planCommentCounter == 1)
                                    @forelse ($planAccomplishments as $planAcc)

                                        @php
                                            $hasOfficeComment = hasOfficeActiveComment($currentLoggedInOffice, $planAcc->kpi_id, $planning_year->id ?? NULL);
                                        @endphp

                                        @if ($hasOfficeComment->count() > 0)
                                             @php
                                                $kpiPlanName = App\Models\KeyPeformanceIndicator::find($planAcc->kpi_id);
                                            @endphp
                                        @endif
                                    @empty
                                    @endforelse
                                @endif

                                {{-- If office exist to be approved --}}
                                @forelse ($planStatus as $planAcc)

                                    @if ($planAcc->plan_status == auth()->user()->offices[0]->level+1)
                                        @php
                                            $planCounter++;
                                        @endphp
                                    @endif
                                @empty

                                @endforelse

                                <ul class="nav nav-treeview">
                                    @if ($user->hasPermission('create planaccomplishments'))
                                        <li class="nav-item">
                                            @if (Auth::user() != [])
                                                <a href="{{ route('plan-accomplishment', Auth::user()->id) }}"
                                                    class="nav-link {{ Request::is('smis/plan/plan-accomplishment/*') || Request::is('smis/plan/plan-accomplishment') || Request::is('smis/plan/get-objectives/*') || Request::is('smis/plan/get-objectives') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Planning
                                                        @if ($planCommentCounter > 0)
                                                                @if ($planCommentCounter == 1)
                                                                    @forelse ($kpiPlanName->KeyPeformanceIndicatorTs as $kpi)
                                                                        @if (app()->getLocale() == $kpi->locale)
                                                                            <span class="badge bg-info ml-2 rounded-circle px-2 py-1" title="You've comment on '{{ $kpi->name }}' KPI">
                                                                                {{ $planCommentCounter }}
                                                                            </span>
                                                                        @endif
                                                                    @empty

                                                                    @endforelse
                                                                @else
                                                                    <span class="badge bg-info ml-2 rounded-circle px-2 py-1" title="You've {{ $planCommentCounter }} comment">
                                                                        {{ $planCommentCounter }}
                                                                    </span>
                                                                @endif

                                                            @endif
                                                    </p>
                                                        {{-- <span class="badge rounded-pill   bg-danger">9</span> --}}

                                                </a>
                                            @endif
                                        </li>
                                    @endif

                                    @if ($user->hasPermission('create planaccomplishments'))
                                        @if (!$office->offices->isEmpty() || $office->level == 1)
                                            <li class="nav-item">
                                                <a href="{{ route('plan-approve.index') }}"
                                                    class="nav-link {{ Request::is('smis/plan/approve/*') || Request::is('smis/plan/approve') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Plan Approval
                                                        @if ($planCounter > 0)
                                                                <span class="badge bg-danger ml-2 rounded-circle px-2 py-1" title="You've {{ $planCounter }} unapproved office">
                                                                    {{ $planCounter }}
                                                                </span>
                                                            @endif
                                                    </p>
                                                </a>
                                            </li>
                                        @endif
                                    @endif


                                    @if ($user->hasPermission('create planaccomplishments'))
                                        <li class="nav-item">
                                            <a href="{{ route('view-plan-accomplishment') }}"
                                                class="nav-link {{ Request::is('smis/plan/view-plan-accomplishment/*') || Request::is('smis/plan/view-plan-accomplishment') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>View Plan
                                                @if ($count_planSubmited > 0)
                                                        <span class="badge bg-info ml-2 rounded-circle px-2 py-1" title="You've {{ $count_planSubmited }}  office who is submitted the plan">
                                                            {{ $count_planSubmited }}
                                                        </span>
                                                    @endif
                                                </p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                            <li class="nav-item {{ Request::is('smis/report/*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-tasks nav-icon"></i>
                                    <p>
                                        Report
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                {{-- Badges to be shown on planning and plan approval menus --}}
                                @php
                                    $currentLoggedInOffice = auth()->user()->offices[0]->id;
                                    $reportCommentCounter = 0;
                                    $reportCounter = 0;
                                    $kpireportName = '';

                                    $obj_office = App\Models\Office::find($currentLoggedInOffice);
                                    $all_child_and_subchild = office_all_childs_ids($obj_office);
                                    $all_office_list = $all_child_and_subchild;
                                    $only_child = $obj_office->offices;
                                    $only_child_array = [];

                                    foreach ($only_child as $key => $value) {
                                        $only_child_array[$key] = $value->id;
                                    }

                                    if ($obj_office->offices->isEmpty()) {
                                        $all_office_list = array($currentLoggedInOffice);
                                    }

                                    $activeReportingPeriodList = getReportingPeriod();

                                    $reportAccomplishments = App\Models\PlanAccomplishment::select('kpi_id')
                                        ->where('office_id', $currentLoggedInOffice)
                                        ->distinct('kpi_id')
                                        ->whereIn('reporting_period_id', $activeReportingPeriodList)
                                        ->get();

                                    $reportStatus = App\Models\PlanAccomplishment::select('kpi_id', 'office_id', 'accom_status')
                                        ->whereIn('office_id', $all_office_list)
                                        ->whereIn('reporting_period_id', $activeReportingPeriodList)
                                        ->whereNotNull('accom_value')
                                        ->distinct('office_id')
                                        ->get();

                                    $planning_year = App\Models\PlaningYear::where('is_active', true)->first();

                                @endphp

                                {{-- @dump($reportStatus) --}}

                                {{-- If comment exist for current office --}}
                                @forelse ($reportAccomplishments as $reportAcc)
                                    @php
                                        $hasOfficeComment = hasOfficeActiveReportComment($currentLoggedInOffice, $reportAcc->kpi_id, $planning_year->id ?? NULL);
                                    @endphp

                                    @if ($hasOfficeComment->count() > 0)
                                        @php
                                            $reportCommentCounter++;
                                        @endphp
                                    @endif
                                @empty

                                @endforelse

                                @if($reportCommentCounter == 1)
                                    @forelse ($reportAccomplishments as $reportAcc)

                                        @php
                                            $hasOfficeComment = hasOfficeActiveReportComment($currentLoggedInOffice, $reportAcc->kpi_id, $planning_year->id ?? NULL);
                                        @endphp

                                        @if ($hasOfficeComment->count() > 0)
                                             @php
                                                $kpireportName = App\Models\KeyPeformanceIndicator::find($reportAcc->kpi_id);
                                            @endphp
                                        @endif
                                    @empty
                                    @endforelse
                                @endif

                                {{-- If office exist to be approved --}}
                                @forelse ($reportStatus as $reportAcc)

                                    @if ($reportAcc->accom_status == auth()->user()->offices[0]->level+1)
                                        @php
                                            $reportCounter++;
                                        @endphp
                                    @endif
                                @empty

                                @endforelse

                                <ul class="nav nav-treeview">
                                    @if ($user->hasPermission('create planaccomplishments'))
                                        <li class="nav-item">
                                            <a href="{{ route('reporting', Auth::user()->id) }}"
                                                class="nav-link {{ Request::is('smis/report/reporting/*') || Request::is('smis/report/reporting') || Request::is('smis/report/get-objectives-reporting/*') || Request::is('smis/report/get-objectives-reporting') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>Reporting
                                                    @if ($reportCommentCounter > 0)
                                                                @if ($reportCommentCounter == 1)
                                                                    @forelse ($kpireportName->KeyPeformanceIndicatorTs as $kpi)
                                                                        @if (app()->getLocale() == $kpi->locale)
                                                                            <span class="badge bg-info ml-2 rounded-circle px-2 py-1" title="You've comment on '{{ $kpi->name }}' KPI">
                                                                                {{ $reportCommentCounter }}
                                                                            </span>
                                                                        @endif
                                                                    @empty

                                                                    @endforelse
                                                                @else
                                                                    <span class="badge bg-info ml-2 rounded-circle px-2 py-1" title="You've {{ $reportCommentCounter }} comment">
                                                                        {{ $reportCommentCounter }}
                                                                    </span>
                                                                @endif

                                                            @endif
                                                </p>
                                            </a>

                                        </li>
                                    @endif
                                    @if ($user->hasPermission('create planaccomplishments'))
                                        @php
                                            $user = auth()->user();
                                            $office = $user->offices[0];
                                            $childAndHimOffKpi = $office->offices;
                                        @endphp
                                        @if (!$office->offices->isEmpty() || $office->level == 1)
                                            <li class="nav-item">
                                                <a href="{{ route('report-approve.index') }}"
                                                    class="nav-link {{ Request::is('smis/report/approve/*') || Request::is('smis/report/approve') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Report Approval
                                                        @if ($reportCounter > 0)
                                                                <span class="badge bg-info rounded-circle px-2 py-1" title="You've {{ $reportCounter }} unapproved office">
                                                                    {{ $reportCounter }}
                                                                </span>
                                                        @endif
                                                    </p>
                                                </a>
                                            </li>
                                        @endif

                                        <li class="nav-item">
                                            <a href="{{ route('view-report-accomplishment') }}"
                                                class="nav-link {{ Request::is('smis/report/view-report-accomplishment/*') || Request::is('smis/report/view-report-accomplishment') ? 'active' : '' }}">
                                                <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                <p>View Report </p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                            @if($user->hasPermission('create task'))
                              <li class="nav-item {{ Request::is('smis/performer/*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon icon fas fa fa-users"></i>
                                        <p>
                                            Performer task
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                         <li class="nav-item">
                                                <a href="{{ route('TaskMeasurements.index') }}" class="nav-link {{ Request::is('smis/performer/TaskMeasurements') || Request::is('smis/performer/TaskMeasurements/*') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Task Measurement</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('tasks.index') }}" class="nav-link {{ Request::is('smis/performer/tasks') || Request::is('smis/performer/tasks/*') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Task</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('performer.index') }}" class="nav-link {{ Request::is('smis/performer/performer-list') ? 'active' : '' }}">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Performer list</p>
                                                </a>
                                            </li>
                                        {{-- @endcan --}}
                                    </ul>

                                    <ul class="nav nav-treeview">
                                        {{-- @can('view-any', App\Models\User::class) --}}
                                            {{-- <li class="nav-item">
                                                <a href="{{ route('performer.index') }}" class="nav-link">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Performer</p>
                                                </a>
                                            </li> --}}
                                        {{-- @endcan --}}
                                    </ul>
                                     <ul class="nav nav-treeview">
                                        {{-- @can('view-any', App\Models\User::class) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('performer.create') }}" class="nav-link">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Performer task list</p>
                                                </a>
                                            </li>
                                        {{-- @endcan --}}
                                    </ul>
                                     <ul class="nav nav-treeview">
                                        {{-- @can('view-any', App\Models\User::class) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('taskassign.index') }}" class="nav-link">
                                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                                    <p>Performer report</p>
                                                </a>
                                            </li>
                                        {{-- @endcan --}}
                                    </ul>

                                </li>
                                @endif


                        </ul>
                    </li>

                    @if (Auth::user()->can('view-any', Spatie\Permission\Models\Role::class) ||
                            Auth::user()->can('view-any', Spatie\Permission\Models\Permission::class))
                        <li class="nav-item {{ Request::is('access/management/*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-key"></i>
                                <p>
                                    User Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('view-any', App\Models\User::class)
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}"
                                            class="nav-link {{ Request::is('access/management/users') || Request::is('access/management/users/*') ? 'active' : '' }}">
                                            <i class="nav-icon icon fas fa fa-caret-right"></i>
                                            <p>Users</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('view-any', Spatie\Permission\Models\Role::class)
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}"
                                            class="nav-link {{ Request::is('access/management/roles') || Request::is('access/management/roles/*') ? 'active' : '' }}">
                                            <i class="nav-icon icon fas fa fa-caret-right"></i>
                                            <p>Roles</p>
                                        </a>
                                    </li>
                                @endcan

                                @can('view-any', Spatie\Permission\Models\Permission::class)
                                    <li class="nav-item">
                                        <a href="{{ route('permissions.index') }}"
                                            class="nav-link {{ Request::is('access/management/permissions') || Request::is('access/management/permissions/*') ? 'active' : '' }}">
                                            <i class="nav-icon icon fas fa fa-caret-right"></i>
                                            <p>Permissions</p>
                                        </a>
                                    </li>
                                @endcan
                                 @can('view-any', App\Models\User::class)
                                    <li class="nav-item">
                                        <a href="{{ route('feedback-view') }}"
                                            class="nav-link {{ Request::is('access/management/comments') || Request::is('access/management/comments/*') ? 'active' : '' }}">
                                            <i class="nav-icon icon fas fa fa-caret-right"></i>
                                            <p>View Comments</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                @endif
                @if($user->hasPermission('view task'))
                <li class="nav-item {{ Request::is('performer/tasks/assigned') || Request::is('performer/tasks/assigned/history') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon icon fas fa fa-list"></i>
                        <p>
                            Task
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        {{-- @can('view-any', App\Models\User::class) --}}
                            <li class="nav-item">
                                <a href="{{ route('assigned-tasks.index') }}" class="nav-link {{ Request::is('performer/tasks/assigned') ? 'active' : '' }}">
                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                    <p>Assigned Tasks</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('assigned-task.history') }}" class="nav-link {{ Request::is('performer/tasks/assigned/history') ? 'active' : '' }}">
                                    <i class="nav-icon icon fas fa fa-caret-right"></i>
                                    <p>View Tasks</p>
                                </a>
                            </li>
                        {{-- @endcan --}}
                    </ul>

                </li>
                @endif
            @endauth

            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon icon ion-md-exit"></i>
                        <p>{{ __('Logout') }}</p>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @endauth
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
