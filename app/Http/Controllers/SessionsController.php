<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin; // Pastikan Anda mengimpor model Admin jika perlu

class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store(Request $request)
    {
        // Validasi input
        $attributes = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Cari user berdasarkan username
        $user = Admin::where('username', $attributes['username'])->first();

        // Cek apakah username ada
        if (!$user) {
            // Jika username salah, kembalikan error username
            return back()->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        // Cek apakah password benar
        if (!Auth::guard('admin')->attempt([
            'username' => $attributes['username'],
            'password' => $attributes['password']
        ], $request->filled('remember'))) {
            // Jika password salah, kembalikan error password
            return back()->withErrors(['password' => 'Password tidak valid.']);
        }

        // Jika username dan password benar, lanjutkan login
        session()->regenerate();
        return redirect()->intended('/dashboard')->with('login_success', true);
    }

    public function destroy()
    {
        // Logout dari guard admin
        Auth::guard('web')->logout();
        return redirect('/login')->with(['success' => 'You\'ve been logged out.']);
    }
}
