<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();   // AMBIL DATA USER LOGIN

        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'telepon' => 'nullable',
            'jabatan' => 'nullable',
            'alamat' => 'nullable',
            'foto' => 'nullable|image|max:2048',
        ]);

        // UPDATE DATA
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->telepon = $request->telepon;
        $user->jabatan = $request->jabatan;
        $user->alamat = $request->alamat;

        // UPDATE FOTO
        if ($request->hasFile('foto')) {

            $fotoName = time().'.'.$request->foto->extension();
            $request->foto->move(public_path('uploads/user'), $fotoName);

            $user->foto = $fotoName;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile berhasil diperbarui!');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai'])->withInput();
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.change-password')
            ->with('success', 'Password berhasil diubah!');
    }
}
