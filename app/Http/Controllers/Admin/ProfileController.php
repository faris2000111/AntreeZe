<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('profile.profile', compact('admin')); 
    }
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        if ($request->has('current_password') && $request->has('new_password')) {
            $request->validate([
                'current_password' => ['required', function ($attribute, $value, $fail) use ($admin) {
                    if (!Hash::check($value, $admin->password)) {
                        return $fail(__('Password saat ini salah.'));
                    }
                }],
                'new_password' => 'required|string|min:6|confirmed',
            ]);
            $admin->password = Hash::make($request->new_password);
            $admin->save(); 

            return redirect()->route('profile.index', $admin->id_admin)
                ->with('success', 'Password berhasil diubah.');
        }

        $request->validate([
            'nama_admin' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'no_hp' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);
        if ($request->hasFile('avatar')) {
            $imageName = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $avatarPath = $request->file('avatar')->storeAs('avatars', $imageName, 'public');
            $admin->avatar = config('app.url') . Storage::url($avatarPath);
        }

        $admin->nama_admin = $request->nama_admin;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->no_hp = $request->no_hp;

        $admin->save();

        return redirect()->route('profile.index', $admin->id_admin)
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
