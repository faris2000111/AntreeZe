<?php

namespace App\Http\Controllers\Mobile;

use Exception;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Profile;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    //
    function generateTimes($start_time, $end_time, $interval = 60)
    {
        $times = [];

        // Konversi jam buka dan jam tutup ke timestamp
        $start = strtotime($start_time);
        $end = strtotime($end_time);

        // Loop untuk menghasilkan jam berdasarkan interval
        for ($current_time = $start; $current_time <= $end; $current_time += $interval * 60) {
            $times[] = date('H:i:s', $current_time); // Format waktu jam:menit
        }

        return $times;
    }
    public function getdateavailabe(Request $request)
    {
        try {
            $profile = Profile::find($request->id);
            $jam_buka = $profile->jam_buka;
            $jam_tutup = $profile->jam_tutup;

            // Hasilkan array waktu dari jam buka sampai jam tutup dengan interval 1 jam
            $times = $this->generateTimes($jam_buka, $jam_tutup);
            // Dapatkan semua no_pelayanan yang ada
            $pelayanans = Pelayanan::distinct()->pluck('no_pelayanan');

            $bookings = [];
            foreach ($pelayanans as $no_pelayanan) {
                $bookings[$no_pelayanan] = Booking::where('tanggal', $request->tanggal)
                    ->where('id_layanan', $request->id_layanan)
                    ->where('id_pelayanan', $no_pelayanan)
                    ->pluck('jam_booking');
            }
            $non_available = [];
            $available = [];
            // Cek setiap waktu terhadap semua no_pelayanan
            foreach ($times as $time) {
                $isNonAvailable = true;
                foreach ($pelayanans as $no_pelayanan) {
                    if (!$bookings[$no_pelayanan]->contains($time)) {
                        $isNonAvailable = false;
                        break;
                    }
                }
                if ($isNonAvailable) {
                    $non_available[] = $time;
                } else {
                    $available[] = $time;
                }
            }

            return ResponseFormatter::success([
                'message' => 'success',
                'time_slots' => [
                    'available' => $available,
                    'non_available' => $non_available
                ]
            ], 'Success');
        } catch (Exception $error) {
            return ResponseFormatter::error(['message' => 'The service list failed to retrieve', 'error' => $error], 'Failed', 500);
        }
    }


    public function checkAvailableLoket(Request $request)
    {
        if (empty($request->jam_booking)) {
            return ResponseFormatter::success([
                'message' => 'Available loket numbers fetched successfully',
                'available_loket' => [] // Mengembalikan array kosong jika 'jam_booking' tidak diisi
            ], 'Success');
        }
        try {
            // Ambil semua nomor pelayanan yang unik
            $allPelayanans = Pelayanan::distinct()->pluck('no_pelayanan');

            // Query untuk mencari booking yang ada pada tanggal, id layanan, dan jam booking tertentu
            $bookedPelayanans = Booking::where('tanggal', $request->tanggal)
                ->where('id_layanan', $request->id_layanan)
                ->where('jam_booking', $request->jam_booking)
                ->pluck('id_pelayanan');

            // Mencari nomor pelayanan yang belum dipakai
            $availablePelayanans = $allPelayanans->diff($bookedPelayanans)->values();

            return ResponseFormatter::success([
                'message' => 'Available loket numbers fetched successfully',
                'available_loket' => $availablePelayanans
            ], 'Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Failed to fetch available loket numbers',
                'error' => $error
            ], 'Failed', 500);
        }
    }

    public function insertbooking(Request $request)
    {
        try {
            $request->validate([
                'id_pelayanan' => ['required', 'string'],
                'id_users' => ['required'],
                'alamat' => ['required'],
                'id_layanan' => ['required'],
                'jam_booking' => ['required'],
                'tanggal' => ['required', 'date'],
            ]);

            // Ambil nomor booking terakhir berdasarkan tanggal dan id_layanan
            $lastBooking = Booking::where('tanggal', $request->tanggal)
                ->where('id_layanan', $request->id_layanan)
                ->orderBy('nomor_booking', 'desc')
                ->first();

            if ($lastBooking) {
                // Jika ada data, increment nomor booking terakhir
                $lastNumber = intval(substr($lastBooking->nomor_booking, -2)); // Ambil 2 digit terakhir
                $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT); // Increment dan format jadi 2 digit
            } else {
                // Jika tidak ada data, mulai dari 01
                $newNumber = '01';
            }

            // Generate nomor_booking baru
            $nomorBooking = $newNumber;

            // Buat booking baru
            $booking = Booking::create([
                'nomor_booking' => $nomorBooking,
                'id_pelayanan' => $request->id_pelayanan,
                'id_users' => $request->id_users,
                'alamat' => $request->alamat,
                'id_layanan' => $request->id_layanan,
                'jam_booking' => $request->jam_booking,
                'tanggal' => $request->tanggal,
                'status' => 'dipesan',
            ]);

            return ResponseFormatter::success([
                'data' => $booking,
            ], 'Make booking request done');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Ada yang error',
                'error' => $error,
            ], 'Booking Failed', 500);
        }
    }



    public function getBookingHistory(Request $request)
    {
        try {
            $userId = $request->id_users;
            $status = $request->status;

            // Melakukan query ke database untuk mendapatkan history booking berdasarkan id_users dan status
            $bookings = Booking::with('layanan')  // Menambahkan eager loading untuk layanan
                ->where('id_users', $userId)
                ->where('status', $status)
                ->orderBy('tanggal', 'desc')
                ->orderBy('jam_booking', 'desc')
                ->get();

            if ($bookings->isEmpty()) {
                return ResponseFormatter::error(
                    ['message' => 'No bookings found'],
                    'Not Found',
                    404
                );
            }

            return ResponseFormatter::success(
                $bookings,
                'Booking history retrieved successfully'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                ['message' => 'Failed to retrieve booking history', 'error' => $error],
                'Failed',
                500
            );
        }
    }

    public function getTicketUsers(Request $request)
    {
        try {
            // Ambil id_users dari request
            $id_users = $request->input('id_users');

            // Query untuk mendapatkan booking berdasarkan id_users
            $date = Carbon::now()->toDateString();
            $bookings = Booking::with('layanan')->where('id_users', $id_users)->whereDate('tanggal', $date)->get();

            // Cek apakah booking ditemukan
            if ($bookings->isEmpty()) {
                return ResponseFormatter::error(null, 'No bookings found for this user.', 404);
            }

            return ResponseFormatter::success($bookings, 'Bookings retrieved successfully.');
        } catch (Exception $error) {
            // Handle error
            return ResponseFormatter::error(null, 'Something went wrong: ' . $error->getMessage(), 500);
        }
    }

    public function getUserBookChart()
    {
        // Ambil profil berdasarkan ID dari request
        $profile = Profile::find('1');

        // Pastikan profile ditemukan
        if (!$profile) {
            return ResponseFormatter::error(null, 'Profile not found', 404);
        }

        // Ambil jam buka dan jam tutup dari database
        $startHour = $profile->jam_buka;
        $endHour = $profile->jam_tutup;

        // Fungsi untuk generate waktu dengan interval 1 jam (60 menit)
        function generateTimes($start_time, $end_time, $interval = 60)
        {
            $times = [];

            // Konversi jam buka dan jam tutup ke timestamp
            $start = strtotime($start_time);
            $end = strtotime($end_time);

            // Loop untuk menghasilkan jam berdasarkan interval
            for ($current_time = $start; $current_time <= $end; $current_time += $interval * 60) {
                $times[] = date('H:i', $current_time); // Format waktu jam:menit
            }

            return $times;
        }

        // Panggil fungsi untuk generate jam dari jam_buka sampai jam_tutup
        $hours = generateTimes($startHour, $endHour);
        $layanan = Layanan::select('id_layanan', 'nama_layanan')->get();

        // Ambil booking data sesuai dengan jam layanan
        // Query untuk mendapatkan booking berdasarkan id_users
        $date = Carbon::now()->toDateString();
        $bookings = Booking::with('layanan')
            ->selectRaw('id_layanan, EXTRACT(HOUR FROM jam_booking) as hour, COUNT(*) as booking_count')
            ->groupBy('id_layanan', 'hour')
            ->whereDate('tanggal', $date)
            ->whereBetween('jam_booking', [$startHour, $endHour])
            ->get();

        // Persiapkan response untuk chart
        $response = [];
        $services = $bookings->groupBy('id_layanan')->keys();

        foreach ($hours as $index => $hour) {
            $barGroup = [
                'x' => $index,
                'time' => $hour, // Include time in the response
                'bookings' => []
            ];

            foreach ($services as $serviceId) {
                $bookingsForHour = $bookings->where('hour', $index + (int)explode(':', $startHour)[0])->where('id_layanan', $serviceId)->first();
                $barGroup['bookings'][] = [
                    'service_id' => $serviceId,
                    'booking_count' => $bookingsForHour ? $bookingsForHour->booking_count : 0
                ];
            }

            $response[] = $barGroup;
        }

        // Return response menggunakan ResponseFormatter
        return ResponseFormatter::success([
            'hours' => $hours,
            'layanan' => $layanan,
            'data' => $response
        ], 'Data successfully retrieved');
    }
}
