<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// PAGE ACCUEIL
Route::get('/', fn() => view('welcome'));

// SOCIALITE (GOOGLE)
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = \App\Models\User::updateOrCreate(
        ['email' => $googleUser->email],
        [
            'name' => $googleUser->name,
            'role' => 'client',
            'email_verified_at' => now(),
        ]
    );

    \Illuminate\Support\Facades\Auth::login($user);

    return redirect('/');
});

// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showForgot']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::post('/logout', [AuthController::class, 'logout']);

// DASHBOARD PAR ROLE
Route::middleware(['auth'])->group(function () {

    Route::get('/admin', function () {
        return "Admin Dashboard";
    })->middleware('role:admin');

    Route::get('/super-admin', function () {
        return "Super Admin Dashboard";
    })->middleware('role:super-admin');

    Route::get('/vendor', function () {
        return "Vendor Dashboard";
    })->middleware('role:vendor');

    Route::get('/freelancer', function () {
        return "Freelancer Dashboard";
    })->middleware('role:freelancer');

});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
});

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return back()->with('status', __($status));
});

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['request' => request()]);
})->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {

    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
    ]);

    $status = \Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->update([
                'password' => bcrypt($password)
            ]);
        }
    );

    return redirect('/login')->with('success', 'Mot de passe modifié');
});

// Vérification email page
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Lien email cliqué
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/login')->with('success', 'Email vérifié avec succès');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Renvoyer email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Email renvoyé');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/profile', function () {
    return view('profile.edit');
})->middleware('auth');

Route::post('/profile/update', function (\Illuminate\Http\Request $request) {

    $user = auth()->user();

    if ($request->hasFile('profile_photo')) {
        $photo = $request->file('profile_photo')->store('profiles', 'public');
        $user->profile_photo = $photo;
    }

    $user->update($request->only('name', 'email', 'phone'));

    return back()->with('success', 'Profil mis à jour');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});
