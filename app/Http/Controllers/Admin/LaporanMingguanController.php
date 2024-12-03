<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanMingguanController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $users = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'asc')
            ->get();

        $booking = DB::table('booking')
            ->join('pelayanan', 'booking.no_pelayanan', '=', 'pelayanan.no_pelayanan')
            ->join('layanan', 'layanan.id_layanan', '=', 'booking.id_layanan')
            ->join('users', 'users.id_users', '=', 'booking.id_users')
            ->select('booking.*', 'pelayanan.*', 'layanan.nama_layanan', 'users.nama_pembeli')
            ->whereBetween('booking.tanggal', [$startOfWeek, $endOfWeek])
            ->whereIn('booking.status', ['selesai', 'dibatalkan'])
            ->orderBy('booking.tanggal', 'asc')
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
                DB::raw('DAYOFWEEK(booking.tanggal) as day_of_week'),
                'layanan.nama_layanan',
                DB::raw('count(booking.id_booking) as total_booking')
            )
            ->whereBetween('booking.tanggal', [$startOfWeek, $endOfWeek])
            ->where('booking.status', 'selesai')
            ->groupBy('day_of_week', 'layanan.nama_layanan')
            ->get();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $layananNames = $bookings->pluck('nama_layanan')->unique(); 
        $chartData = [];

    function getColor($index, $defaultColors) {
        return $defaultColors[$index % count($defaultColors)];
    }

    $layananIndex = 0;
    foreach ($layananNames as $layanan) {
        $dataset = [];
        foreach (range(1, 7) as $dayIndex) {
            $totalBookingPerDay = $bookings->where('day_of_week', $dayIndex + 1)
                ->where('nama_layanan', $layanan)
                ->first();
            $dataset[] = $totalBookingPerDay ? $totalBookingPerDay->total_booking : 0;
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
        return view('laporan.laporan-mingguan', compact('admin', 'users', 'booking', 'days', 'chartData'));
    }
}
