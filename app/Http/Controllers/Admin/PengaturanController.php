<?php

namespace App\Http\Controllers\Admin;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        return view('profile.pengaturan', compact('profile'));
    }
    public function update(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);

        $request->validate([
            'nama_usaha' => 'nullable|string|max:255',
            'warna' => 'nullable|string|max:255',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
            'banner1' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
            'banner2' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
            'banner3' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $logoOriginalName = $request->file('logo')->getClientOriginalName();
            $logoPath = $request->file('logo')->storeAs('logos', $logoOriginalName, 'public');
            $profile->logo = url(Storage::url($logoPath)); 
        }

        if ($request->hasFile('banner1')) {
            if ($profile->banner1) {
                Storage::disk('public')->delete($profile->banner1);
            }
            $bannerOriginalName1 = $request->file('banner1')->getClientOriginalName();
            $bannerPath1 = $request->file('banner1')->storeAs('banners', $bannerOriginalName1, 'public');
            $profile->banner1 = url(Storage::url($bannerPath1));
        }
        if ($request->hasFile('banner2')) {
            if ($profile->banner2) {
                Storage::disk('public')->delete($profile->banner2);
            }
            $bannerOriginalName2 = $request->file('banner2')->getClientOriginalName();
            $bannerPath2 = $request->file('banner2')->storeAs('banners', $bannerOriginalName2, 'public');
            $profile->banner2 = url(Storage::url($bannerPath2));
        }
        if ($request->hasFile('banner3')) {
            if ($profile->banner3) {
                Storage::disk('public')->delete($profile->banner3);
            }
            $bannerOriginalName3 = $request->file('banner3')->getClientOriginalName();
            $bannerPath3 = $request->file('banner3')->storeAs('banners', $bannerOriginalName3, 'public');
            $profile->banner3 = url(Storage::url($bannerPath3));
        }

        $profile->nama_usaha = $request->input('nama_usaha');
        $profile->warna = $request->input('warna');

        if ($request->filled('jam_buka')) {
            $profile->jam_buka = $request->input('jam_buka');
        }

        if ($request->filled('jam_tutup')) {
            $profile->jam_tutup = $request->input('jam_tutup');
        }
        $profile->save();

        return redirect()->route('pengaturan.index')->with('success', 'Profile updated successfully.');
    }
}
