use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\SupplyCenterController;
use App\Http\Controllers\Api\AssignmentController;

Route::apiResource('staff', StaffController::class);
Route::apiResource('supply-centers', SupplyCenterController::class);
Route::apiResource('assignments', AssignmentController::class)->except(['create', 'edit']);
Route::post('assignments/auto-assign', [AssignmentController::class, 'autoAssign']);