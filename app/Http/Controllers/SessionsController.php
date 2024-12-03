<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin; 

class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = Admin::where('username', $attributes['username'])->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        if (!Auth::guard('admin')->attempt([
            'username' => $attributes['username'],
            'password' => $attributes['password']
        ], $request->filled('remember'))) {
            return back()->withErrors(['password' => 'Password tidak valid.']);
        }

        session()->regenerate();
        return redirect()->intended('/dashboard')->with('login_success', true);
    }

    public function destroy()
    {
        Auth::guard('web')->logout();
        return redirect('/login')->with(['success' => 'You\'ve been logged out.']);
    }
}
