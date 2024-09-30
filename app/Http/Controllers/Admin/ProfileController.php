<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        return view('profile.profile', compact('admin'));
    }
    public function edit(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $validatedData = $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'username' => 'required|string|max:255',
            'no_hp' => 'required|string',
        ]);

        $admin->update($validatedData);
        return redirect()->route('profile.edit')->with('success', 'Profil admin berhasil diperbarui.');
    }

    // Mengubah kata sandi admin
    public function changePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($admin) {
                if (!Hash::check($value, $admin->password)) {
                    return $fail(__('Kata sandi saat ini tidak cocok.'));
                }
            }],
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return redirect()->route('profile.change-password')->with('success', 'Kata sandi berhasil diubah.');
    }

    // Mengedit pengaturan profil usaha
    public function settings(Request $request)
    {
        $profile = Profile::first(); // Asumsi hanya ada satu profil usaha
        $validatedData = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'warna' => 'nullable|string|max:255',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/logos');
            $validatedData['logo'] = $path;
        }

        $profile->update($validatedData);
        return redirect()->route('profile.settings')->with('success', 'Pengaturan profil usaha berhasil diperbarui.');
    }
}
