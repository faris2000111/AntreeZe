<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $admin = Auth::guard('admin')->user();
        $profile = Profile::find(1);

        // Calculating totals
        $totalKaryawan = Admin::where('role', 'karyawan')->count();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Total new users and percentage change
        $totalPenggunaBaru = User::whereDate('created_at', $today)->count();
        $totalPenggunaKemarin = User::whereDate('created_at', $yesterday)->count();
        $persentasePerubahan = $this->calculatePercentageChange($totalPenggunaBaru, $totalPenggunaKemarin);

        // Total new bookings and percentage change
        $totalBookingBaru = Booking::whereDate('created_at', $today)->count();
        $totalBookingKemarin = Booking::whereDate('created_at', $yesterday)->count();
        $persentasePerubahan1 = $this->calculatePercentageChange($totalBookingBaru, $totalBookingKemarin);

        // Total successful bookings and percentage change
        $totalBookingBerhasil = Booking::whereDate('created_at', $today)->where('status', 'selesai')->count();
        $totalBerhasilKemarin = Booking::whereDate('created_at', $yesterday)->where('status', 'selesai')->count();
        $persentasePerubahan2 = $this->calculatePercentageChange($totalBookingBerhasil, $totalBerhasilKemarin);

        // Monthly performance data
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalBookings = Booking::whereMonth('created_at', $currentMonth)
                                ->whereYear('created_at', $currentYear)
                                ->count();

        $successfulBookings = Booking::where('status', 'selesai')
                                     ->whereMonth('created_at', $currentMonth)
                                     ->whereYear('created_at', $currentYear)
                                     ->count();

        $cancelledBookings = Booking::where('status', 'dibatalkan')
                                    ->whereMonth('created_at', $currentMonth)
                                    ->whereYear('created_at', $currentYear)
                                    ->count();

        // Most popular service this month
        $popularService = Booking::join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                                 ->select('layanan.nama_layanan', DB::raw('count(booking.id_booking) as total'))
                                 ->whereMonth('booking.created_at', $currentMonth)
                                 ->whereYear('booking.created_at', $currentYear)
                                 ->groupBy('layanan.nama_layanan')
                                 ->orderBy('total', 'desc')
                                 ->first();

        $popularServiceName = $popularService ? $popularService->nama_layanan : 'Tidak ada data';

        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $popularServices = Booking::join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                                  ->select('layanan.nama_layanan', DB::raw('count(booking.id_booking) as total'))
                                  ->whereDate('booking.created_at', '>=', $thirtyDaysAgo)
                                  ->groupBy('layanan.nama_layanan')
                                  ->orderBy('total', 'desc')
                                  ->limit(5)
                                  ->get();

        // Returning the view with compact data
        return view('dashboard', compact(
            'admin', 
            'totalBookings', 
            'successfulBookings', 
            'cancelledBookings', 
            'totalPenggunaBaru', 
            'totalKaryawan', 
            'persentasePerubahan', 
            'totalBookingBaru', 
            'persentasePerubahan1', 
            'totalBookingBerhasil', 
            'persentasePerubahan2', 
            'popularServiceName',
            'popularServices'
        ));
    }

    // Helper function to calculate percentage change
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous > 0) {
            return (($current - $previous) / $previous) * 100;
        }
        return $current > 0 ? 100 : 0;
    }
}
