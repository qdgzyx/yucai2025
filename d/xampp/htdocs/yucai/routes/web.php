
Route::get('/banji/template', [App\Http\Controllers\BanjisController::class, 'downloadTemplate'])->name('banji.template');

Route::post('quantify_records/{quantify_record}/archive', [QuantifyRecordsController::class, 'archive'])->name('quantify_records.archive');
