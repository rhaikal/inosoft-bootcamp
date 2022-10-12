<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controller\TaskController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('task')->group(function() {
    Route::get('', [TaskController::class, 'showTasks']);
    Route::post('', [TaskController::class, 'createTask']);
    Route::patch('/{id}', [TaskController::class, 'updateTask']);

    // NOTE: lanjutkan tugas assignment di routing baru dibawah ini
    Route::delete('/{id}', [TaskController::class, 'deleteTask']);
    Route::patch('/{id}/assign', [TaskController::class, 'assignTask']);
    Route::patch('/{id}/unassign', [TaskController::class, 'unassignTask']);
    Route::post('/{id}/subtask', [TaskController::class, 'createSubtask']);
    Route::patch('/{id}/subtask/{subtaskId}', [TaskController::class, 'updateSubtask']);
    Route::delete('/{id}/subtask/{subtaskId}', [TaskController::class, 'deleteSubtask']);
});