<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;

// 🔐 AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// 📧 EMAIL VERIFICATION
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email vérifié']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// 👤 USER
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// 🔑 FORGOT PASSWORD
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return response()->json(['status' => $status]);
});

// 🔁 RESET PASSWORD
Route::post('/reset-password', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        }
    );

    return response()->json(['status' => $status]);
});
