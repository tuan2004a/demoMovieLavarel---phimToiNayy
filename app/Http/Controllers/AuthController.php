<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $req){
        $req -> validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'password'],
        ]);

        if (Auth::attempt($req->only('email', 'password'))) {
            return response()->json([
                'user' => Auth::user(),
                'message' => 'Successfully logged in',
            ]);
        }
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
        return redirect()->to('/');
    }
    public function registation(Request $req){
        $req -> validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'password' => ['required','min:8','confirmed'],
        ]);
        User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);
        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }
}