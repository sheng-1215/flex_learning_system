<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class FunctionController extends Controller
{
    public function login(Request $request)
    {
        $form = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($form)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'student') {
                return redirect()->route('index');
            } else if ($user->role === 'admin' || $user->role === 'lecturer') {
                return redirect()->route('admin_dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Unknown user role.'])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $form = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
            'role' => ['required', 'in:admin,lecturer,student'],
        ]);

        $form['password'] = Hash::make($form['password']);
        $user = User::create($form);
        auth()->login($user);
        return redirect()->route('index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }
}
