<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporanMingguanController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Ambil data booking dalam rentang waktu seminggu terakhir
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
    
        // Ambil data booking dengan status selesai selama satu minggu
        $booking = DB::table('booking')
            ->join('pelayanan', 'booking.no_pelayanan', '=', 'pelayanan.no_pelayanan')
            ->join('layanan', 'layanan.id_layanan', '=', 'booking.id_layanan')
            ->join('users', 'users.id_users', '=', 'booking.id_users')
            ->select('booking.*', 'pelayanan.*', 'layanan.nama_layanan', 'users.nama_pembeli')
            ->whereBetween('booking.tanggal', [$startOfWeek, $endOfWeek])
            ->where('booking.status', 'selesai')
            ->orderBy('booking.tanggal', 'asc')
            ->get();
    
        // Tentukan jam buka dan tutup (misal dari 08:00 hingga 18:00)
        $jamBuka = 8;
        $jamTutup = 18;
        $jamBukaTutup = [];
        
        // Generate jam mulai dari jam buka sampai jam tutup
        for ($i = $jamBuka; $i <= $jamTutup; $i++) {
            $jamBukaTutup[] = $i . ":00";
        }
    
        // Inisialisasi chartData untuk menampung kepadatan antrian dan booking per jam
        $chartData = [
            'antrian' => array_fill(0, 7, array_fill(0, $jamTutup - $jamBuka + 1, 0)), // Kepadatan antrian per hari dan jam
            'booking' => array_fill(0, 7, array_fill(0, $jamTutup - $jamBuka + 1, 0))  // Data booking per hari dan jam
        ];
    
        // Hitung jumlah booking per hari dan jam
        foreach ($booking as $bok) {
            $hari = Carbon::parse($bok->tanggal)->dayOfWeek; // Mendapatkan hari dari tanggal
            $jam = Carbon::parse($bok->jam_booking)->hour; // Mendapatkan jam dari waktu booking
            
            if ($jam >= $jamBuka && $jam <= $jamTutup) {
                $indexJam = $jam - $jamBuka; // Hitung index untuk jam (offset dari jam buka)
                $chartData['antrian'][$hari][$indexJam]++; // Tambahkan jumlah antrian per hari dan jam
                $chartData['booking'][$hari][$indexJam]++; // Tambahkan jumlah booking per hari dan jam
            }
        }
    
        return view('laporan-mingguan.laporan-mingguan', compact('admin', 'booking', 'chartData', 'jamBukaTutup', 'jamBuka', 'jamTutup'));
    }
}    