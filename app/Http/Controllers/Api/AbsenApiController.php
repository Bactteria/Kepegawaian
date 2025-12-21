<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absen;

class AbsenApiController extends Controller
{
    public function kehadiranHariIni()
    {
        $today = now()->toDateString();

        $jumlahKehadiran = Absen::whereDate('waktu', $today)
            ->where('tipe', 'check_in')
            ->distinct('user_id')
            ->count('user_id');

        return response()->json([
            'date' => $today,
            'jumlah_kehadiran' => $jumlahKehadiran,
        ]);
    }
}
