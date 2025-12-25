@extends('adminlte::page')

@section('title', 'Tambah Karyawan')

@section('content_header')
    <h1>Tambah Karyawan</h1>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                @if(auth()->user()->role === 'karyawan')
                    <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" readonly required>
                    <small class="text-muted">Email harus sesuai dengan email login Anda</small>
                @else
                    <input type="email" name="email" class="form-control" required>
                @endif
            </div>

            <div class="mb-3">
                <label>Jabatan</label>
                <select name="jabatan" class="form-control" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Manager">Manager</option>
                    <option value="Staff">Staff</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Unit Kerja</label>
                <select name="unit_kerja" class="form-control" required>
                    <option value="">-- Pilih Unit Kerja --</option>
                    <option value="Sistem dan Harmonisasi Akreditasi">Sistem dan Harmonisasi Akreditasi</option>
                    <option value="Pusat Data dan Informasi">Pusat Data dan Informasi</option>
                    <option value="Penguatan Penerapan Standar dan Penilaian Kesesuaian">Penguatan Penerapan Standar dan Penilaian Kesesuaian</option>
                    <option value="Akreditasi Lembaga Inspeksi dan Lembaga Sertifikasi">Akreditasi Lembaga Inspeksi dan Lembaga Sertifikasi</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="">-- Pilih Gender --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control">
            </div>

            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>

@endsection