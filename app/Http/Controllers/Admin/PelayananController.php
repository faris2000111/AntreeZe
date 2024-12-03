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
            ->join('admin', 'pelayanan.id_admin', '=', 'admin.id_admin')
            ->orderBy('no_pelayanan', 'asc')
            ->select('pelayanan.*', 'admin.*') 
            ->get();

        return view('pelayanan.pelayanan', compact('pelayanan'));
    }

    public function create()
    {
        $adminsa = DB::table('admin')
            ->leftJoin('pelayanan', 'admin.id_admin', '=', 'pelayanan.id_admin')
            ->whereNull('pelayanan.id_admin')
            ->select('admin.*')
            ->get();

        return view('pelayanan.create', compact('adminsa'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_admin' => 'required|exists:admin,id_admin',
            'jenis' => 'required',
            'no_pelayanan' => 'required',
        ], [
            'id_admin.required' => 'Nama tidak boleh kosong!',
            'jenis.required' => 'Jenis tidak boleh kosong!',
            'no_pelayanan.required' => 'No Pelayanan tidak boleh kosong!',
        ]);

        $duplicateEntry = Pelayanan::where('no_pelayanan', $request->no_pelayanan)
            ->exists(); 
            
        if ($duplicateEntry) {
            return redirect()->back()->withErrors(['duplicate' => 'Kombinasi No Pelayanan sudah ada.']);
        }

        $adminExists = Pelayanan::where('id_admin', $request->id_admin)->exists();
        if ($adminExists) {
            return redirect()->back()->withErrors(['admin' => 'Admin ini sudah ditambahkan.']);
        }

        Pelayanan::create([
            'id_admin' => $request->id_admin ?? auth()->user()->id_admin,
            'jenis' => $request->jenis,
            'no_pelayanan' => $request->no_pelayanan,
            // Removed 'id_layanan'
        ]);

        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        $adminsa = DB::table('admin')
            ->leftJoin('pelayanan', 'admin.id_admin', '=', 'pelayanan.id_admin')
            ->whereNull('pelayanan.id_admin')
            ->orWhere('pelayanan.id_admin', $pelayanan->id_admin)
            ->select('admin.*')
            ->get();

        $layanans = Pelayanan::select('jenis')->distinct()->get();

        return view('pelayanan.edit', compact('pelayanan', 'adminsa', 'layanans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_admin' => 'required|exists:admin,id_admin',
            'jenis' => 'required',
            'no_pelayanan' => 'required',
        ], [
            'id_admin.required' => 'Nama tidak boleh kosong!',
            'jenis.required' => 'Jenis tidak boleh kosong!',
            'no_pelayanan.required' => 'No Pelayanan tidak boleh kosong!',
        ]);

        $duplicateEntry = Pelayanan::where('no_pelayanan', $request->no_pelayanan)
            ->where('id_pelayanan', '!=', $id) 
            ->exists();

        if ($duplicateEntry) {
            return redirect()->back()->withErrors(['duplicate' => 'Kombinasi No Pelayanan sudah ada.']);
        }

        $adminExists = Pelayanan::where('id_admin', $request->id_admin)
            ->where('id_pelayanan', '!=', $id) 
            ->exists();

        if ($adminExists) {
            return redirect()->back()->withErrors(['admin' => 'Admin ini sudah ditambahkan.']);
        }

        $pelayanan = Pelayanan::findOrFail($id);
        $pelayanan->id_admin = $request->id_admin;
        $pelayanan->jenis = $request->jenis;
        $pelayanan->no_pelayanan = $request->no_pelayanan;

        $pelayanan->save();

        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);
        $pelayanan->delete();

        return redirect()->route('pelayanan.index')->with('success', 'Pelayanan berhasil dihapus.');
    }
}
