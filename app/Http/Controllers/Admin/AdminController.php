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
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'no_hp' => 'required',
            'alamat' => 'required',
        ], [
            'nama_admin.required' => 'Nama tidak boleh kosong!',
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Format email tidak valid!',
            'username.required' => 'Username tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
            'password.confirmed' => 'Password dan konfirmasi password tidak sama!',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong!',
            'no_hp.required' => 'Nomor HP tidak boleh kosong!',
            'alamat.required' => 'Alamat tidak boleh kosong!',
        ]);
        
        // Membuat data karyawan baru
        Admin::create([
            'nama_admin' => $request->nama_admin,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => 'karyawan',
            'remember_token' => Str::random(60),
        ]);

        // Redirect setelah data berhasil disimpan
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
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        $karyawan = Admin::findOrFail($id);
        $karyawan->nama_admin = $request->nama_admin;
        $karyawan->email = $request->email;
        $karyawan->username = $request->username;
        if ($request->password) {
            $karyawan->password = Hash::make($request->password);
        }
        $karyawan->no_hp = $request->no_hp;
        $karyawan->alamat = $request->alamat;

        $karyawan->save();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    // Fungsi untuk menghapus karyawan
    public function destroy($id)
    {
        $karyawan = Admin::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
