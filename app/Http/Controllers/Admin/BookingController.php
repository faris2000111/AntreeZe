<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index()
    {
        $today = Carbon::today(); 
        $adminId = auth()->user()->id_admin;

        $noPelayananAdmin = DB::table('pelayanan')
            ->where('id_admin', $adminId)
            ->value('no_pelayanan');

        $booking = DB::table('booking')
            ->join('pelayanan', 'booking.no_pelayanan', '=', 'pelayanan.no_pelayanan')
            ->join('layanan', 'layanan.id_layanan', '=', 'booking.id_layanan')
            ->join('users', 'users.id_users', '=', 'booking.id_users')
            ->select('booking.*', 'pelayanan.*', 'layanan.*', 'users.nama_pembeli', 'users.phone_number')
            ->where('booking.no_pelayanan', $noPelayananAdmin)
            ->whereDate('booking.tanggal', $today)
            ->where('booking.status', 'selesai')
            ->orderBy('booking.jam_booking', 'asc')
            ->orderBy('booking.updated_at', 'asc')
            ->get();

        return view('booking.booking', compact('booking'));
    }
    public function edit(Request $request)
    {
        $nomorBooking = $request->query('nomor_booking');
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $adminId = auth()->user()->id_admin;

        $noPelayananAdmin = DB::table('pelayanan')
            ->where('id_admin', $adminId)
            ->value('no_pelayanan');

        if (is_null($noPelayananAdmin)) {
            $booking = [];
            return redirect()->route('booking.index')->with('error', 'Admin belum terdaftar di halaman pelayanan.');
        }
    
        if ($nomorBooking) {
            $booking = DB::table('booking')
                ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                ->join('users', 'booking.id_users', '=', 'users.id_users')
                ->select('booking.*', 'layanan.nama_layanan', 'users.phone_number', 'users.nama_pembeli')
                ->whereIn('booking.status', ['dipesan', 'diproses'])
                ->where('booking.nomor_booking', $nomorBooking)
                ->where('booking.tanggal', $tanggalHariIni)
                ->first();
        } else {
            $query = DB::table('booking')
                ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                ->join('users', 'booking.id_users', '=', 'users.id_users')
                ->select('booking.*', 'layanan.nama_layanan', 'users.phone_number', 'users.nama_pembeli')
                ->whereIn('booking.status', ['dipesan', 'diproses'])
                ->where('booking.tanggal', $tanggalHariIni)
                ->where(function ($q) use ($noPelayananAdmin) {
                    $q->where('booking.no_pelayanan', 0)
                        ->orWhere('booking.no_pelayanan', $noPelayananAdmin);
                })
                ->orderByRaw("CASE WHEN booking.status = 'diproses' THEN 1 WHEN booking.status = 'dipesan' THEN 2 END")
                ->orderBy('booking.updated_at', 'asc')
                ->orderBy('booking.jam_booking', 'asc');
    
            $booking = $query->first();
        }
    
        if (!$booking) {
            return redirect()->route('booking.index')->with('error', 'Booking tidak ditemukan untuk nomor booking yang dicari.');
        }
    
        return view('booking.edit', compact('booking')); 
    }
    public function update(Request $request)
    {
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $validated = $request->validate([
            'nomor_booking' => 'required|string',
            'action' => 'required|string', 
            'catatan' => 'nullable|string',
        ]);
    
        $booking = DB::table('booking')
            ->join('users', 'booking.id_users', '=', 'users.id_users')
            ->join('layanan','booking.id_layanan','=','layanan.id_layanan')
            ->where('booking.nomor_booking', $validated['nomor_booking'])
            ->where('booking.tanggal', $tanggalHariIni)
            ->first();
            
        if (!$booking) {
            return redirect()->route('booking.index')->with('error', 'Booking tidak ditemukan untuk nomor booking yang dicari.');
        }
    
        $adminId = auth()->user()->id_admin;
        $noPelayananAdmin = DB::table('pelayanan')
            ->where('id_admin', $adminId)
            ->value('no_pelayanan');
    
        $status = $booking->status;
    
        if ($validated['action'] == 'selanjutnya') {
            if ($status == 'dipesan') {
                $status = 'diproses'; 
            } elseif ($status == 'diproses') {
                $status = 'selesai'; 
            }
        } elseif ($validated['action'] == 'selesai') {
            $status = 'selesai'; 
        } elseif ($validated['action'] == 'dibatalkan') {
            $status = 'dibatalkan';
        } elseif ($validated['action'] == 'lewati') {
            DB::table('booking')->where('nomor_booking', $validated['nomor_booking'])->update([
                'status_dilewati' => 1, 
                'catatan' => $validated['catatan'] ?? 'Anda telah dilewati karena datang terlambat, mohon pastikan datang 15 menit lebih awal, terimakasih!',
                'updated_at' => Carbon::now()
            ]);
    
            $nextBooking = DB::table('booking')
                ->where('tanggal', $tanggalHariIni)
                ->where('status', 'dipesan')
                ->orderBy('jam_booking', 'asc')
                ->orderBy('updated_at', 'asc')
                ->first();
    
            if ($nextBooking) {
                $nextQueueNumber = $nextBooking->nomor_booking;
                $nextBuyerName = DB::table('users')->where('id_users', $nextBooking->id_users)->value('nama_pembeli');
    
                return redirect()->route('booking.edit')->with('success', 'Booking berhasil diperbarui dan dilewati.')
                    ->with('nextQueueNumber', $nextQueueNumber)
                    ->with('nextBuyerName', $nextBuyerName)
                    ->with('action', 'lewati');
            } else {
                return redirect()->route('booking.edit')->with('success', 'Booking berhasil diperbarui. Tidak ada booking selanjutnya.');
            }
        }

        if ($validated['action'] == 'selesai') {
            $catatan = null; 
            $statusDilewati = 0;
        } elseif ($validated['action'] == 'dibatalkan') {
            $catatan = "Booking Anda telah dibatalkan karena alasan tertentu."; 
            $statusDilewati = $booking->status_dilewati;
        } else {
            $catatan = $validated['catatan'] ?? $booking->catatan; 
            $statusDilewati = $booking->status_dilewati;
        }
    
        DB::table('booking')->where('nomor_booking', $validated['nomor_booking'])->update([
            'status' => $status,
            'catatan' => $catatan,
            'status_dilewati' => $statusDilewati,
            'no_pelayanan' => $noPelayananAdmin, 
            'updated_at' => Carbon::now(),
        ]);
    
        $nextBooking = DB::table('booking')
            ->join('users', 'booking.id_users', '=', 'users.id_users') 
            ->where('booking.tanggal', $tanggalHariIni)
            ->where('booking.status', 'dipesan')
            ->select('booking.*', 'users.nama_pembeli') 
            ->orderBy('booking.updated_at', 'asc')
            ->orderBy('booking.jam_booking', 'asc')
            ->first();
    
        if ($nextBooking) {
            $nextQueueNumber = $nextBooking->nomor_booking;
            $nextBuyerName = DB::table('users')->where('id_users', $nextBooking->id_users)->value('nama_pembeli');
    
            return redirect()->route('booking.edit')->with('success', 'Booking berhasil diperbarui.')
                ->with('nextQueueNumber', $nextQueueNumber)
                ->with('nextBuyerName', $nextBuyerName)
                ->with('action', $validated['action']); 
        } else {
            return redirect()->route('booking.edit')->with('success', 'Booking berhasil diperbarui. Tidak ada booking selanjutnya.')
                ->with('action', $validated['action']);
        }
    }       
    public function search(Request $request)
    {
        $nomorBooking = $request->query('nomor_booking');
        $tanggalHariIni = now()->format('Y-m-d'); 
        $adminId = auth()->user()->id_admin;

        $noPelayananAdmin = DB::table('pelayanan')
            ->where('id_admin', $adminId)
            ->value('no_pelayanan');

        if (is_null($noPelayananAdmin)) {
            return response()->json(['error' => 'Anda belum terdaftar pada halaman pelayanan.'], 400);
        }

        if (empty($nomorBooking)) {
            $query = DB::table('booking')
                ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                ->join('users', 'booking.id_users', '=', 'users.id_users')
                ->select('booking.*', 'layanan.nama_layanan', 'users.phone_number', 'users.nama_pembeli')
                ->whereIn('booking.status', ['dipesan', 'diproses'])
                ->where('booking.tanggal', $tanggalHariIni)
                ->where(function($q) use ($noPelayananAdmin) {
                    $q->where('booking.no_pelayanan', 0)
                    ->orWhere('booking.no_pelayanan', $noPelayananAdmin);
                })
                ->orderByRaw("CASE WHEN booking.status = 'diproses' THEN 1 WHEN booking.status = 'dipesan' THEN 2 END")
                ->orderBy('booking.updated_at', 'asc')
                ->orderBy('booking.jam_booking', 'asc');

            $booking = $query->first();
        } else {
            $booking = DB::table('booking')
                ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                ->join('users', 'booking.id_users', '=', 'users.id_users')
                ->select('booking.*', 'layanan.nama_layanan', 'users.phone_number', 'users.nama_pembeli')
                ->whereIn('booking.status', ['dipesan', 'diproses'])
                ->where('booking.nomor_booking', $nomorBooking)
                ->where('booking.tanggal', $tanggalHariIni)
                ->first();
        }

        if (!$booking) {
            return response()->json(['error' => 'Data booking tidak ditemukan.'], 404);
        }

        return response()->json(['booking' => $booking]);
    }
    public function getNewBookings()
    {
        $today = Carbon::today();
        $newBookings = DB::table('booking')
            ->join('users', 'booking.id_users', '=', 'users.id_users')
            ->whereDate('booking.tanggal', $today)
            ->where('booking.status', 'dipesan')
            ->select('booking.nomor_booking', 'users.nama_pembeli', 'booking.jam_booking', 'booking.updated_at')
            ->orderBy('booking.updated_at', 'desc')
            ->limit(5) 
            ->get();

        return response()->json(['new_bookings' => $newBookings]);
    }
    public function fetchBookingData(Request $request)
    {
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $adminId = auth()->user()->id_admin;

        $noPelayananAdmin = DB::table('pelayanan')
            ->where('id_admin', $adminId)
            ->value('no_pelayanan');

        if (is_null($noPelayananAdmin)) {
            return response()->json(['error' => 'Anda belum terdaftar pada halaman pelayanan.']);
        }

        $nomorBooking = $request->query('nomor_booking');
        $booking = null;

        if ($nomorBooking) {
            $booking = DB::table('booking')
                ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                ->join('users', 'booking.id_users', '=', 'users.id_users')
                ->select('booking.*', 'layanan.nama_layanan', 'users.phone_number', 'users.nama_pembeli')
                ->whereIn('booking.status', ['dipesan', 'diproses'])
                ->where('booking.nomor_booking', $nomorBooking)
                ->where('booking.tanggal', $tanggalHariIni)
                ->first();
        } else {
            $query = DB::table('booking')
                ->join('layanan', 'booking.id_layanan', '=', 'layanan.id_layanan')
                ->join('users', 'booking.id_users', '=', 'users.id_users')
                ->select('booking.*', 'layanan.nama_layanan', 'users.phone_number', 'users.nama_pembeli')
                ->whereIn('booking.status', ['dipesan', 'diproses'])
                ->where('booking.tanggal', $tanggalHariIni)
                ->where(function ($q) use ($noPelayananAdmin) {
                    $q->where('booking.no_pelayanan', 0)
                        ->orWhere('booking.no_pelayanan', $noPelayananAdmin);
                })
                ->orderByRaw("CASE WHEN booking.status = 'diproses' THEN 1 WHEN booking.status = 'dipesan' THEN 2 END")
                ->orderBy('booking.updated_at', 'asc')
                ->orderBy('booking.jam_booking', 'asc');

            $booking = $query->first();
        }

        if (!$booking) {
            return response()->json(['error' => 'Tidak ada booking dalam status "dipesan" atau "diproses" untuk tanggal hari ini.']);
        }

        return response()->json(['booking' => $booking]);
    }
    public function destroy($id)
    {
        $karyawan = Booking::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }
}
