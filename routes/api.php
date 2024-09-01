<?php
declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'authenticate']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::delete('/logout', [AuthController::class, 'logout']);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    Route::get('/tasks/{taskId}/comments', [TaskCommentController::class, 'show']);
    Route::post('/tasks/{taskId}/comments', [TaskCommentController::class, 'store']);
    Route::delete('/comments/{id}', [TaskCommentController::class, 'destroy']);

});
