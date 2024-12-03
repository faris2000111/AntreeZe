<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanTahunanController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();

        $startOfYear = Carbon::now()->startOfYear();  
        $endOfYear = Carbon::now()->endOfYear();    

        $users = User::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->orderBy('created_at', 'asc')
            ->get();

        $years = DB::table('booking')
            ->select(DB::raw('YEAR(booking.tanggal) as year'))
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year');
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
                DB::raw('YEAR(booking.tanggal) as year'),
                'layanan.nama_layanan',
                DB::raw('count(booking.id_booking) as total_booking')
            )
            ->whereIn(DB::raw('YEAR(booking.tanggal)'), $years)
            ->where('booking.status', 'selesai')
            ->groupBy('year', 'layanan.nama_layanan')
            ->get();

        $layananNames = $bookings->pluck('nama_layanan')->unique(); 
        $chartData = [];

    function getColor($index, $defaultColors) {
        return $defaultColors[$index % count($defaultColors)]; 
    }
    $layananIndex = 0; 
    foreach ($layananNames as $layanan) {
        $dataset = [];
        foreach ($years as $year) { 
            $totalBookingPerYear = $bookings->where('year', $year)
                ->where('nama_layanan', $layanan)
                ->first();
            $dataset[] = $totalBookingPerYear ? $totalBookingPerYear->total_booking : 0;
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
            ->whereIn(DB::raw('YEAR(booking.tanggal)'), $years)
            ->whereIn('booking.status', ['selesai', 'dibatalkan'])
            ->orderBy('booking.tanggal', 'asc')
            ->get();

        return view('laporan.laporan-tahunan', compact('admin', 'users', 'booking', 'years', 'chartData'));
    }
}
