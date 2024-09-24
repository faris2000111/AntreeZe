<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PelayananController extends Controller
{
    public function index()
    {
        $pelayanan = DB::table('pelayanan')
            ->join('admin', 'pelayanan.id_admin', '=', 'admin.id_admin') // Join berdasarkan id_admin
            ->select('pelayanan.*', 'admin.*') // Pilih kolom nama_admin dari tabel admin
            ->get();


        return view('pelayanan.pelayanan', compact('pelayanan'));
    }

    public function create()
    {
        // Mengambil semua data admin untuk ditampilkan dalam dropdown
        $admins = Admin::all();
        // Ambil nomor pelayanan terakhir
        $lastPelayanan = Pelayanan::orderBy('no_pelayanan', 'desc')->first();

        // Jika ada, tambahkan satu dari nomor terakhir, jika tidak, mulai dari 1
        $newNoPelayanan = $lastPelayanan ? $lastPelayanan->no_pelayanan + 1 : 1;

        return view('pelayanan.create', compact('admins', 'newNoPelayanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_admin' => 'required|exists:admin,id_admin',
            'jenis' => 'required',
        ], [
            'id_admin.required' => 'Nama tidak boleh kosong!',
            'jenis.required' => 'Jenis tidak boleh kosong!',
        ]);

        $lastPelayanan = Pelayanan::orderBy('no_pelayanan', 'desc')->first();
        $newNoPelayanan = $lastPelayanan ? $lastPelayanan->no_pelayanan + 1 : 1;



        Pelayanan::create([
            'id_admin' => $request->id_admin ?? auth()->user()->id_admin, // Jika null, gunakan ID admin yang login
            'jenis' => $request->jenis,
            'no_pelayanan' => $newNoPelayanan,
        ]);

        // Redirect setelah data berhasil disimpan
        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Mencari data pelayanan berdasarkan id
        $pelayanan = Pelayanan::findOrFail($id);
        $admins = Admin::all(); // Mengambil semua admin untuk dropdown

        return view('pelayanan.edit', compact('pelayanan', 'admins'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data input untuk update pelayanan
        $request->validate([
            'id_admin' => 'required',
            'jenis' => 'required',
            'no_pelayanan' => 'required',
        ]);

        // Mencari data pelayanan yang akan diupdate
        $pelayanan = Pelayanan::findOrFail($id);

        // Update data pelayanan
        $pelayanan->id_admin = $request->id_admin;
        $pelayanan->jenis = $request->jenis;
        $pelayanan->no_pelayanan = $request->no_pelayanan;

        $pelayanan->save();

        // Redirect setelah update berhasil
        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Mencari data pelayanan yang akan dihapus
        $pelayanan = Pelayanan::findOrFail($id);
        $pelayanan->delete();

        // Redirect setelah data berhasil dihapus
        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil dihapus.');
    }
}
