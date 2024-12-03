<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Profile;
use App\Events\LoketUpdated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $admin = DB::table('admin')
                ->join('pelayanan', 'admin.id_admin', '=', 'pelayanan.id_admin') // Join berdasarkan no_pelayanan
                ->where('admin.id_admin', auth()->id()) // Berdasarkan admin yang sedang login
                ->select('admin.*', 'pelayanan.*') // Pilih kolom yang diinginkan
                ->first();
        $profile = Profile::find(1);

        // Data Karyawan, Pengguna Baru, dan Perubahan Persentase
        $totalKaryawan = Admin::where('role', 'karyawan')->count();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $totalPenggunaBaru = User::whereDate('created_at', $today)->count();
        $totalPenggunaKemarin = User::whereDate('created_at', $yesterday)->count();
        $persentasePerubahan = $this->calculatePercentageChange($totalPenggunaBaru, $totalPenggunaKemarin);

        $totalBookingBaru = Booking::whereDate('created_at', $today)->count();
        $totalBookingKemarin = Booking::whereDate('created_at', $yesterday)->count();
        $persentasePerubahan1 = $this->calculatePercentageChange($totalBookingBaru, $totalBookingKemarin);

        $totalBookingBerhasil = Booking::whereDate('created_at', $today)->where('status', 'selesai')->count();
        $totalBerhasilKemarin = Booking::whereDate('created_at', $yesterday)->where('status', 'selesai')->count();
        $persentasePerubahan2 = $this->calculatePercentageChange($totalBookingBerhasil, $totalBerhasilKemarin);

        // Performa Bulanan
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

        // Layanan Terlaris Bulanan
        $popularService = Booking::join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                                 ->select('layanan.nama_layanan', DB::raw('count(booking.id_booking) as total'))
                                 ->whereMonth('booking.created_at', $currentMonth)
                                 ->whereYear('booking.created_at', $currentYear)
                                 ->where('status', 'selesai')
                                 ->groupBy('layanan.nama_layanan')
                                 ->orderBy('total', 'desc')
                                 ->first();

        $popularServiceName = $popularService ? $popularService->nama_layanan : 'Tidak ada data';

        // Data untuk Chart Booking Harian
        $jamBuka = Carbon::parse($profile->jam_buka)->hour; 
        $jamTutup = Carbon::parse($profile->jam_tutup)->hour; 

        // Inisialisasi array untuk label jam (sumbu X)
        $chartLabels = [];
        for ($i = $jamBuka; $i <= $jamTutup; $i++) {
            $chartLabels[] = $i . ":00"; // Misal: 8:00, 9:00, dst.
        }

        // Tetapkan daftar warna default
        $defaultColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(255, 205, 86, 0.7)',
            'rgba(201, 203, 207, 0.7)', // Tambahkan lebih banyak warna sesuai kebutuhan
        ];
        $adminPelayanan = isset($admin->no_pelayanan) && !empty($admin->no_pelayanan);

        if ($adminPelayanan) {
            // Ambil data booking berdasarkan jam booking dan nama layanan dari jam buka sampai jam tutup
            $bookings = DB::table('booking')
                ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan') // Pastikan join ini masih valid
                ->join('pelayanan', 'booking.no_pelayanan', '=', 'pelayanan.no_pelayanan')
                ->where('pelayanan.id_admin', '=', $admin->id_admin)
                ->select(
                    DB::raw('HOUR(booking.jam_booking) as jam'),
                    'layanan.nama_layanan',
                    'pelayanan.no_pelayanan',
                    DB::raw('count(booking.id_booking) as total_booking')
                )
                ->whereDate('booking.tanggal', $today)
                ->where('pelayanan.no_pelayanan', $admin->no_pelayanan)
                ->whereBetween(DB::raw('HOUR(booking.jam_booking)'), [$jamBuka, $jamTutup])
                ->groupBy(DB::raw('HOUR(booking.jam_booking)'), 'layanan.nama_layanan', 'pelayanan.no_pelayanan')
                ->get();
        } else {
            // Jika admin tidak terdaftar, bisa return kosong atau pesan tertentu
            $bookings = collect(); // Mengembalikan koleksi kosong
            // Atau bisa juga menyiapkan pesan untuk ditampilkan
            // $message = 'Anda tidak memiliki pelayanan terdaftar.';
        }

        // Initialize chart data
        $chartData = [];
        $layananIndex = 0;

        // Check if bookings data exists
        if ($bookings->isEmpty()) {
        // Handle case where there are no bookings
        $noDataMessage = 'No bookings available for this time range.';
        $defaultColors = ['#FF6384', '#36A2EB', '#FFCE56']; // Example colors
        $dataset = array_fill(0, ($jamTutup - $jamBuka + 1), 0); // Create an empty dataset
        $chartData[] = [
            'label' => 'No Data',
            'data' => $dataset,
            'backgroundColor' => '#CCCCCC', // Grey color for no data
            'borderColor' => '#CCCCCC',
            'borderWidth' => 1
        ];
        } else {
        // Prepare chart data if bookings exist
        foreach ($bookings->groupBy('nama_layanan') as $layanan => $groupedBookings) {
            $dataset = [];
            foreach (range($jamBuka, $jamTutup) as $jam) {
                $bookingPerJam = $groupedBookings->firstWhere('jam', $jam);
                $dataset[] = $bookingPerJam ? $bookingPerJam->total_booking : 0;
            }

            // Tetapkan warna berdasarkan urutan layanan
            $color = $defaultColors[$layananIndex % count($defaultColors)];

            $chartData[] = [
                'label' => $layanan,
                'data' => $dataset,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'borderWidth' => 1
            ];

            $layananIndex++; // Increment index untuk layanan berikutnya
        }
    }
        // Kirim data ke view
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
            'chartLabels', 'chartData', 
            'jamBuka', 'jamTutup',
            'popularServiceName',
            'adminPelayanan'
        ));
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous > 0) {
            return (($current - $previous) / $previous) * 100;
        } elseif ($previous === 0 && $current > 0) {
            return 100;
        }
        return 0; 
    }

    public function checkLoket()
{
    $today = date('Y-m-d'); // Tanggal hari ini

    $lokets = DB::table('pelayanan')
        ->leftJoin(DB::raw('(SELECT no_pelayanan, nomor_booking, jam_booking
                            FROM booking
                            WHERE status = "diproses"
                            AND DATE(tanggal) = "' . $today . '"
                            ORDER BY jam_booking DESC) as latest_booking'), function ($join) {
            $join->on('pelayanan.no_pelayanan', '=', 'latest_booking.no_pelayanan');
        })
        ->join('admin', 'pelayanan.id_admin', '=', 'admin.id_admin') // Join dengan admin
        ->select(
            'pelayanan.no_pelayanan',
            'admin.nama_admin',
            'latest_booking.nomor_booking',
            'latest_booking.jam_booking'
        )
        ->groupBy('pelayanan.no_pelayanan', 'admin.nama_admin', 'latest_booking.nomor_booking', 'latest_booking.jam_booking')
        ->get();

    return response()->json(['lokets' => $lokets]);
}

}
