<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Http\Request;
use App\Models\{ActivityLog, User};

class AuthController extends Controller
{
    public function ShowRegister()
    {
        return view('auth.register');
    }

    public function ShowLogin()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'activity' => 'Registered a new account'
        ]);

        return redirect()->intended('/login')->with(['success' => 'Account Registered Successfully!']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Logged in'
            ]);

            return redirect()->route('dashboard');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Email or Password Incorrect!'])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Logged out'
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
