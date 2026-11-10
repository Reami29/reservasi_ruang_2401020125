<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservasiRuangController extends Controller
{
    // Fungsi khusus Tipe A untuk status durasi
    public function statusDurasi($durasi) {
        return $durasi >= 4 ? 'Panjang' : 'Singkat';
    }

    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $ruangFilter = $request->input('ruang_id');

            $query = DB::table('reservasi_ruang')
                ->join('ruang', 'reservasi_ruang.ruang_id', '=', 'ruang.id')
                ->select('reservasi_ruang.*', 'ruang.nama_ruang');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('kode_reservasi', 'like', "%{$search}%")
                      ->orWhere('nama_peminjam', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($ruangFilter) {
                $query->where('reservasi_ruang.ruang_id', $ruangFilter);
            }

            $reservasis = $query->get();
            $ruangs = DB::table('ruang')->get();

            return view('reservasi.index', compact('reservasis', 'ruangs', 'search', 'ruangFilter'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memuat data.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_reservasi' => 'required|size:10|alpha_num|unique:reservasi_ruang,kode_reservasi',
                'nama_peminjam' => 'required|min:3|max:100',
                'email' => 'required|email|max:100|unique:reservasi_ruang,email',
                'durasi_jam' => 'required|integer|between:1,8',
                'ruang_id' => 'required|exists:ruang,id',
            ]);

            DB::table('reservasi_ruang')->insert([
                'kode_reservasi' => $validated['kode_reservasi'],
                'nama_peminjam' => strip_tags(trim($validated['nama_peminjam'])),
                'email' => strip_tags(trim($validated['email'])),
                'durasi_jam' => $validated['durasi_jam'],
                'ruang_id' => $validated['ruang_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('reservasi.index')->with('success', 'Data reservasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data. Pastikan format valid dan unik.');
        }
    }

    public function edit($id)
    {
        $reservasi = DB::table('reservasi_ruang')->where('id', $id)->first();
        $ruangs = DB::table('ruang')->get();
        return view('reservasi.edit', compact('reservasi', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nama_peminjam' => 'required|min:3|max:100',
                'durasi_jam' => 'required|integer|between:1,8',
                'ruang_id' => 'required|exists:ruang,id',
            ]);

            DB::table('reservasi_ruang')->where('id', $id)->update([
                'nama_peminjam' => strip_tags(trim($validated['nama_peminjam'])),
                'durasi_jam' => $validated['durasi_jam'],
                'ruang_id' => $validated['ruang_id'],
                'updated_at' => now(),
            ]);

            return redirect()->route('reservasi.index')->with('success', 'Data reservasi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('reservasi_ruang')->where('id', $id)->delete();
            return redirect()->route('reservasi.index')->with('success', 'Data reservasi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}