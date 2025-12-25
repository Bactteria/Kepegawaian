@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content_header')
    <h1>Tambah User</h1>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       required>
                <small class="text-muted">Minimal 6 karakter</small>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Role <span class="text-danger">*</span></label>
                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Jabatan (untuk Karyawan)</label>
                <select name="jabatan" class="form-control @error('jabatan') is-invalid @enderror">
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Manager" {{ old('jabatan') == 'Manager' ? 'selected' : '' }}>Manager</option>
                    <option value="Staff" {{ old('jabatan') == 'Staff' ? 'selected' : '' }}>Staff</option>
                </select>
                @error('jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Unit Kerja (untuk Karyawan)</label>
                <select name="unit_kerja" class="form-control @error('unit_kerja') is-invalid @enderror">
                    <option value="">-- Pilih Unit Kerja --</option>
                    <option value="Sistem dan Harmonisasi Akreditasi" {{ old('unit_kerja') == 'Sistem dan Harmonisasi Akreditasi' ? 'selected' : '' }}>Sistem dan Harmonisasi Akreditasi</option>
                    <option value="Pusat Data dan Informasi" {{ old('unit_kerja') == 'Pusat Data dan Informasi' ? 'selected' : '' }}>Pusat Data dan Informasi</option>
                    <option value="Penguatan Penerapan Standar dan Penilaian Kesesuaian" {{ old('unit_kerja') == 'Penguatan Penerapan Standar dan Penilaian Kesesuaian' ? 'selected' : '' }}>Penguatan Penerapan Standar dan Penilaian Kesesuaian</option>
                    <option value="Akreditasi Lembaga Inspeksi dan Lembaga Sertifikasi" {{ old('unit_kerja') == 'Akreditasi Lembaga Inspeksi dan Lembaga Sertifikasi' ? 'selected' : '' }}>Akreditasi Lembaga Inspeksi dan Lembaga Sertifikasi</option>
                </select>
                @error('unit_kerja')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Gender (untuk Karyawan)</label>
                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                    <option value="">-- Pilih Gender --</option>
                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </form>

    </div>
</div>

@endsection

