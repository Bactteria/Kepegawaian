<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $this->ensureSuperadmin();
        
        $users = User::with('karyawan')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->ensureSuperadmin();
        
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $this->ensureSuperadmin();

        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:superadmin,karyawan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $this->ensureSuperadmin();
        
        $user = User::findOrFail($id);
        
        // Prevent editing own account through this route (use profile instead)
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Gunakan menu Profile untuk mengubah data Anda sendiri');
        }
        
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $this->ensureSuperadmin();

        $user = User::findOrFail($id);
        
        // Prevent editing own account through this route
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Gunakan menu Profile untuk mengubah data Anda sendiri');
        }

        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:superadmin,karyawan',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $this->ensureSuperadmin();

        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        // Jika user yang dihapus adalah karyawan, hapus juga data karyawan terkait
        if ($user->role === 'karyawan') {
            $karyawan = Karyawan::where('email', $user->email)->first();
            
            if ($karyawan) {
                // Hapus foto dari storage jika ada
                if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                    Storage::disk('public')->delete($karyawan->foto);
                }
                
                // Hapus data karyawan
                $karyawan->delete();
            }
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Ensure only superadmin can access.
     */
    private function ensureSuperadmin(): void
    {
        if (Auth::user()?->role !== 'superadmin') {
            abort(403, 'Akses hanya untuk superadmin');
        }
    }
}

