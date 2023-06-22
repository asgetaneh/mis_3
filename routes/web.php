<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\InititiveController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PerspectiveController;
use App\Http\Controllers\PlaningYearController;
use App\Http\Controllers\SuitableKpiController;
use App\Http\Controllers\GoalTranslationController;
use App\Http\Controllers\ReportingPeriodController;
use App\Http\Controllers\ReportingPeriodTController;
use App\Http\Controllers\GenderTranslationController;
use App\Http\Controllers\OfficeTranslationController;
use App\Http\Controllers\PlanAccomplishmentController;
use App\Http\Controllers\ReportingPeriodTypeController;
use App\Http\Controllers\StrategyTranslationController;
use App\Http\Controllers\InititiveTranslationController;
use App\Http\Controllers\ObjectiveTranslationController;
use App\Http\Controllers\ReportingPeriodTypeTController;
use App\Http\Controllers\KeyPeformanceIndicatorController;
use App\Http\Controllers\PerspectiveTranslationController;
use App\Http\Controllers\PlaningYearTranslationController;
use App\Http\Controllers\KeyPeformanceIndicatorTController;
use App\Http\Controllers\KpiChildOneTranslationController;
use App\Http\Controllers\KpiChildTwoTranslationController;
use App\Http\Controllers\KpiChildThreeTranslationController;
use App\Http\Controllers\KpiChildOneController;
use App\Http\Controllers\KpiChildTwoController;
use App\Http\Controllers\KpiChildThreeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('welcome');});
Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

// Route::get('/dashboard', function () { return view('layouts.dashboard');});
Route::get('lang/home', [HomeController::class, 'languageIndex']);
Route::get('lang/change', [HomeController::class, 'change'])->name('changeLang');
Route::get('languages', [LanguageController::class, 'index']);
    // institution ownerships

Route::get('kpi-chain-two/{id}', [KpiChildOneController::class, 'kpiChainTwo'])->name('kpi-chain-two.create');
Route::POST('kpi-chain-two/save', [KpiChildOneController::class, 'kpiChainTwoStore'])->name('kpi-chain-two.store');
Route::DELETE('kpi-chain-two-remove/{kpiOne}/{childtwo}', [KpiChildOneController::class, 'kpiChainTwoRemove'])->name('kpi-chain-two.remove');
Route::get('kpi-chain-three/{id}', [KpiChildTwoController::class, 'kpiChainThree'])->name('kpi-chain-three.create');
Route::POST('kpi-chain-three/save', [KpiChildTwoController::class, 'kpiChainThreeStore'])->name('kpi-chain-three.store');
Route::DELETE('kpi-chain-three-remove/{kpiTwo}/{childthree}', [KpiChildTwoController::class, 'kpiChainThreeRemove'])->name('kpi-chain-three.remove');

Route::get('home', [HomeController::class, 'home'])->name('home');
Route::get('kpi_chain/{id}', [KeyPeformanceIndicatorController::class, 'kpiChain'])->name('kpi-Chain');
Route::POST('kpi_chain/save', [KeyPeformanceIndicatorController::class, 'kpiChainSave'])->name('kpi-Chain-save');
Route::DELETE('kpi_chain_remove/{kpi}/{childone}', [KeyPeformanceIndicatorController::class, 'kpiChainRemove'])->name('kpi-Chain-remove');
Route::POST('assign-office', [HomeController::class, 'assignOffice'])->name('assign-office');

Route::prefix('/')
    ->middleware('auth')
    ->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);

        Route::resource('users', UserController::class);
        Route::resource('goals', GoalController::class);
        Route::resource('goal-translations', GoalTranslationController::class);
        Route::resource('genders', GenderController::class);
        Route::resource('inititives', InititiveController::class);
        Route::resource(
            'key-peformance-indicators',
            KeyPeformanceIndicatorController::class
        );
       // Route::GET('/kpi-child-one-translations/{id}',[
       //      'as' => 'kpi-child-one-translations.add',
       //      'uses' => 'KpiChildOneTranslationController@add'
       //  ]);
         Route::resource(
            'kpi-child-one-translations',
            KpiChildOneTranslationController::class
        );
        Route::resource('kpi-child-threes', KpiChildThreeController::class);
        Route::resource(
            'kpi-child-three-translations',
            KpiChildThreeTranslationController::class
        );
        Route::resource('kpi-child-twos', KpiChildTwoController::class);
        Route::resource(
            'kpi-child-two-translations',
            KpiChildTwoTranslationController::class
        );
        Route::resource('objectives', ObjectiveController::class);
        Route::resource('office_translations', OfficeTranslationController::class);
        Route::resource('perspectives', PerspectiveController::class);
        Route::resource('planing-years', PlaningYearController::class);
        Route::resource('reporting-periods', ReportingPeriodController::class);
        Route::resource(
            'reporting-period-types',
            ReportingPeriodTypeController::class
        );
        Route::resource('strategies', StrategyController::class);
        Route::resource('suitable-kpis', SuitableKpiController::class);
        Route::resource(
            'gender-translations',
            GenderTranslationController::class
        );
        Route::resource(
            'inititive-translations',
            InititiveTranslationController::class
        );
        Route::resource(
            'key-peformance-indicator-ts',
            KeyPeformanceIndicatorTController::class
        );
        Route::resource(
            'objective-translations',
            ObjectiveTranslationController::class
        );
        Route::resource(
            'office-translations',
            OfficeTranslationController::class
        );
        Route::resource(
            'perspective-translations',
            PerspectiveTranslationController::class
        );
        Route::match(array('GET', 'POST'),'plan-accomplishment/{office}', [PlanAccomplishmentController::class, 'officeKpiObjectiveGoal'])->name('plan-accomplishment');
         Route::match(array('GET', 'POST'),'plan-accomplishment-goalclick/{office}/{goal}/{offwithkpi}', [PlanAccomplishmentController::class, 'planaccomplishmentGoalClick'])->name('plan-accomplishment-goalclick');

        Route::resource(
            'plan-accomplishments',
            PlanAccomplishmentController::class
        );
        Route::resource(
            'planing-year-translations',
            PlaningYearTranslationController::class
        );
        Route::resource(
            'reporting-period-ts',
            ReportingPeriodTController::class
        );
        Route::resource(
            'reporting-period-type-ts',
            ReportingPeriodTypeTController::class
        );
        Route::resource(
            'strategy-translations',
            StrategyTranslationController::class
        );
        Route::resource('languages', LanguageController::class);

        Route::match(array('GET', 'POST'),'suitable-kpi/{recover_request}', [SuitableKpiController::class, 'officeSuitableKpi'])->name('suitable-kpi');
        Route::GET('select-suitable-kpi', [SuitableKpiController::class, 'selectOfficeSuitableKpi'])->name('select-suitable-kpi');
        Route::POST('suitable-kpi/save', [SuitableKpiController::class, 'kpiChainSave'])->name('kpi-Chain-sae');
        Route::DELETE('kpi_chain_remove/{kpi}/{childone}', [SuitableKpiController::class, 'kpiChainRemove'])->name('kpi-Chain-remove');

        Route::GET('/get-objectives/{goal}', [PlanAccomplishmentController::class, 'getAllObjectives'])->name('get-objectives');
        Route::POST('/plan-save', [PlanAccomplishmentController::class, 'savePlan'])->name('plan.save');

    });
require __DIR__.'/auth.php';
