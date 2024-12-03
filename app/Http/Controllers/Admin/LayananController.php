<?php

namespace App\Http\Controllers\Admin;

use App\Models\Layanan; 
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::orderBy('created_at', 'asc')->get();
        return view('layanan.layanan', compact('layanan'));
    }
    public function create()
    {
        return view('layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
        ], [
            'nama_layanan.required' => 'Nama layanan tidak boleh kosong!',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong!',
            'gambar.required' => 'Gambar tidak boleh kosong!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.max' => 'Maksimal gambar 1MB!',
        ]);

        if ($request->file('gambar')) {
        $imageName = $request->file('gambar')->getClientOriginalName();
        $tujuan_upload = 'storage/layanan';
        $request->gambar->move($tujuan_upload, $imageName);

        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
            'gambar' =>$imageName, null, 
        ]);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
        }
    }
    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('layanan.edit', compact('layanan'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024', 
        ], [
            'nama_layanan.required' => 'Nama layanan tidak boleh kosong!',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong!',
            'gambar.required' => 'Gambar tidak boleh kosong!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.max' => 'Maksimal gambar 1MB!',
        ]);

        $layanan = Layanan::findOrFail($id);
        if ($request->File('gambar')) {
            $imageName = $request->file('gambar')->getClientOriginalName();
            $tujuan_upload = 'storage/layanan';
            $request->gambar->move($tujuan_upload, $imageName);
            
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'deskripsi' => $request->deskripsi,
                'gambar' => $imageName,
            ]);
        }else{
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'deskripsi' => $request->deskripsi,
            ]);
        }
        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        if ($layanan->gambar && Storage::exists('public/' . $layanan->gambar)) {
            Storage::delete('public/' . $layanan->gambar);
        }

        $layanan->delete();

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
