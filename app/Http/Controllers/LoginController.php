<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = auth()->user();

            // Superadmin diarahkan ke manajemen karyawan
            if ($user->role === 'superadmin') {
                return redirect()->route('karyawan.index');
            }

            // Staff (karyawan) dan manager diarahkan ke dashboard umum
            if (in_array($user->role, ['karyawan', 'manager'])) {
                return redirect()->route('dashboard');
            }

            // Fallback: jika role tidak dikenali, kembali ke dashboard
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}

?>