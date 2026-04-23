<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Freelancer;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 🔹 REGISTER
    public function register(Request $request)
    {
        // ❌ bloquer super-admin
        if ($request->role === 'super-admin') {
            return response()->json(['message' => 'Interdit'], 403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
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

        // 🎯 ROLE LOGIC
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
            ]);
        }

        return response()->json(['message' => 'Compte créé']);
    }

    // 🔹 LOGIN
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        $user = Auth::user();

        // 🔒 vérifier email
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Veuillez vérifier votre email'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // 📊 Historique
        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
        ]);

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}
