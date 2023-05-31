<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="https://vemto.app/favicon.png" alt="Vemto Logo" class="brand-image bg-white img-circle">
        <span class="brand-text font-weight-light">mis-lar</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                @auth
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon icon ion-md-pulse"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon icon ion-md-apps"></i>
                        <p>
                            EMIS
                            <i class="nav-icon right icon ion-md-arrow-round-back"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                           <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon icon ion-md-radio-button-off"></i>
                                <p>
                                    Setting
                                    <i class="nav-icon right icon ion-md-arrow-round-back"></i>
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
                        <i class="nav-icon icon ion-md-apps"></i>
                        <p>
                            SMIS
                            <i class="nav-icon right icon ion-md-arrow-round-back"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon icon ion-md-radio-button-off"></i>
                                <p>
                                    Setting
                                    <i class="nav-icon right icon ion-md-arrow-round-back"></i>
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
                                <a href="{{ route('inititives.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Inititives</p>
                                </a>
                            </li>
                            @endcan
                           
                            
                            @can('view-any', App\Models\Office::class)
                            <li class="nav-item">
                                <a href="{{ route('offices.index') }}" class="nav-link">
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
                            @endcan
                            @can('view-any', App\Models\InititiveTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('inititive-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Inititive Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\KeyPeformanceIndicatorT::class)
                            <li class="nav-item">
                                <a href="{{ route('key-peformance-indicator-ts.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Key Peformance Indicator Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\ObjectiveTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('objective-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Objective Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\OfficeTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('office-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Office Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\PerspectiveTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('perspective-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Perspective Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\PlanAccomplishment::class)
                            <li class="nav-item">
                                <a href="{{ route('plan-accomplishments.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Plan Accomplishments</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\PlaningYearTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('planing-year-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Planing Year Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\ReportingPeriodT::class)
                            <li class="nav-item">
                                <a href="{{ route('reporting-period-ts.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Reporting Period Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\ReportingPeriodTypeT::class)
                            <li class="nav-item">
                                <a href="{{ route('reporting-period-type-ts.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Reporting Period Type Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\StrategyTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('strategy-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Strategy Translations</p>
                                </a>
                            </li>
                            @endcan
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

               

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon icon ion-md-apps"></i>
                        <p>
                            Apps
                            <i class="nav-icon right icon ion-md-arrow-round-back"></i>
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
                          
                            @can('view-any', App\Models\GoalTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('goal-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Goals</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Gender::class)
                            <li class="nav-item">
                                <a href="{{ route('genders.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Genders</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Inititive::class)
                            <li class="nav-item">
                                <a href="{{ route('inititives.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Inititives</p>
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
                            @can('view-any', App\Models\Objective::class)
                            <li class="nav-item">
                                <a href="{{ route('objectives.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Objectives</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Office::class)
                            <li class="nav-item">
                                <a href="{{ route('offices.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Offices</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\Perspective::class)
                            <li class="nav-item">
                                <a href="{{ route('perspectives.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Perspectives</p>
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
                            @can('view-any', App\Models\Strategy::class)
                            <li class="nav-item">
                                <a href="{{ route('strategies.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Strategies</p>
                                </a>
                            </li>
                            @endcan
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
                            @endcan
                            @can('view-any', App\Models\InititiveTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('inititive-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Inititive Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\KeyPeformanceIndicatorT::class)
                            <li class="nav-item">
                                <a href="{{ route('key-peformance-indicator-ts.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Key Peformance Indicator Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\ObjectiveTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('objective-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Objective Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\OfficeTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('office-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Office Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\PerspectiveTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('perspective-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Perspective Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\PlanAccomplishment::class)
                            <li class="nav-item">
                                <a href="{{ route('plan-accomplishments.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Plan Accomplishments</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\PlaningYearTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('planing-year-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Planing Year Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\ReportingPeriodT::class)
                            <li class="nav-item">
                                <a href="{{ route('reporting-period-ts.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Reporting Period Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\ReportingPeriodTypeT::class)
                            <li class="nav-item">
                                <a href="{{ route('reporting-period-type-ts.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Reporting Period Type Translations</p>
                                </a>
                            </li>
                            @endcan
                            @can('view-any', App\Models\StrategyTranslation::class)
                            <li class="nav-item">
                                <a href="{{ route('strategy-translations.index') }}" class="nav-link">
                                    <i class="nav-icon icon ion-md-radio-button-off"></i>
                                    <p>Strategy Translations</p>
                                </a>
                            </li>
                            @endcan
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

                @if (Auth::user()->can('view-any', Spatie\Permission\Models\Role::class) || 
                    Auth::user()->can('view-any', Spatie\Permission\Models\Permission::class))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon icon ion-md-key"></i>
                        <p>
                            Access Management
                            <i class="nav-icon right icon ion-md-arrow-round-back"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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

                <li class="nav-item">
                    <a href="https://adminlte.io/docs/3.1//index.html" target="_blank" class="nav-link">
                        <i class="nav-icon icon ion-md-help-circle-outline"></i>
                        <p>Docs</p>
                    </a>
                </li>

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