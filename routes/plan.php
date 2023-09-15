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
 
Route::get('home2', [HomeController::class, 'home'])->name('home');
Route::get('kpi_chain2/{id}', [KeyPeformanceIndicatorController::class, 'kpiChain'])->name('kpi-Chain');
Route::POST('kpi_chain2/save', [KeyPeformanceIndicatorController::class, 'kpiChainSave'])->name('kpi-Chain-save');
Route::DELETE('kpi_chain_remove2/{kpi}/{childone}', [KeyPeformanceIndicatorController::class, 'kpiChainRemove'])->name('kpi-Chain-remove');
require __DIR__.'/auth.php';
