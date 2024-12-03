<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanBulananController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();

        $currentYear = Carbon::now()->year;
        $startOfYear = Carbon::now()->startOfYear();  
        $endOfYear = Carbon::now()->endOfYear();  

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $users = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->orderBy('created_at', 'asc')
            ->get();

        $defaultColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(201, 203, 207, 0.7)'
        ];

        $bookings = DB::table('booking')
            ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
            ->select(
                DB::raw('MONTH(booking.tanggal) as month'),
                'layanan.nama_layanan',
                DB::raw('count(booking.id_booking) as total_booking')
            )
            ->whereYear('booking.tanggal', $currentYear)
            ->where('booking.status', 'selesai')
            ->groupBy('month', 'layanan.nama_layanan')
            ->get();

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $layananNames = $bookings->pluck('nama_layanan')->unique(); 
        $chartData = [];

    function getColor($index, $defaultColors) {
        return $defaultColors[$index % count($defaultColors)]; 
    }

    $layananIndex = 0; 
    foreach ($layananNames as $layanan) {
        $dataset = [];
        foreach (range(1, 12) as $month) {
            $totalBookingPerMonth = $bookings->where('month', $month)
                ->where('nama_layanan', $layanan)
                ->first();
            $dataset[] = $totalBookingPerMonth ? $totalBookingPerMonth->total_booking : 0;
        }

        $color = getColor($layananIndex, $defaultColors);
        $chartData[] = [
            'label' => $layanan,
            'data' => $dataset,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'borderWidth' => 1
        ];
        $layananIndex++; 
    }
        $booking = DB::table('booking')
            ->join('pelayanan', 'booking.no_pelayanan', '=', 'pelayanan.no_pelayanan')
            ->join('layanan', 'layanan.id_layanan', '=', 'booking.id_layanan')
            ->join('users', 'users.id_users', '=', 'booking.id_users')
            ->select('booking.*', 'pelayanan.*', 'layanan.nama_layanan', 'users.nama_pembeli')
            ->whereYear('booking.tanggal', $currentYear)
            ->whereIn('booking.status', ['selesai', 'dibatalkan'])
            ->orderBy('booking.tanggal', 'asc')
            ->get();

        return view('laporan.laporan-bulanan', compact('admin', 'users', 'booking', 'months', 'chartData'));
    }
}
