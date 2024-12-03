<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $karyawan = Admin::where('role', 'karyawan')
                 ->orderBy('created_at', 'asc')
                 ->get();
        return view('karyawan.karyawan', compact('karyawan'));
    }
    public function create()
    {
        return view('karyawan.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
            'no_hp' => 'required|min:10|max:14',
        ], [
            'nama_admin.required' => 'Nama tidak boleh kosong!',
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Format email tidak valid!',
            'username.required' => 'Username tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
            'password.confirmed' => 'Password dan konfirmasi password tidak sama!',
            'password.min' => 'Password kurang dari 8 karakter!',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong!',
            'password_confirmation.min' => 'Konfirmasi Password kurang dari 8 karakter!',
            'no_hp.required' => 'Nomor HP tidak boleh kosong!',
            'no_hp.min' => 'Nomor HP harus terdiri dari minimal 10 angka!',
            'no_hp.max' => 'Nomor HP tidak boleh lebih dari 14 angka!',
        ]);
        $avatarUrl = 'https://api.dicebear.com/9.x/avataaars-neutral/svg?seed=' . urlencode($request->nama_admin);

        Admin::create([
            'nama_admin' => $request->nama_admin,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'role' => 'karyawan',
            'avatar' => $avatarUrl, 
            'remember_token' => Str::random(60),
        ]);
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }
    public function edit($id)
    {
        $karyawan = Admin::findOrFail($id);
        return view('karyawan.edit', compact('karyawan'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_admin' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'no_hp' => 'required|min:10|max:14',
        ]);

        $karyawan = Admin::findOrFail($id);
        $karyawan->nama_admin = $request->nama_admin;
        $karyawan->email = $request->email;
        $karyawan->username = $request->username;
        if ($request->password) {
            $karyawan->password = Hash::make($request->password);
        }
        $karyawan->no_hp = $request->no_hp;
        $karyawan->save();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $karyawan = Admin::findOrFail($id);
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
