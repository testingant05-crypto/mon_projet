<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // PHOTO
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo')->store('profiles', 'public');
        } else {
            $photo = $user->profile_photo;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profile_photo' => $photo,
        ]);

        return back()->with('success', 'Profil mis à jour');
    }
}
