<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\LoginHistory;



class AuthController extends Controller
{
    // 🔹 AFFICHAGE
    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function showForgot() {
        return view('auth.forgot-password');
    }

    // 🔹 REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|confirmed|min:6',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 📧 Email verification
        $user->sendEmailVerificationNotification();

        return redirect('/login')->with('success', 'Compte créé. Vérifiez votre email.');

        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo')->store('profiles', 'public');
        } else {
            $photo = 'profiles/default.png';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_photo' => $photo,
            'country' => $request->country,
            'city' => $request->city,
        ]);

        if ($request->role === 'vendor') {
            Store::create([
                'user_id' => $user->id,
                'store_name' => $request->store_name,
                'product_type' => $request->product_type,
                'description' => $request->description,
            ]);
        }

        if ($request->role === 'freelancer') {
            Freelancer::create([
                'user_id' => $user->id,
                'domain' => $request->domain,
                'skills' => $request->skills,
                'bio' => $request->bio,
                'price' => $request->price,
                'portfolio' => $request->portfolio,
            ]);
        }

        $user->sendEmailVerificationNotification();
    }

    // 🔹 LOGIN
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->with('error', 'Email ou mot de passe incorrect');
        }

        $user = Auth::user();

        // 🔒 Email non vérifié
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return back()->with('error', 'Veuillez vérifier votre email');
        }

        // 🔐 sécurité session
        $request->session()->regenerate();

        // 🔁 redirection par rôle
        return match($user->role) {
            'admin' => redirect('/admin'),
            'vendor' => redirect('/vendor'),
            'freelancer' => redirect('/freelancer'),
            'super-admin' => redirect('/super-admin'),
            default => redirect('/')
        };

        $key = 'login_'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('error', 'Trop de tentatives. Réessayez dans 1 minute.');
        }

        if (!Auth::attempt($credentials, $request->remember)) {
            RateLimiter::hit($key, 60);
            return back()->with('error', 'Email ou mot de passe incorrect');
        }

        RateLimiter::clear($key);

        LoginHistory::create([
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        event(new NewLoginNotification($user));
    }

    // 🔹 FORGOT PASSWORD
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Password::sendResetLink($request->only('email'));

        return back()->with('success', 'Email envoyé');
    }

    // 🔹 LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
