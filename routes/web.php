<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'ReportsController@create')->name('root');

// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');

Route::middleware(['auth'])->group(function () {
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// 再次确认密码（重要操作前提示）
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);
// 在用户相关路由组中添加模板下载路由
Route::get('/user/template', [App\Http\Controllers\UsersController::class, 'downloadTemplate'])->name('user.template');

Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');
Route::resource('replies', 'RepliesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);

Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');
Route::resource('grades', 'GradesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('banjis', 'BanjisController', ['only' => ['index', 'show','assignmentshow', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('reports', 'ReportsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('/report/summary', [App\Http\Controllers\ReportsController::class, 'summary']);
// 分年级出勤汇总
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/report/summary/{grade}', [App\Http\Controllers\ReportsController::class, 'summaryByGrade'])
    ->whereNumber('grade') // 限制为数字
    ->name('reports.summary.grade');
 });
// 分年级导出Excel
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/report/export/{grade}', [App\Http\Controllers\ReportsController::class, 'exportByGrade'])
    ->whereNumber('grade')
    ->name('reports.export.grade');
    });
Route::get('/banji/import', [App\Http\Controllers\BanjisController::class, 'showForm'])->name('banji.import');
Route::post('/banji/import', [App\Http\Controllers\BanjisController::class, 'import'])->middleware('auth');;
Route::get('/user/import', [App\Http\Controllers\UsersController::class, 'showForm'])->name('user.import');
Route::post('/user/import', [App\Http\Controllers\UsersController::class, 'import'])->middleware('auth');;


Route::resource('subjects', 'SubjectsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('assignments', 'AssignmentsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::post('assignments/{assignment}/approve', [App\Http\Controllers\AssignmentsController::class, 'approve'])->name('assignments.approve');
Route::post('assignments/{assignment}/reject', [App\Http\Controllers\AssignmentsController::class, 'reject'])->name('assignments.reject');

// 带日期参数的班级作业展示路由
Route::get('/banji/{banji}/assignments', [App\Http\Controllers\AssignmentsController::class, 'show'])
     ->name('banjis.assignments')->middleware('auth');

Route::resource('teacher-banji-subjects', App\Http\Controllers\TeacherBanjiSubjectController::class, ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']])
     ->middleware('auth');

// 新增导入路由
Route::get('/teacher-banji-subject/import', [App\Http\Controllers\TeacherBanjiSubjectController::class, 'showForm'])->name('teacher-banji-subject.import');
Route::post('/teacher-banji-subject/import', [App\Http\Controllers\TeacherBanjiSubjectController::class, 'import'])->middleware('auth');

Route::get('/teacher-banji-subject/template', [App\Http\Controllers\TeacherBanjiSubjectController::class, 'downloadTemplate'])->name('teacher-banji-subject.template');
Route::get('/teacher-banji-subjects/department-schedule', [App\Http\Controllers\TeacherBanjiSubjectController::class, 'departmentSchedule'])->name('teacher-banji-subjects.department-schedule');
Route::get('/teacher-banji-subjects/export', [TeacherBanjiSubjectController::class, 'export'])->name('teacher-banji-subjects.export');

Route::get('/banji/template', [App\Http\Controllers\BanjisController::class, 'downloadTemplate'])->name('banji.template');
Route::resource('academics', 'AcademicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('semesters', 'SemestersController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('quantify_types', 'QuantifyTypesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('quantify_items', 'QuantifyItemsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('quantify_records', 'QuantifyRecordsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::post('/quantify_records/batch', [\App\Http\Controllers\QuantifyRecordsController::class, 'destroyBatch'])
     ->name('quantify_records.destroyBatch');

Route::get('quantify/display', [\App\Http\Controllers\QuantifyDisplayController::class, 'index'])
    ->name('quantify.display');

Route::get('quantify/semester-report', [\App\Http\Controllers\QuantifyReportController::class, 'semesterReport'])
    ->name('quantify.semester_report');

    // 调整小组量化路由到认证保护组内
    Route::get('/group-quantifications', [\App\Http\Controllers\GroupQuantificationController::class, 'index'])
        ->name('group_quantifications.index');
    Route::get('/group-quantifications/create', [\App\Http\Controllers\GroupQuantificationController::class, 'create'])
        ->name('group_quantifications.create');
    Route::post('/group-quantifications', [\App\Http\Controllers\GroupQuantificationController::class, 'store'])
        ->name('group_quantifications.store');
Route::resource('notifications', '\App\Http\Controllers\NotificationsController', ['only' => ['index']]);
Route::get('/user/teaching-schedule', [\App\Http\Controllers\UsersController::class, 'teachingSchedule'])
     ->name('users.teaching-schedule');

Route::resource('group-basic-infos', '\App\Http\Controllers\GroupBasicInfoController')->middleware('auth');
Route::get('/group-basic-info/import', [\App\Http\Controllers\GroupBasicInfoController::class, 'showForm'])
         ->name('group-basic-infos.import');
    Route::post('/group-basic-info/import', [\App\Http\Controllers\GroupBasicInfoController::class, 'import'])
         ->middleware('auth');
    
    // 新增小组量化项目路由
    Route::resource('group_quantify_items', 'GroupQuantifyItemsController');
    
    // 新增小组量化公示路由
    Route::get('/group-quantify/display', [\App\Http\Controllers\GroupQuantificationController::class, 'groupDisplay'])
         ->name('group_quantify.display');
});
Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
