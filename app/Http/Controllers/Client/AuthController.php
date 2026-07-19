<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/client/dashboard');
        }

        return back()->withErrors([
            'phone' => 'Invalid credentials.',
        ]);
    }

    public function showRegister()
    {
        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'email' => ['nullable', 'email', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'referral_code' => ['nullable', 'string'],
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['referral_code'] = Str::random(10);
        $validated['role'] = 'user';
        $validated['status'] = 'active';
        $validated['balance_investissable'] = 0;
        $validated['balance_retirable'] = 10; // Welcome offer

        // Handle referral
        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            if ($referrer) {
                $validated['referred_by'] = $referrer->id;
            }
        }

        $user = User::create($validated);

        Auth::login($user);

        return redirect('/client/dashboard')->with('success', 'Welcome! You received a $10 welcome bonus.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}