<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cuti;

class CutiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cuti = Cuti::where('user_id', $user->id)
            ->latest('created_at')
            ->paginate(5);

        return view('cuti.index', compact('cuti'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis' => 'required|string|max:100',
            'alasan' => 'required|string',
        ]);

        $user = Auth::user();

        Cuti::create([
            'user_id' => $user->id,
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'jenis' => $data['jenis'],
            'alasan' => $data['alasan'],
            'status' => 'pending',
        ]);

        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function manage()
    {
        $cuti = Cuti::with('user')
            ->latest('created_at')
            ->paginate(10);

        return view('cuti.manage', compact('cuti'));
    }

    public function updateStatus(Request $request, Cuti $cuti)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,disetujui,ditangguhkan',
        ]);

        $cuti->update([
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Status cuti berhasil diperbarui.');
    }
}
