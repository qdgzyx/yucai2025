<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 将需要认证的路由移入此分组
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // 添加认证保护的量化路由
    Route::get('/banjigroup/{banji}/quantify-today', [\App\Http\Controllers\BanjisController::class, 'quantifyToday']);
});

Route::get('/banjis', function (Request $request) {
    $gradeId = $request->input('grade_id');
    $banjis = \App\Models\Banji::where('grade_id', $gradeId)
        ->select('id', 'name')
        ->get();
    return response()->json($banjis);
});

Route::get('/quantify/semester-report', [\App\Http\Controllers\QuantifyReportController::class, 'apiSemesterReport']);

Route::get('/quantify-items/{id}', function ($id) {
    $item = \App\Models\QuantifyItem::findOrFail($id);
    return response()->json([
        'score' => $item->score
    ]);
});
