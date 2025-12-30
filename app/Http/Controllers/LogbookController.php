<?php

namespace App\Http\Controllers;

use App\Models\logbook;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class LogbookController extends Controller
{
    public function index()
    {
        $logbook = LogBook::all();

        if ($logbook->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data logbook ditemukan.'
            ], 404);
        }

        return response()->json($logbook, 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nim'             => 'required|exists:mahasiswa,nim',
                'id_program'      => 'required|exists:program_magang,id_program',
                'tanggal'         => 'required|date',
                'aktivitas'       => 'required',
                'lampiran'        => 'nullable|string',
                'jenis_logbook'   => 'required|in:harian,mingguan,bulanan',
                'status_validasi' => 'required|in:belum divalidasi,divalidasi'
            ]);

            $logbook = LogBook::create($validated);

            if ($logbook) {
                return response()->json([
                    'message' => 'LogBook berhasil ditambahkan',
                    'data' => $logbook
                ], 201);
            }

            return response()->json([
                'message' => 'Gagal menambahkan logbook.'
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
        $logbook = LogBook::where('nim', $nim)->first();

        if (!$logbook) {
            return response()->json([
                'message' => "LogBook dengan NIDN $nim tidak ditemukan."
            ], 404);
        }

        return response()->json($logbook, 200);
    }

    public function update(Request $request, $nim)
    {
        $logbook = LogBook::where('nim', $nim)->first();

        if (!$logbook) {
            return response()->json([
                'message' => "LogBook dengan NIDN $nim tidak ditemukan."
            ], 404);
        }

        try {
            $update = $logbook->update($request->all());

            if ($update) {
                return response()->json([
                    'message' => 'Data logbook berhasil diperbarui.',
                    'data' => $logbook
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal memperbarui data logbook.'
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
        $logbook = LogBook::where('nim', $nim)->first();

        if (!$logbook) {
            return response()->json([
                'message' => "LogBook dengan NIDN $nim tidak ditemukan."
            ], 404);
        }

        try {
            if ($logbook->delete()) {
                return response()->json([
                    'message' => "LogBook dengan NIDN $nim berhasil dihapus."
                ], 200);
            }

            return response()->json([
                'message' => 'Gagal menghapus logbook.'
            ], 500);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
