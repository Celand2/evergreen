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
            'phone'    => ['required', 'string'],
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

    public function showRegister(Request $request)
    {
        $referralCode = $request->query('ref');
        return view('client.auth.register', compact('referralCode'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'phone'          => ['required', 'string', 'unique:users,phone'],
            'email'          => ['nullable', 'email', 'max:255'],
            'country'        => ['required', 'string', 'max:255'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'referral_code'  => ['nullable', 'string'],
        ]);

        $referrerCode = $request->input('referral_code');
        $registrationIp = $request->ip();

        $userData = [
            'name'                   => $validated['name'],
            'phone'                  => $validated['phone'],
            'email'                  => $validated['email'] ?? null,
            'country'                => $validated['country'],
            'password'               => bcrypt($validated['password']),
            'referral_code'          => Str::upper(Str::random(8)),
            'role'                   => 'user',
            'status'                 => 'active',
            'balance_investissable'  => 0,
            'balance_retirable'      => 0.5, // Welcome offer $0.5
            'registration_ip'        => $registrationIp,
        ];

        // Gestion du parrainage
        $referrer = null;
        if ($referrerCode) {
            $referrer = User::where('referral_code', $referrerCode)->first();
            if ($referrer) {
                $userData['referred_by'] = $referrer->id;
            }
        }

        $user = User::create($userData);

        // Anti-fraude : même IP que le parrain à l'inscription → gel du parrain
        if ($referrer && $referrer->registration_ip && $referrer->registration_ip === $registrationIp) {
            $referrer->update(['is_frozen' => true]);

            \App\Models\Notification::create([
                'user_id' => $referrer->id,
                'title'   => 'Account under review',
                'body'    => 'Suspicious activity detected on your referral network. Your account has been temporarily frozen pending review.',
            ]);
        }

        Auth::login($user);

        $welcomeMessage = $user->currency
            ? 'Welcome! You received a ' . $user->toLocal(0.5) . ' welcome bonus.'
            : 'Welcome! You received a $0.50 USD welcome bonus.';

        return redirect('/client/dashboard')->with('success', $welcomeMessage);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
