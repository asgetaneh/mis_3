<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-1">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link" style="background: #3074a4; color: white;">
        <img src="{{ asset('images/logo.png') }}" alt="Vemto Logo" class="brand-image">
        <span class="brand-text">JU MIS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <hr>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                @auth
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon  fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>



                <li class="nav-item">
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
                                <i class="nav-icon icon ion-md-radio-button-off"></i>
                                <p>
                                    Setting
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                    @can('view-any', App\Models\User::class)
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" class="nav-link">
                                            <i class="nav-icon icon ion-md-radio-button-off"></i>
                                            <p>Users</p>
                                        </a>
                                    </li>
                                    @endcan
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            SMIS
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-wrench"></i>
                                <p>
                                    Setting
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                 @can('view-any', App\Models\Perspective::class)
                            <li class="nav-item">
                                <a href="{{ route('perspectives.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Perspectives</p>
                                </a>
                            </li>
                            @endcan
                                  @can('view-any', App\Models\Goal::class)
                                <li class="nav-item">
                                    <a href="{{ route('goals.index') }}" class="nav-link">
                                        <i class="nav-icon icon ion-md-radio-button-off"></i>
                                        <p>Goals</p>
                                    </a>
                                </li>
                                @endcan

                            @can('view-any', App\Models\Objective::class)
                            <li class="nav-item">
                                <a href="{{ route('objectives.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Objectives</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Strategy::class)
                            <li class="nav-item">
                                <a href="{{ route('strategies.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Strategies</p>
                                </a>
                            </li>
                            @endcan
                             @can('view-any', App\Models\KeyPeformanceIndicator::class)
                            <li class="nav-item">
                                <a href="{{ route('key-peformance-indicators.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Key Peformance Indicators</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Inititive::class)
                            <li class="nav-item">
                                <a href="{{ route('kpi-child-one-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Inititives Level One</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Inititive::class)
                            <li class="nav-item">
                                <a href="{{ route('kpi-child-two-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Inititives Level Two</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Inititive::class)
                            <li class="nav-item">
                                <a href="{{ route('kpi-child-three-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Inititives Level Three</p>
                                </a>
                            </li>
                            @endcan


                            @can('view-any', App\Models\Office::class)
                            <li class="nav-item">
                                <a href="{{ route('office_translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Offices</p>
                                </a>
                            </li>
                            @endcan

                            @can('view-any', App\Models\PlaningYear::class)
                            <li class="nav-item">
                                <a href="{{ route('planing-years.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Planing Years</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\ReportingPeriod::class)
                            <li class="nav-item">
                                <a href="{{ route('reporting-periods.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Reporting Periods</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\ReportingPeriodType::class)
                            <li class="nav-item">
                                <a href="{{ route('reporting-period-types.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Reporting Period Types</p>
                                </a>
                            </li>
                            @endcan
                           {{--
                            @can('view-any', App\Models\SuitableKpi::class)
                            <li class="nav-item">
                                <a href="{{ route('suitable-kpis.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Suitable Kpis</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\GenderTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('gender-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Gender Translations</p>
                                </a>
                            </li>
                            @endcan--}}

                            @can('view-any', App\Models\Language::class)
                            <li class="nav-item">
                                <a href="{{ route('languages.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Languages</p>
                                </a>
                            </li>
                            @endcan
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-credit-card  nav-icon"></i>
                                <p>
                                    Plan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                        {{--    @can('view-any', App\Models\StrategyTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('suitable-kpis.create') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Cascade</p>
                                </a>
                            </li>
                            @endcan--}}
                            @can('view-any', App\Models\Language::class)


                            <li class="nav-item">
                                @if(Auth::user()!= [])
                                <a href="{{ route('plan-accomplishment',Auth::user()->id) }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Planning</p>
                                </a>
                                @endif
                            </li>
                            @endcan
                             @can('view-any', App\Models\Language::class)
                             <li class="nav-item">
                                <a href="{{ route('view-plan-accomplishment') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>View Plan</p>
                                </a>
                            </li>
                            @endcan
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-tasks nav-icon"></i>
                                <p>
                                    Report
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                            @can('view-any', App\Models\StrategyTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('strategy-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Reporting</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Language::class)
                            <li class="nav-item">
                                <a href="{{ route('languages.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>View Report  </p>
                                </a>
                            </li>
                            @endcan
                            </ul>
                        </li>



                    </ul>
                </li>

                @if (Auth::user()->can('view-any', Spatie\Permission\Models\Role::class) ||
                    Auth::user()->can('view-any', Spatie\Permission\Models\Permission::class))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-key"></i>
                        <p>
                            Access Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                         @can('view-any', App\Models\User::class)
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                            @endcan
                        @can('view-any', Spatie\Permission\Models\Role::class)
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link">
                                <i class="nav-icon icon ion-md-radio-button-off"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endcan

                        @can('view-any', Spatie\Permission\Models\Permission::class)
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link">
                                <i class="nav-icon icon ion-md-radio-button-off"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
                @endauth

                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
