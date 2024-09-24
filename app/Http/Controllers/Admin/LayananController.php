<?php

namespace App\Http\Controllers\Admin;

use App\Models\Layanan; // Asumsikan kamu memiliki model Layanan
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    // Menampilkan semua layanan
    public function index()
    {
        $layanan = Layanan::orderBy('created_at', 'asc')->get();
        return view('layanan.layanan', compact('layanan'));
    }

    // Menampilkan form untuk menambah layanan baru
    public function create()
    {
        return view('layanan.create');
    }

    // Menyimpan data layanan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'waktu' => 'required',
        ], [
            'nama_layanan.required' => 'Nama layanan tidak boleh kosong!',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong!',
            'gambar.required' => 'Gambar tidak boleh kosong!',
            'gambar.image' => 'File harus berupa gambar!',
            'waktu.required' => 'Waktu tidak boleh kosong!',
        ]);

        if ($request->file('gambar')) {
        $imageName = $request->file('gambar')->getClientOriginalName();
        $tujuan_upload = 'storage/layanan';
        $request->gambar->move($tujuan_upload, $imageName);

        // Membuat layanan baru
        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
            'gambar' =>$imageName, null, // Menyimpan path gambar
            'waktu' => $request->waktu,
        ]);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
        }
    }

    // Menampilkan form untuk mengedit layanan
    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('layanan.edit', compact('layanan'));
    }

    // Memperbarui data layanan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Gambar opsional
            'waktu' => 'required',
        ]);

        $layanan = Layanan::findOrFail($id);

        // Update gambar jika ada file baru
        if ($request->File('gambar')) {

            $imageName = $request->file('gambar')->getClientOriginalName();
            $tujuan_upload = 'storage/layanan';
            $request->gambar->move($tujuan_upload, $imageName);
            
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'deskripsi' => $request->deskripsi,
                'gambar' => $imageName,
                'waktu' => $request->waktu,
            ]);
        }else{
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'deskripsi' => $request->deskripsi,
                'waktu' => $request->waktu,
            ]);
        }

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    // Menghapus layanan
    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);

        // Hapus gambar jika ada
        if ($layanan->gambar && Storage::exists('public/' . $layanan->gambar)) {
            Storage::delete('public/' . $layanan->gambar);
        }

        // Hapus layanan
        $layanan->delete();

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
