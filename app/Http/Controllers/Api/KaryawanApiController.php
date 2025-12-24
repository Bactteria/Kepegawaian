<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanApiController extends Controller
{
    // Daftar semua karyawan (sederhana, tanpa pagination dulu untuk demo)
    public function index()
    {
        $karyawan = Karyawan::select('id', 'nama', 'email', 'jabatan', 'unit_kerja', 'gender', 'telepon')
            ->orderBy('nama')
            ->get();

        return response()->json([
            'data' => $karyawan,
        ]);
    }

    // Detail satu karyawan
    public function show($id)
    {
        $k = Karyawan::select('id', 'nama', 'email', 'jabatan', 'unit_kerja', 'gender', 'telepon', 'alamat')
            ->with(['manager:id,nama,jabatan,unit_kerja'])
            ->findOrFail($id);

        return response()->json([
            'data' => $k,
        ]);
    }
}
