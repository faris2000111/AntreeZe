<?php

namespace App\Http\Controllers\Mobile;

use Exception;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Profile;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function __construct()
    {
        // Terapkan rate limiting pada seluruh method di controller
        $this->middleware('throttle:60,1')->only([
            'getBookingToday'

        ]);
    }

    protected function handleTooManyRequests($exception)
    {
        return ResponseFormatter::error([
            'message' => 'Too many requests. Please try again later.',
            'retry_after' => $exception->getHeaders()['Retry-After'] ?? 60
        ], 'Too Many Requests', Response::HTTP_TOO_MANY_REQUESTS);
    }


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

            // Generate array of times from opening to closing hours
            $times = $this->generateTimes($jam_buka, $jam_tutup);

            // Get total number of loket
            $totalLoket = Pelayanan::where('jenis', 'Loket')->count();

            // Calculate maximum capacity (2 records per loket)
            $maxCapacityPerTime = $totalLoket * 2;

            // Get booking counts for each time slot on the requested date
            $bookingCounts = Booking::where('tanggal', $request->tanggal)
                ->select('jam_booking')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('jam_booking')
                ->get()
                ->pluck('count', 'jam_booking')
                ->toArray();

            $non_available = [];
            $available = [];

            // Check each time slot
            foreach ($times as $time) {
                $currentBookings = isset($bookingCounts[$time]) ? $bookingCounts[$time] : 0;

                // If current bookings are less than max capacity, time slot is available
                if ($currentBookings < $maxCapacityPerTime) {
                    // $available[] = $time;
                    $available[] = [
                        'time' => $time,
                        'remaining_slots' => $maxCapacityPerTime - $currentBookings
                    ];
                } else {
                    $non_available[] = $time;
                }
            }

            return ResponseFormatter::success([
                'message' => 'success',
                'loket_info' => [
                    'total_loket' => $totalLoket,
                    'capacity_per_loket' => 2,
                    'total_capacity' => $maxCapacityPerTime
                ],
                'time_slots' => [
                    'available' => $available,
                    'non_available' => $non_available
                ]
            ], 'Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'The service list failed to retrieve',
                'error' => $error
            ], 'Failed', 500);
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
                ->pluck('no_pelayanan');

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
                'id_users' => ['required'],
                'id_layanan' => ['required'],
                'jam_booking' => ['required'],
                'tanggal' => ['required', 'date'],
                'created_at' => ['required', 'date']
            ]);


            function generateTime($start_time, $end_time, $interval = 60)
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
            $existingBooking = Booking::where('tanggal', $request->tanggal)
                ->where('jam_booking', $request->jam_booking)
                ->where('no_pelayanan', $request->no_pelayanan)
                ->where('id_layanan', $request->id_layanan) // Cek id_layanan juga
                ->first();

            if ($existingBooking) {
                // Jika sudah ada booking untuk tanggal, jam, dan loket yang sama, return error
                return ResponseFormatter::error([
                    'message' => 'Booking already exists for the selected time, date, and service point.',
                ], 'Booking Failed', 400);
            }

            // Ambil jam buka dan tutup dari profil
            $profile = Profile::find(1); // Asumsikan profil id 1
            $jam_buka = $profile->jam_buka;
            $jam_tutup = $profile->jam_tutup;

            // Hasilkan array waktu dari jam buka sampai jam tutup dengan interval 1 jam
            $times = generateTime($jam_buka, $jam_tutup);

            // Cocokkan jam_booking dengan daftar waktu layanan
            $jamBookingIndex = array_search($request->jam_booking, $times);

            if ($jamBookingIndex === false) {
                // Jika jam_booking tidak ditemukan dalam daftar waktu, return error
                return ResponseFormatter::error([
                    'message' => $times,
                ], 'Booking Failed', 400);
            }

            $countBooking = Booking::where('tanggal', $request->tanggal)
                ->where('jam_booking', $request->jam_booking)
                ->where('id_users', $request->id_users)
                ->first();

            if (!empty($countBooking)) {
                return ResponseFormatter::error([
                    'message' => 'User sudah memiliki data booking pada jam tersebut',
                ], 'User have record booking in that time', 402);
            }

            // Nomor antrian berdasarkan indeks waktu
            $nomorAntrian = str_pad($jamBookingIndex + 1, 2, '0', STR_PAD_LEFT);
            $hurufLayanan = chr(65 + ($request->id_layanan - 1));
            // $noPelayanan = chr(65 + ($request->no_pelayanan - 1));

            // Gabungkan huruf layanan dengan nomor antrian
            $nomorBooking = $hurufLayanan . $nomorAntrian;

            $existingBookingWithSameCode = Booking::where('tanggal', $request->tanggal)
                ->where('nomor_booking', 'LIKE', $nomorBooking . '%')
                ->count();


            $nomorBooking .= str_pad($existingBookingWithSameCode + 1, 1, '0', STR_PAD_LEFT);

            // Buat booking baru
            $booking = Booking::create([
                'nomor_booking' => $nomorBooking,
                'no_pelayanan' => 0,
                'id_users' => $request->id_users,
                'id_layanan' => $request->id_layanan,
                'jam_booking' => $request->jam_booking,
                'tanggal' => $request->tanggal,
                'status' => 'dipesan',
                'no_urut' => 0,
                'created_at' => $request->created_at
            ]);

            $firebase_json = env('FIREBASE_CREDENTIALS');
            // Firebase setup
            $factory = (new Factory())
                // Sesuaikan dengan tempat penyimpanan
                // ->withServiceAccount('/xampp/htdocs/antreezy/AntreeZe/config/antriqu_crendetials.json')
                ->withServiceAccount($firebase_json)
                ->withDatabaseUri('https://antriqu-apps-default-rtdb.asia-southeast1.firebasedatabase.app');
            $database = $factory->createDatabase();

            // Cek apakah pemesanan dilakukan pada hari yang sama
            $currentDate = Carbon::now()->toDateString();
            if ($request->tanggal === $currentDate) {
                // Jika ya, tambahkan data ke Firebase Realtime Database pada referensi 'UpdateChart'
                $chartData = [
                    'id_booking' => $booking->id_booking,
                    'nomor_booking' => $booking->nomor_booking,
                    'id_users' => $booking->id_users,
                    'status' => 'dipesan',
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];

                // Tambahkan data ke referensi 'UpdateChart'
                $database->getReference('UpdateChart/' . $booking->id_booking)
                    ->set($chartData);
            }

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
    public function getBookingToday(Request $request)
    {
        try {

            $key = 'bookingToday_' . ($request->ip() ?? 'unknown');

            if (RateLimiter::tooManyAttempts($key, 30)) { // 30 requests per minute
                $seconds = RateLimiter::availableIn($key);
                return ResponseFormatter::error([
                    'message' => 'Too many booking attempts. Please try again later.',
                    'retry_after' => $seconds
                ], 'Too Many Requests', Response::HTTP_TOO_MANY_REQUESTS);
            }

            RateLimiter::hit($key, 60); // Key expires in 60 seconds

            $userId = $request->id_users;
            $date = Carbon::now()->toDateString();
            $bookings = Booking::where('id_users', $userId)
                ->whereDate('tanggal', $date)
                ->orderBy('tanggal', 'asc')
                ->orderBy('jam_booking', 'asc')
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

            if ($error instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
                return $this->handleTooManyRequests($error);
            }

            return ResponseFormatter::error(
                ['message' => 'Failed to retrieve booking history', 'error' => $error],
                'Failed',
                500
            );
        }
    }

    public function getLatestBookingHistory(Request $request)
    {
        try {
            $userId = $request->id_users;

            // Melakukan query ke database untuk mendapatkan history booking berdasarkan id_users
            $bookings = Booking::with('layanan')
                ->where('id_users', $userId)
                ->orderBy('created_at', 'desc')
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



    public function getBookingHistory(Request $request)
    {
        try {
            $userId = $request->id_users;
            $status = $request->status;


            $bookings = Booking::with('layanan')
                ->where('id_users', $userId)
                ->where('status', $status)
                ->orderBy('tanggal', 'asc')
                ->orderBy('jam_booking', 'asc')
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

        $profile = Profile::find('1');
        // Pastikan profile ditemukan
        if (!$profile) {
            return ResponseFormatter::error(null, 'Profile not found', 404);
        }

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

        $date = Carbon::now()->toDateString();
        $testDate = '2024-10-09';
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
                // Pastikan `hour` dicast menjadi integer untuk mencocokkan hasil EXTRACT(HOUR)
                $hourInt = (int) explode(':', $hour)[0];

                // Cari booking untuk layanan tertentu pada jam tertentu
                $bookingsForHour = $bookings->where('hour', $hourInt)->where('id_layanan', $serviceId)->first();

                $barGroup['bookings'][] = [
                    'service_id' => $serviceId,
                    'booking_count' => $bookingsForHour ? (int) $bookingsForHour->booking_count : 0
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

    public function getUserBookChartByDate(Request $request)
    {

        $profile = Profile::find('1');
        // Pastikan profile ditemukan
        if (!$profile) {
            return ResponseFormatter::error(null, 'Profile not found', 404);
        }

        $startHour = $profile->jam_buka;
        $endHour = $profile->jam_tutup;

        // Fungsi untuk generate waktu dengan interval 1 jam (60 menit)
        function generateTimess($start_time, $end_time, $interval = 60)
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
        $hours = generateTimess($startHour, $endHour);
        $layanan = Layanan::select('id_layanan', 'nama_layanan')->get();


        $bookings = Booking::with('layanan')
            ->selectRaw('id_layanan, EXTRACT(HOUR FROM jam_booking) as hour, COUNT(*) as booking_count')
            ->groupBy('id_layanan', 'hour')
            ->whereDate('tanggal', $request->date)
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
                // Pastikan `hour` dicast menjadi integer untuk mencocokkan hasil EXTRACT(HOUR)
                $hourInt = (int) explode(':', $hour)[0];

                // Cari booking untuk layanan tertentu pada jam tertentu
                $bookingsForHour = $bookings->where('hour', $hourInt)->where('id_layanan', $serviceId)->first();

                $barGroup['bookings'][] = [
                    'service_id' => $serviceId,
                    'booking_count' => $bookingsForHour ? (int) $bookingsForHour->booking_count : 0
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

    public function updateBookingStatus(Request $request)
    {
        try {
            $request->validate([
                'id_booking' => 'required',
            ]);

            // Mencari booking berdasarkan id_booking
            $booking = Booking::find($request->id_booking);

            // Cek jika booking ditemukan
            if ($booking) {
                // Update status
                $booking->status = 'selesai';
                // Simpan perubahan
                $booking->save();

                // Kirim respons sukses
                return ResponseFormatter::success([
                    'data' => $booking,
                    'message' => 'Status booking berhasil diupdate.'
                ], 'Update berhasil');
            } else {
                // Jika booking tidak ditemukan
                return ResponseFormatter::error([
                    'message' => 'Booking tidak ditemukan'
                ], 'Booking tidak ditemukan', 404);
            }
        } catch (Exception $error) {
            // Handle error
            return ResponseFormatter::error([
                'message' => 'Terjadi kesalahan saat update status',
                'error' => $error
            ], 'Update gagal', 500);
        }
    }
}
