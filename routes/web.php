<?php

use App\Models\Office;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\KpiTypeController;
use App\Http\Controllers\BehaviorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\InititiveController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\KpiChildOneController;
use App\Http\Controllers\KpiChildTwoController;
use App\Http\Controllers\PerspectiveController;
use App\Http\Controllers\PlaningYearController;
use App\Http\Controllers\SuitableKpiController;
use App\Http\Controllers\PlanApprovalController;
use App\Http\Controllers\KpiChildThreeController;
use App\Http\Controllers\ReportApprovalController;
use App\Http\Controllers\GoalTranslationController;
use App\Http\Controllers\ReportingPeriodController;
use App\Http\Controllers\ReportingPeriodTController;
use App\Http\Controllers\GenderTranslationController;
use App\Http\Controllers\OfficeTranslationController;
use App\Http\Controllers\PlanAccomplishmentController;
use App\Http\Controllers\ReportingPeriodTypeController;
use App\Http\Controllers\StrategyTranslationController;
use App\Http\Controllers\InititiveTranslationController;
use App\Http\Controllers\InstitutionEMIS;
use App\Http\Controllers\ObjectiveTranslationController;
use App\Http\Controllers\ReportingPeriodTypeTController;
use App\Http\Controllers\KeyPeformanceIndicatorController;
use App\Http\Controllers\KpiChildOneTranslationController;
use App\Http\Controllers\KpiChildTwoTranslationController;
use App\Http\Controllers\PerspectiveTranslationController;
use App\Http\Controllers\PlaningYearTranslationController;
use App\Http\Controllers\KeyPeformanceIndicatorTController;
use App\Http\Controllers\KpiChildThreeTranslationController;
use App\Http\Controllers\StaffEMIS;
use App\Http\Controllers\StudentEMIS;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NationInstitutionIdController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\BuildingPurposeController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskMeasurementController;
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
    if(Auth::check()){
        return redirect('/dashboard');
    }

    return view('landing.index');
});
Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
Route::match(array('POST', 'GET'),'dashboard-plan-report', [HomeController::class, 'planReport'])->name('planReport');

// Route::get('/dashboard', function () { return view('layouts.dashboard');});
Route::get('lang/home', [HomeController::class, 'languageIndex']);
Route::get('lang/change', [HomeController::class, 'change'])->name('changeLang');
Route::get('languages', [LanguageController::class, 'index']);
Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback-view');
Route::get('feedback/new', [FeedbackController::class, 'create'])->name('feedback');
Route::get('feedback/save', [FeedbackController::class, 'store'])->name('feedback-save');

    // institution ownerships

// Route::get('home', [HomeController::class, 'home'])->name('home');

Route::prefix('/')
    ->middleware('auth')
    ->group(function () {

        Route::prefix('/smis')->group(function () {
            Route::prefix('/performer')->group(function () {

                Route::get('tasks/assigned-tasks', [TaskController::class, 'assignedTasksIndex'])->name('assigned-tasks.index');
                Route::POST('tasks/assigned-task-status', [TaskController::class, 'assignedTaskStatus'])->name('assigned-task.status');
                Route::POST('tasks/assigned/report', [TaskController::class, 'assignedTaskReport'])->name('assigned-task.report');

                Route::get('performer-list', [TaskController::class, 'performer'])->name('performer.index');
                Route::post('performer-add', [TaskController::class, 'addPerformer'])->name('performer-add-tooffices-save');
                Route::DELETE('performer-remove-from-office/{performer}', [TaskController::class, 'performerRemoveFromOffice'])->name('performer-remove-from-office');
                Route::resource('tasks', TaskController::class);
                Route::resource('TaskMeasurements', TaskMeasurementController::class);
                Route::get('tasks/task-assign/{id}', [TaskController::class, 'taskAssignIndex'])->name('task-assign.index');
                Route::POST('tasks/task-assign/store', [TaskController::class, 'taskAssignStore'])->name('task-assign.store');
                Route::DELETE('tasks/task-remove/{performer}/{task}', [TaskController::class, 'taskAssignRemove'])->name('task-remove.destroy');
            });
            Route::prefix('/setting')->group(function () {
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

                Route::prefix('/kpi')->group(function () {
                    Route::resource(
                        'behaviors',
                        BehaviorController::class
                    );
                    Route::resource(
                        'types',
                        KpiTypeController::class
                    );
                    Route::get('kpi-chain-two/{id}', [KeyPeformanceIndicatorController::class, 'kpiChainTwo'])->name('kpi-chain-two.create');
                    Route::POST('kpi-chain-two/save', [KeyPeformanceIndicatorController::class, 'kpiChainTwoStore'])->name('kpi-chain-two.store');
                    Route::DELETE('kpi-chain-two-remove/{kpiOne}/{childtwo}', [KeyPeformanceIndicatorController::class, 'kpiChainTwoRemove'])->name('kpi-chain-two.remove');
                    Route::get('kpi-chain-three/{id}', [KeyPeformanceIndicatorController::class, 'kpiChainThree'])->name('kpi-chain-three.create');
                    Route::POST('kpi-chain-three/save', [KeyPeformanceIndicatorController::class, 'kpiChainThreeStore'])->name('kpi-chain-three.store');
                    Route::DELETE('kpi-chain-three-remove/{kpiTwo}/{childthree}', [KeyPeformanceIndicatorController::class, 'kpiChainThreeRemove'])->name('kpi-chain-three.remove');
                    Route::get('kpi-assign-tooffices/{id}', [KeyPeformanceIndicatorController::class, 'kpiAssignToOffices'])->name('kpi-assign-tooffices');
                    Route::POST('kpi-assign-tooffices/save', [KeyPeformanceIndicatorController::class, 'kpiAssignToOfficesSave'])->name('kpi-assign-tooffices-save');
                    Route::DELETE('kpi-remove-from-office/{kpi}/{office}', [KeyPeformanceIndicatorController::class, 'kpiRemoveFromOffice'])->name('kpi-remove-from-office');


                    Route::resource(
                        'key-peformance-indicators',
                        KeyPeformanceIndicatorController::class
                    );

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

                    Route::get('kpi_chain/{id}', [KeyPeformanceIndicatorController::class, 'kpiChain'])->name('kpi-Chain');
                    Route::POST('kpi_chain/save', [KeyPeformanceIndicatorController::class, 'kpiChainSave'])->name('kpi-Chain-save');
                    Route::DELETE('kpi_chain_remove/{kpi}/{childone}', [KeyPeformanceIndicatorController::class, 'kpiChainRemove'])->name('kpi-Chain-remove');
                });



                Route::resource('goals', GoalController::class);
                Route::resource('goal-translations', GoalTranslationController::class);
                Route::resource('genders', GenderController::class);
                Route::resource('inititives', InititiveController::class);

                Route::resource('objectives', ObjectiveController::class);
                Route::resource('office_translations', OfficeTranslationController::class);

                Route::get('/office-hierarchy',
                    function () {
                        $officesList = Office::where('parent_office_id')->latest()->get();
                        return view('app.office_translations.tree', ['officesList' => $officesList]);
                    })->name('office.hierarchy');

                Route::resource('perspectives', PerspectiveController::class);
                Route::resource('planing-years', PlaningYearController::class);
                Route::post('/planing-years/activation', [PlaningYearController::class, 'activation'])->name('planing-years.activation');

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
                Route::get('office-translations/office/assign', [OfficeTranslationController::class, 'assignIndex'])->name('office-assign.index');
                Route::post('office-translations/office/assign/store', [OfficeTranslationController::class, 'assignStore'])->name('office-assign.store');
                Route::post('office-translations/office/assign/remove/{id}', [OfficeTranslationController::class, 'removeManager'])->name('office-manager.remove');
                Route::resource(
                    'perspective-translations',
                    PerspectiveTranslationController::class
                );

                Route::POST('assign-office', [HomeController::class, 'assignOffice'])->name('assign-office');

            });

            Route::prefix('/plan')->group(function () {
                Route::match(array('GET', 'POST'),'plan-accomplishment/{office}', [PlanAccomplishmentController::class, 'officeKpiObjectiveGoal'])->name('plan-accomplishment');
                Route::match(array('GET', 'POST'),'plan-accomplishment-goalclick/{office}/{goal}/{offwithkpi}', [PlanAccomplishmentController::class, 'planaccomplishmentGoalClick'])->name('plan-accomplishment-goalclick');

                Route::match(array('GET', 'POST'),'approve-plan', [PlanAccomplishmentController::class, 'approvePlanAccomplishment'])->name('approve-plan');
                Route::match(array('GET', 'POST'),'view-plan-accomplishment', [PlanAccomplishmentController::class, 'viewPlanAccomplishment'])->name('view-plan-accomplishment');

                Route::get('approve', [PlanApprovalController::class, 'viewPlanAccomplishment'])->name('plan-approve.index');
                Route::post('plan-approve', [PlanApprovalController::class, 'planApproved'])->name('plan-approve');
                Route::post('comment', [PlanApprovalController::class, 'planComment'])->name('plan-comment.store');
                Route::get('/comment/fetch/{data}', [PlanApprovalController::class, 'getOfficeKpiInfo'])->name('plan-comment.ajax')->where('data', '.*');
                Route::get('/view/comment/{data}', [PlanApprovalController::class, 'getCommentInfo'])->name('plan-comment.view-comment')->where('data', '.*');
                Route::post('/reply-comment', [PlanApprovalController::class, 'replyComment'])->name('reply-comment.store');
                Route::post('/plan-disapproval',[PlanApprovalController::class, 'planDisapproved'])->name('disapprove-plan.store');
                Route::get('/disapprove/fetch/{data}', [PlanApprovalController::class, 'disapproveInfo'])->name('disapprove-plan.ajax')->where('data', '.*');
                Route::get('/reply/fetch/{data}', [PlanApprovalController::class, 'replyInfo'])->name('reply-comment.ajax')->where('data', '.*');

                 Route::resource(
                   'plan-accomplishments',
                   PlanAccomplishmentController::class
                );

                Route::match(array('GET', 'POST'),'suitable-kpi/{recover_request}', [SuitableKpiController::class, 'officeSuitableKpi'])->name('suitable-kpi');
                Route::GET('select-suitable-kpi', [SuitableKpiController::class, 'selectOfficeSuitableKpi'])->name('select-suitable-kpi');
                Route::POST('suitable-kpi/save', [SuitableKpiController::class, 'kpiChainSave'])->name('kpi-Chain-sae');
                // Route::DELETE('kpi_chain_remove/{kpi}/{childone}', [SuitableKpiController::class, 'kpiChainRemove'])->name('kpi-Chain-remove');

                Route::GET('/get-objectives/{goal}', [PlanAccomplishmentController::class, 'getAllObjectives'])->name('get-objectives');
                Route::POST('/plan-save', [PlanAccomplishmentController::class, 'savePlan'])->name('plan.save');

                // Exporting route for planning
                Route::POST('/download', [PlanAccomplishmentController::class, 'downloadPlan'])->name('plan.download');

            });

            // reporting
            Route::prefix('/report')->group(function () {
                Route::match(array('GET', 'POST'),'reporting/{office}', [PlanAccomplishmentController::class, 'officeKpiObjectiveGoalReporting'])->name('reporting');
                // Route::match(array('GET', 'POST'),'plan-accomplishment-goalclick/{office}/{goal}/{offwithkpi}', [PlanAccomplishmentController::class, 'planaccomplishmentGoalClick'])->name('plan-accomplishment-goalclick');

                Route::get('approve', [ReportApprovalController::class, 'viewReportAccomplishment'])->name('report-approve.index');
                Route::post('report-approve', [ReportApprovalController::class, 'reportApproved'])->name('report-approve');
                Route::post('comment', [ReportApprovalController::class, 'reportComment'])->name('report-comment.store');
                Route::get('/comment/fetch/{data}', [ReportApprovalController::class, 'getOfficeKpiInfo'])->name('report-comment.ajax')->where('data', '.*');
                Route::get('/view/comment/{data}', [ReportApprovalController::class, 'getCommentInfo'])->name('report-comment.view-comment')->where('data', '.*');
                Route::post('/reply-comment', [ReportApprovalController::class, 'replyComment'])->name('replyreport-comment.store');
                Route::post('/report-disapproval',[ReportApprovalController::class, 'reportDisapproved'])->name('disapprove-report.store');
                Route::get('/disapprove/report/fetch/{data}', [ReportApprovalController::class, 'disapproveInfo'])->name('disapprove-report.ajax')->where('data', '.*');
                Route::get('/reply/fetch/{data}', [ReportApprovalController::class, 'replyInfo'])->name('replyreport-comment.ajax')->where('data', '.*');

                // Route::match(array('GET', 'POST'),'approve-plan', [PlanAccomplishmentController::class, 'approvePlanAccomplishment'])->name('approve-plan');
                Route::match(array('GET', 'POST'),'view-report-accomplishment', [PlanAccomplishmentController::class, 'viewReportAccomplishment'])->name('view-report-accomplishment');
                 Route::GET('/get-objectives-reporting/{goal}', [PlanAccomplishmentController::class, 'getAllObjectivesReporting'])->name('get-objectives-reporting');
                 Route::POST('/report-save', [PlanAccomplishmentController::class, 'saveReport'])->name('report.save');

                Route::POST('/download', [PlanAccomplishmentController::class, 'downloadReport'])->name('report.download');

            });

            Route::prefix('/report')->group(function () {
                // to be listed when working on report
            });

        });


        Route::prefix('/access/management')->group(function () {
            Route::resource('roles', RoleController::class);
            Route::get('roles/{role}/permission', [RoleController::class, 'rolePermissions'])->name('roles.permissions');
            Route::post('roles/permission/update', [RoleController::class, 'rolePermissionsUpdate'])->name('roles.permissions.update');

            Route::get('roles/{role}/user', [RoleController::class, 'roleUsers'])->name('roles.users');
            Route::post('roles/user/update', [RoleController::class, 'roleUsersUpdate'])->name('roles.users.update');

            Route::resource('permissions', PermissionController::class);
            Route::resource('users', UserController::class);
        });

Route::prefix('/emis')->group(function(){

    Route::prefix('/institution')->group(function(){
        Route::get('/student-id', [NationInstitutionIdController::class, 'index'])->name('emis.setting.student-id');
        Route::post('/student-id/import', [NationInstitutionIdController::class, 'import'])->name('emis.setting.student-id-import');

        Route::get('/campus', [CampusController::class, 'index'])->name('emis.setting.campus');
        Route::get('/building/purpose', [BuildingPurposeController::class, 'index'])->name('emis.setting.building.purpose');
        Route::get('/building/purpose/new', [BuildingPurposeController::class, 'create'])->name('emis.setting.building.purpose.new');
        Route::post('/building/purpose/save', [BuildingPurposeController::class, 'store'])->name('emis.setting.building.purpose.store');
        Route::get('/building/purpose/edit', [BuildingPurposeController::class, 'create'])->name('emis.setting.building.purpose.edit');
        Route::get('/building', [BuildingController::class, 'index'])->name('emis.institution.building');


        Route::get('/', [InstitutionEMIS::class, 'index'])->name('emis.institution.index');
     });
    Route::prefix('/student')->group(function(){
        Route::get('/applicant', [StudentEMIS::class, 'applicant'])->name('emis.student.applicant.index');
        Route::get('/overview', [StudentEMIS::class, 'overview'])->name('emis.student.overview.index');
        Route::get('/enrollment', [StudentEMIS::class, 'enrollment'])->name('emis.student.enrollment.index');
        Route::get('/results', [StudentEMIS::class, 'results'])->name('emis.student.results.index');
        Route::get('/graduates', [StudentEMIS::class, 'graduates'])->name('emis.student.graduates.index');
        Route::get('/attrition', [StudentEMIS::class, 'attrition'])->name('emis.student.attrition.index');
        Route::get('/internship', [StudentEMIS::class, 'internship'])->name('emis.student.internship.index');
        Route::get('/employment', [StudentEMIS::class, 'employment'])->name('emis.student.employment.index');

        Route::prefix('/others')->group(function(){
            Route::get('/', [StudentEMIS::class, 'others'])->name('emis.student.others.index');
            Route::get('/filter', [StudentEMIS::class, 'othersFilter'])->name('emis.student.others.filter');
        });

    });
    Route::prefix('/staff')->group(function(){
        Route::get('/overview', [StaffEMIS::class, 'overview'])->name('emis.staff.overview.index');
        Route::get('/assignment', [StaffEMIS::class, 'assignment'])->name('emis.staff.assignment.index');
        Route::get('/development', [StaffEMIS::class, 'development'])->name('emis.staff.development.index');
        Route::get('/attrition', [StaffEMIS::class, 'attrition'])->name('emis.staff.attrition.index');
        Route::get('/awards', [StaffEMIS::class, 'awards'])->name('emis.staff.awards.index');

    });

});

    });

require __DIR__.'/auth.php';
