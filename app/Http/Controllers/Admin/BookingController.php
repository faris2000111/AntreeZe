<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index()
    {
        // Mengambil semua data booking dari database
        $booking = Booking::all();
        
        // Mengirim data booking ke view
        return view('booking.booking', compact('booking'));
    }

    public function create()
    {
        // Menampilkan form untuk menambah booking
        return view('booking.create');
    }
    public function kelola()
    {

    }

    public function edit($id)
    {
        // Mencari data booking berdasarkan ID
        $booking = Booking::findOrFail($id);
        
        // Menampilkan form edit booking
        return view('booking.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'nomor_booking' => 'required',
            'no_pelayanan' => 'required',
            'id_users' => 'required',
            'alamat' => 'required',
            'id_layanan' => 'required',
            'jam_booking' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required',
        ]);

        // Mencari data booking yang akan diupdate
        $booking = Booking::findOrFail($id);
        
        // Mengupdate data booking
        $booking->update($request->all());

        // Redirect setelah update berhasil
        return redirect()->route('booking.index')->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Mencari data booking yang akan dihapus
        $booking = Booking::findOrFail($id);
        $booking->delete();

        // Redirect setelah data berhasil dihapus
        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }
}
