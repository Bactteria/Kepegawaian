<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absen;

class AbsenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperadmin = $user->role === 'superadmin';

        if ($isSuperadmin) {
            $riwayat = Absen::with('user')
                ->select('absens.*')
                ->join('users', 'users.id', '=', 'absens.user_id')
                ->orderBy('users.name')
                ->orderByDesc('waktu')
                ->paginate(15);
        } else {
            $riwayat = Absen::where('user_id', $user->id)
                ->latest('waktu')
                ->limit(5)
                ->get();
        }

        $sudahCheckInHariIni = Absen::where('user_id', $user->id)
            ->whereDate('waktu', now()->toDateString())
            ->where('tipe', 'check_in')
            ->exists();

        $sudahCheckOutHariIni = Absen::where('user_id', $user->id)
            ->whereDate('waktu', now()->toDateString())
            ->where('tipe', 'check_out')
            ->exists();

        return view('absen.index', compact('riwayat', 'sudahCheckInHariIni', 'sudahCheckOutHariIni', 'isSuperadmin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:check_in,check_out',
        ]);

        $user = Auth::user();

        if ($request->input('tipe') === 'check_in') {
            $sudahCheckIn = Absen::where('user_id', $user->id)
                ->whereDate('waktu', now()->toDateString())
                ->where('tipe', 'check_in')
                ->exists();

            if ($sudahCheckIn) {
                return redirect()->route('absen.index')->with('error', 'Anda sudah melakukan Check In hari ini.');
            }
        }

        if ($request->input('tipe') === 'check_out') {
            $sudahCheckOut = Absen::where('user_id', $user->id)
                ->whereDate('waktu', now()->toDateString())
                ->where('tipe', 'check_out')
                ->exists();

            if ($sudahCheckOut) {
                return redirect()->route('absen.index')->with('error', 'Anda sudah melakukan Check Out hari ini.');
            }
        }

        Absen::create([
            'user_id' => $user->id,
            'tipe' => $request->input('tipe'),
            'waktu' => now(),
            'keterangan' => null,
        ]);

        return redirect()->route('absen.index')->with('success', 'Absen berhasil dicatat.');
    }
}
