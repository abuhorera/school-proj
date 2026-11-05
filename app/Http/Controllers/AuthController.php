<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\School;

class AuthController extends Controller
{
    public function showLogin()
    {
        $school = School::first();
        return view('auth.login', compact('school'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function demoLogin($role)
    {
        $emailMap = [
            'schooladmin' => 'admin@yugschool.com',
            'superadmin' => 'superadmin@multischoolerp.com',
            'teacher' => 'teacher@yugschool.com',
            'accountant' => 'accountant@yugschool.com',
        ];

        $email = $emailMap[strtolower($role)] ?? 'admin@yugschool.com';
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::first();
        }

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();
        }

        return redirect()->route('dashboard')->with('success', 'Logged in as ' . ($user->role ?? 'School Admin'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

