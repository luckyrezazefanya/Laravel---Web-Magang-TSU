<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PendaftaranController extends Controller
{
    public function index()
    {
         
        $pendaftaran = Pendaftaran::all();

        if ($pendaftaran->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data pendaftaran ditemukan.'
            ], 404);
        }
        return view('pages.register');
        
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nim'             => 'required|exists:mahasiswa,nim',
                'id_program'      => 'required|exists:program_magang,id_program',
                'status'          => 'required|in:menunggu,diterima,ditolak',
                'jumlah_konversi' => 'nullable|integer',
                'cv'              => 'nullable|string|max:50',
                'transkrip_nilai' => 'nullable|string|max:50'
            ]);

            $pendaftaran = Pendaftaran::create($validated);

            if ($pendaftaran) {
                return response()->json([
                    'message' => 'Pendaftaran berhasil ditambahkan',
                    'data' => $pendaftaran
                ], 201);
            }

            return response()->json([
                'message' => 'Gagal menambahkan pendaftaran.'
            ], 500);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($nim)
    {
        $pendaftaran = Pendaftaran::where('nim', $nim)->first();

        if (!$pendaftaran) {
            return response()->json([
                'message' => "Pendaftaran dengan NIM $nim tidak ditemukan."
            ], 404);
        }

        return response()->json($pendaftaran, 200);
    }

    public function update(Request $request, $nim)
    {
        $pendaftaran = Pendaftaran::where('nim', $nim)->first();

        if (!$pendaftaran) {
            return response()->json([
                'message' => "Pendaftaran dengan NIM $nim tidak ditemukan."
            ], 404);
        }

        try {
            $update = $pendaftaran->update($request->all());

            if ($update) {
                return response()->json([
                    'message' => 'Data pendaftaran berhasil diperbarui.',
                    'data' => $pendaftaran
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal memperbarui data pendaftaran.'
            ], 500);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat update.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($nim)
    {
        $pendaftaran = Pendaftaran::where('nim', $nim)->first();

        if (!$pendaftaran) {
            return response()->json([
                'message' => "Pendaftaran dengan NIM $nim tidak ditemukan."
            ], 404);
        }

        try {
            if ($pendaftaran->delete()) {
                return response()->json([
                    'message' => "Pendaftaran dengan NIM $nim berhasil dihapus."
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal menghapus pendaftaran.'
            ], 500);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
