<?php
declare(strict_types=1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'authenticate']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::delete('/logout', [AuthController::class, 'logout']);

});
