<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Karyawan hanya boleh melihat datanya sendiri
        if ($user->role === 'karyawan') {
            $karyawan = Karyawan::where('email', $user->email)->first();
            return view('karyawan.index', [
                'karyawan' => $karyawan,
                'isSingleView' => true,
            ]);
        }

        // Superadmin melihat semua data
        $karyawan = Karyawan::paginate(10);
        return view('karyawan.index', [
            'karyawan' => $karyawan,
            'isSingleView' => false,
        ]);
    }

    public function managementLevel()
    {
        $managers = Karyawan::where('jabatan', 'like', '%manager%')->orderBy('nama')->get();
        $staffAll = Karyawan::where('jabatan', 'like', '%staff%')->orderBy('nama')->get();

        $groups = $managers->map(function ($manager) use ($staffAll) {
            // Prioritas: staff yang sudah memiliki manager_id
            $staffWithManagerId = $manager->staffs()->orderBy('nama')->get();

            // Tambahan: staff tanpa manager_id tapi unit_kerja sama dengan manager
            $staffByUnitKerja = $staffAll
                ->whereNull('manager_id')
                ->where('unit_kerja', $manager->unit_kerja);

            $mergedStaff = $staffWithManagerId->concat($staffByUnitKerja)->unique('id');

            return [
                'manager' => $manager,
                'staff' => $mergedStaff,
            ];
        });

        return view('karyawan.management', [
            'groups' => $groups,
            'allManagers' => $managers,
            'allStaff' => $staffAll,
        ]);
    }

    public function updateStaffManager(Request $request)
    {
        $this->ensureSuperadmin();

        $data = $request->validate([
            'staff_id' => 'required|exists:karyawans,id',
            'manager_id' => 'nullable|exists:karyawans,id',
        ]);

        $staff = Karyawan::findOrFail($data['staff_id']);
        $staff->manager_id = $data['manager_id'] ?? null;
        $staff->save();

        return back()->with('success', 'Relasi manager-staff berhasil diperbarui.');
    }

    public function create()
    {
        $user = Auth::user();
        
        // Karyawan hanya bisa create sekali - cek apakah sudah ada data dengan email mereka
        if ($user->role === 'karyawan') {
            $existingKaryawan = Karyawan::where('email', $user->email)->first();
            if ($existingKaryawan) {
                return redirect()->route('karyawan.index')
                    ->with('error', 'Anda sudah terdaftar sebagai karyawan. Silakan edit data yang sudah ada.');
            }
        }
        
        return view('karyawan.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Karyawan hanya bisa create sekali - cek apakah sudah ada data dengan email mereka
        if ($user->role === 'karyawan') {
            $existingKaryawan = Karyawan::where('email', $user->email)->first();
            if ($existingKaryawan) {
                return redirect()->route('karyawan.index')
                    ->with('error', 'Anda sudah terdaftar sebagai karyawan. Silakan edit data yang sudah ada.');
            }
        }
        
        // Validasi email: karyawan hanya bisa create dengan email mereka sendiri
        $request->validate([
            'nama' => 'required|min:3',
            'email' => 'required|email|unique:karyawans,email',
            'jabatan' => 'required',
            'gender' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'unit_kerja' => 'required',
            'alamat' => 'required',
            'telepon' => 'required|numeric',
            'foto' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Karyawan hanya bisa create dengan email mereka sendiri
        if ($user->role === 'karyawan' && $request->email !== $user->email) {
            return back()->withErrors(['email' => 'Email harus sesuai dengan email login Anda'])->withInput();
        }

        $fotoName = null;

        if ($request->hasFile('foto')) {
            // Simpan ke storage/app/karyawan
            $fotoName = $request->file('foto')->store('karyawan', 'public');
        }

        Karyawan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'gender' => $request->gender,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'foto' => $fotoName,
        ]);

        return redirect()->route('karyawan.index')
                ->with('success', 'Data karyawan berhasil ditambahkan');
    }



    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $this->ensureCanEdit($karyawan);

        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $this->ensureCanEdit($karyawan);

        $user = Auth::user();

        $request->validate([
            'nama' => 'required|min:3',
            'jabatan' => 'required',
            'gender' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'unit_kerja' => 'required',
            'alamat' => 'required',
            'telepon' => 'required|numeric',
            'foto' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Karyawan tidak boleh mengubah email - tetap sesuai dengan email login
        if ($user->role === 'karyawan') {
            // Pastikan email tetap sesuai dengan email user yang login
            $emailToUse = $user->email;
            // Pastikan jabatan tetap sesuai dengan yang ada di database
            $jabatanToUse = $karyawan->jabatan;
            // Pastikan unit_kerja tetap sesuai dengan yang ada di database
            $unitKerjaToUse = $karyawan->unit_kerja;
        } else {
            // Superadmin bisa ubah email (jika ada perubahan)
            $emailToUse = $request->email ?? $karyawan->email;
            // Superadmin bisa ubah jabatan
            $jabatanToUse = $request->jabatan ?? $karyawan->jabatan;
            // Superadmin bisa ubah unit_kerja
            $unitKerjaToUse = $request->unit_kerja ?? $karyawan->unit_kerja;
        }

        // Jika upload foto baru
        if ($request->hasFile('foto')) {
            // hapus foto lama dari storage
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }

            // Simpan foto baru ke storage
            $fotoName = $request->file('foto')->store('karyawan', 'public');
            $karyawan->foto = $fotoName;
        }

        $karyawan->update([
            'nama' => $request->nama,
            'email' => $emailToUse, // Email ditetapkan sesuai role
            'jabatan' => $jabatanToUse, // Jabatan ditetapkan sesuai role
            'unit_kerja' => $unitKerjaToUse, // Unit kerja ditetapkan sesuai role
            'gender' => $request->gender,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'foto' => $karyawan->foto
        ]);

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui');
    }


    public function destroy($id)
    {
        $this->ensureSuperadmin();

        $karyawan = Karyawan::findOrFail($id);
        
        // Hapus foto dari storage jika ada
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }
        
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus');
    }

    /**
     * Pastikan hanya superadmin yang boleh create/store/destroy.
     */
    private function ensureSuperadmin(): void
    {
        if (Auth::user()?->role !== 'superadmin') {
            abort(403, 'Akses hanya untuk superadmin');
        }
    }

    /**
     * Superadmin bebas edit; karyawan hanya boleh edit datanya sendiri.
     */
    private function ensureCanEdit(Karyawan $karyawan): void
    {
        $user = Auth::user();

        if ($user?->role === 'superadmin') {
            return;
        }

        // Karyawan boleh edit bila email sama
        if ($user && $user->email === $karyawan->email) {
            return;
        }

        abort(403, 'Anda tidak boleh mengubah data ini');
    }


}
