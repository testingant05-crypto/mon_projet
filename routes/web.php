<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/admin', function () {
        return "Admin Dashboard";
    })->middleware('role:admin');

});

require __DIR__.'/auth.php';
