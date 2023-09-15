<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\InititiveController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PerspectiveController;
use App\Http\Controllers\PlaningYearController;
use App\Http\Controllers\GoalTranslationController;
use App\Http\Controllers\ReportingPeriodController;
use App\Http\Controllers\ReportingPeriodTypeController;
use App\Http\Controllers\KeyPeformanceIndicatorController;

use App\Http\Controllers\Auth\LoginRegisterController;

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

Route::get('/', function () {
    return view('welcome');
});
// Route::controller(LoginRegisterController::class)->group(function() {
//     Route::get('/register', 'register')->name('register');
//     Route::post('/store', 'store')->name('store');
//     Route::get('/login', 'login')->name('login');
//     Route::post('/authenticate', 'authenticate')->name('authenticate');
//     Route::get('/dashboard', 'dashboard')->name('dashboard');
//     Route::post('/logout', 'logout')->name('logout');
// });

Route::get('lang/home', [HomeController::class, 'languageIndex']);
Route::get('lang/change', [HomeController::class, 'change'])->name('changeLang');
Route::get('languages', [LanguageController::class, 'index']);
Route::get('home', [HomeController::class, 'home'])->name('home');


Route::prefix('/')
    // ->middleware('auth')
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
        Route::resource('objectives', ObjectiveController::class);
        Route::resource('offices', OfficeController::class);
        Route::resource('perspectives', PerspectiveController::class);
        Route::resource('planing-years', PlaningYearController::class);
        Route::resource('reporting-periods', ReportingPeriodController::class);
        Route::resource(
            'reporting-period-types',
            ReportingPeriodTypeController::class
        );
        Route::resource('strategies', StrategyController::class);
    });
require __DIR__.'/auth.php';