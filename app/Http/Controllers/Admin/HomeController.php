<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    
    public function home()
    {
        $admin = Auth::guard('admin')->user();

        $profile = Profile::find(1);

        $totalKaryawan = Admin::where('role', 'karyawan')->count();

        $today = Carbon::today();
        $totalPenggunaBaru = User::whereDate('created_at', $today)->count();

         // Menghitung persentase perubahan pengguna baru (contoh sederhana)
         $yesterday = Carbon::yesterday();
         $totalPenggunaKemarin = User::whereDate('created_at', $yesterday)->count();
 
         // Menghitung persentase perubahan dibanding hari kemarin
         if ($totalPenggunaKemarin > 0) {
             $persentasePerubahan = (($totalPenggunaBaru - $totalPenggunaKemarin) / $totalPenggunaKemarin) * 100;
         } else {
             $persentasePerubahan = $totalPenggunaBaru > 0 ? 100 : 0;
         }
        
        return view('dashboard', compact('admin', 'totalPenggunaBaru', 'totalKaryawan', 'persentasePerubahan'));
    }
}
