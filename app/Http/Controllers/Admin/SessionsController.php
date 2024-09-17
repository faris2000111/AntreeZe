<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

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

        // Login menggunakan guard admin
        if (Auth::guard('admin')->attempt([
            'username' => $attributes['username'],
            'password' => $attributes['password']
        ], $request->filled('remember'))) {
            // Regenerasi session dan redirect ke dashboard
            session()->regenerate();
            return redirect('dashboard')->with(['success' => 'You are logged in.']);
        } else {
            // Jika gagal, kembalikan error
            return back()->withErrors(['username' => 'Username or password invalid.']);
        }
    }

    public function destroy()
    {
        // Logout dari guard admin
        Auth::guard('web')->logout();
        return redirect('/login')->with(['success' => 'You\'ve been logged out.']);
    }
}
