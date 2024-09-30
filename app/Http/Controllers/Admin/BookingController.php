<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index()
    {
        $adminId = auth()->user()->id_admin; // Ambil id_admin dari user yang sedang login

        // Mengambil id_pelayanan untuk admin yang login
        $pelayananIds = DB::table('pelayanan')
            ->where('id_admin', $adminId) // Filter berdasarkan id_admin yang login
            ->pluck('id_pelayanan'); // Ambil hanya id_pelayanan sebagai array

        // Mengambil data booking berdasarkan id_pelayanan dan status "dipesan"
        $booking = DB::table('booking')
            ->join('pelayanan', 'booking.no_pelayanan', '=', 'pelayanan.no_pelayanan') // Join dengan tabel pelayanan
            ->join('layanan', 'layanan.id_layanan', '=', 'booking.id_layanan') // Join dengan tabel layanan
            ->join('users', 'users.id_users', '=', 'booking.id_users') // Join dengan tabel users
            ->select(
                'booking.*', 
                'pelayanan.*', 
                'layanan.*', 
                'users.nama_pembeli', 
                'users.phone_number'
            ) // Pilih kolom yang diinginkan
            ->whereIn('pelayanan.id_pelayanan', $pelayananIds) // Filter berdasarkan id_pelayanan
            ->where('booking.status', 'dipesan') // Hanya tampilkan booking dengan status "dipesan"
            ->get();
        
        // Mengirim data booking ke view
        return view('booking.booking', compact('booking'));
    }

    public function create()
    {

    }

    public function edit()
    {
        // Ambil tanggal hari ini
        $tanggalHariIni = Carbon::now()->format('Y-m-d');

        // Ambil id_admin yang sedang login
        $adminId = auth()->user()->id_admin;

        // Ambil no_pelayanan yang terkait dengan admin yang login
        $noPelayanan = DB::table('pelayanan')
            ->where('id_admin', $adminId)
            ->pluck('no_pelayanan'); // Mengambil semua no_pelayanan yang terkait dengan admin

        // Mencari data booking berdasarkan tanggal hari ini dan no_pelayanan serta join ke layanan dan users
        $booking = DB::table('booking')
            ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan') // Join dengan tabel layanan
            ->join('pelayanan', 'booking.no_pelayanan', '=', 'pelayanan.no_pelayanan') // Join dengan tabel pelayanan
            ->join('users', 'booking.id_users', '=', 'users.id_users') // Join dengan tabel users
            ->select(
                'booking.*', 
                'layanan.*', 
                'pelayanan.*', 
                'users.phone_number',
                'users.nama_pembeli' // Aliaskan nama pembeli
            )
            ->where('booking.tanggal', $tanggalHariIni)
            ->where('status', 'dipesan') // Filter berdasarkan tanggal hari ini
            ->whereIn('booking.no_pelayanan', $noPelayanan) // Filter dengan no_pelayanan admin yang login
            ->first(); // Ambil satu data booking yang cocok

        // Cek apakah booking ditemukan
        if ($booking) {
            // Kirim data booking ke view edit
            return view('booking.edit', compact('booking'));
        } else {
            // Jika booking tidak ditemukan, redirect dengan pesan error
            return redirect()->route('booking.index')->with('error', 'Booking tidak ditemukan untuk tanggal hari ini.');
        }
    }
    public function update(Request $request)
{
    // Ambil tanggal hari ini
    $tanggalHariIni = Carbon::now()->format('Y-m-d');

    // Ambil id_admin yang sedang login
    $adminId = auth()->user()->id_admin;

    // Ambil no_pelayanan yang terkait dengan admin yang login
    $noPelayanan = DB::table('pelayanan')
        ->where('id_admin', $adminId)
        ->pluck('no_pelayanan'); // Mengambil semua no_pelayanan yang terkait dengan admin

    // Ambil booking dengan status "dipesan" berdasarkan tanggal hari ini dan no_pelayanan yang sesuai
    $booking = DB::table('booking')
        ->where('tanggal', $tanggalHariIni)
        ->whereIn('no_pelayanan', $noPelayanan)
        ->where('status', 'dipesan') // Filter hanya booking dengan status "dipesan"
        ->first();

    if ($booking) {
        // Tentukan status berdasarkan tombol yang diklik
        switch ($request->input('action')) {
            case 'selanjutnya':
                $status = 'diproses';
                break;
            case 'dibatalkan':
                $status = 'dibatalkan';
                break;
            case 'lewati':
                $status = 'dipesan';
                // Memperbarui timestamp booking untuk me-requeue ke akhir antrian
                DB::table('booking')->where('id_booking', $booking->id_booking)->update([
                    'updated_at' => Carbon::now() // Atau gunakan field khusus untuk mengatur urutan antrian
                ]);
                return redirect()->route('booking.edit')->with('success', 'Booking telah dilewati dan dimasukkan ke akhir antrian.');
            default:
                $status = $booking->status; // Default ke status saat ini jika tidak ada aksi yang cocok
        }

        // Update status dan catatan jika bukan kasus lewati
        if ($request->input('action') !== 'lewati') {
            DB::table('booking')->where('id_booking', $booking->id_booking)->update([
                'status' => $status,
                'catatan' => $request->input('catatan') ?? $booking->catatan, // Jika catatan tidak dikirim, gunakan catatan lama
            ]);
        }

        // Redirect ke halaman booking.index jika berhasil
        return redirect()->route('booking.edit')->with('success', 'Booking berhasil dilayani!');
    } else {
        return redirect()->route('booking.edit')->with('error', 'Booking tidak ditemukan atau status tidak "dipesan".');
    }
}

}
