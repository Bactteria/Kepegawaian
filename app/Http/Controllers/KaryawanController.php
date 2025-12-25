<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\KaryawanRequest;
use App\Models\User;
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
            $karyawan = Karyawan::where('user_id', $user->id)->first();
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
        $this->ensureSuperadmin();

        $user = Auth::user();
        return view('karyawan.create', compact('user'));
    }

    public function store(Request $request)
    {
        $this->ensureSuperadmin();

        $request->validate([
            'nama' => 'required|min:3',
            'email' => 'required|email|unique:karyawans,email',
            'jabatan' => 'required',
            'gender' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'unit_kerja' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'foto' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $fotoName = null;

        if ($request->hasFile('foto')) {
            $fotoName = $request->file('foto')->store('karyawan', 'local');
        }

        $linkedUserId = User::query()
            ->where('email', $request->email)
            ->where('role', 'karyawan')
            ->value('id');

        Karyawan::create([
            'user_id' => $linkedUserId,
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

        $karyawanRequest = $karyawan->request;
        return view('karyawan.edit', compact('karyawan', 'karyawanRequest'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $this->ensureCanEdit($karyawan);

        $user = Auth::user();

        if ($user->role === 'karyawan') {
            $request->validate([
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'nullable',
                'telepon' => 'nullable',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $fotoName = $karyawan->request?->foto;
            if ($request->hasFile('foto')) {
                if ($fotoName && Storage::disk('local')->exists($fotoName)) {
                    Storage::disk('local')->delete($fotoName);
                }
                $fotoName = $request->file('foto')->store('karyawan', 'local');
            }

            KaryawanRequest::updateOrCreate(
                ['karyawan_id' => $karyawan->id],
                [
                    'telepon' => $request->telepon,
                    'alamat' => $request->alamat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'foto' => $fotoName,
                    'status' => 'pending',
                    'rejected_reason' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                ]
            );

            return redirect()->route('karyawan.index')
                ->with('success', 'Pengajuan kelengkapan data berhasil dikirim dan menunggu persetujuan superadmin');
        }

        $request->validate([
            'nama' => 'required|min:3',
            'jabatan' => 'required',
            'gender' => 'required',
            'unit_kerja' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable',
            'telepon' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($user?->role === 'superadmin' && !$karyawan->user_id) {
            $linkedUserId = User::query()
                ->where('email', $karyawan->email)
                ->where('role', 'karyawan')
                ->value('id');

            if ($linkedUserId) {
                $karyawan->user_id = $linkedUserId;
                $karyawan->save();
            }
        }

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
            if ($karyawan->foto && Storage::disk('local')->exists($karyawan->foto)) {
                Storage::disk('local')->delete($karyawan->foto);
            }

            // Simpan foto baru ke storage
            $fotoName = $request->file('foto')->store('karyawan', 'local');
            $karyawan->foto = $fotoName;
        }

        $tanggalLahirToUse = $request->filled('tanggal_lahir')
            ? $request->tanggal_lahir
            : $karyawan->tanggal_lahir;

        $alamatToUse = $request->filled('alamat')
            ? $request->alamat
            : $karyawan->alamat;

        $teleponToUse = $request->filled('telepon')
            ? $request->telepon
            : $karyawan->telepon;

        $karyawan->update([
            'nama' => $request->nama,
            'email' => $emailToUse, // Email ditetapkan sesuai role
            'jabatan' => $jabatanToUse, // Jabatan ditetapkan sesuai role
            'unit_kerja' => $unitKerjaToUse, // Unit kerja ditetapkan sesuai role
            'gender' => $request->gender,
            'tanggal_lahir' => $tanggalLahirToUse,
            'alamat' => $alamatToUse,
            'telepon' => $teleponToUse,
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
        if ($karyawan->foto && Storage::disk('local')->exists($karyawan->foto)) {
            Storage::disk('local')->delete($karyawan->foto);
        }
        
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus');
    }

    public function requestsIndex()
    {
        $this->ensureSuperadmin();

        $requests = KaryawanRequest::with('karyawan')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('karyawan.requests.index', compact('requests'));
    }

    public function requestShow($karyawanId)
    {
        $this->ensureSuperadmin();

        $karyawan = Karyawan::findOrFail($karyawanId);
        $req = KaryawanRequest::with('karyawan')->where('karyawan_id', $karyawan->id)->firstOrFail();

        return view('karyawan.requests.show', [
            'karyawan' => $karyawan,
            'req' => $req,
        ]);
    }

    public function requestFoto($karyawanId)
    {
        $this->ensureSuperadmin();

        $req = KaryawanRequest::where('karyawan_id', $karyawanId)->firstOrFail();

        if (!$req->foto || !Storage::disk('local')->exists($req->foto)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($req->foto));
    }

    public function approveRequest($karyawanId)
    {
        $this->ensureSuperadmin();

        $karyawan = Karyawan::findOrFail($karyawanId);
        $req = KaryawanRequest::where('karyawan_id', $karyawan->id)->firstOrFail();

        if ($req->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses');
        }

        if ($req->foto && $req->foto !== $karyawan->foto) {
            if ($karyawan->foto && Storage::disk('local')->exists($karyawan->foto)) {
                Storage::disk('local')->delete($karyawan->foto);
            }
            $karyawan->foto = $req->foto;
        }

        $karyawan->telepon = $req->telepon;
        $karyawan->alamat = $req->alamat;
        $karyawan->tanggal_lahir = $req->tanggal_lahir;
        $karyawan->save();

        $req->status = 'approved';
        $req->reviewed_by = Auth::id();
        $req->reviewed_at = now();
        $req->save();

        return back()->with('success', 'Pengajuan berhasil disetujui');
    }

    public function rejectRequest(Request $request, $karyawanId)
    {
        $this->ensureSuperadmin();

        $data = $request->validate([
            'rejected_reason' => 'nullable|string',
        ]);

        $req = KaryawanRequest::where('karyawan_id', $karyawanId)->firstOrFail();
        if ($req->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses');
        }

        $req->status = 'rejected';
        $req->rejected_reason = $data['rejected_reason'] ?? null;
        $req->reviewed_by = Auth::id();
        $req->reviewed_at = now();
        $req->save();

        return back()->with('success', 'Pengajuan berhasil ditolak');
    }

    public function foto($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $user = Auth::user();
        if ($user?->role !== 'superadmin' && (!$karyawan->user_id || $user?->id !== $karyawan->user_id)) {
            abort(403, 'Anda tidak boleh mengakses foto ini');
        }

        if (!$karyawan->foto || !Storage::disk('local')->exists($karyawan->foto)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($karyawan->foto));
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

        // Karyawan boleh edit bila user_id sama
        if ($user && $karyawan->user_id && $user->id === $karyawan->user_id) {
            return;
        }

        abort(403, 'Anda tidak boleh mengubah data ini');
    }


}
