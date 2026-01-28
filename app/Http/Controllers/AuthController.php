<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required ',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if (!$user) {
            return redirect()->back()->with('error', 'not registered');
        }
        return redirect()->route('login')->with('success', ' registered successfully');
    }

    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:5'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User with this email ot found');
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'password incorrect');
        }

        Auth::login($user);
        return redirect()->route('todos.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('todos.index');
    }
}
