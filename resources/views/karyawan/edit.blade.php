@extends('adminlte::page')

@section('title', 'Edit Karyawan')

@section('content_header')
    <h1>Edit Karyawan</h1>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        @if(auth()->user()->role === 'karyawan' && isset($karyawanRequest))
            @if($karyawanRequest && $karyawanRequest->status === 'pending')
                <div class="alert alert-info">
                    Pengajuan Anda sedang menunggu persetujuan superadmin.
                </div>
            @elseif($karyawanRequest && $karyawanRequest->status === 'rejected')
                <div class="alert alert-danger">
                    Pengajuan Anda ditolak. {{ $karyawanRequest->rejected_reason ?? '' }}
                </div>
            @endif
        @endif

        <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Nama --}}
                <div class="col-md-6 mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $karyawan->nama }}" {{ auth()->user()->role === 'karyawan' ? 'readonly' : '' }} required>
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    @if(auth()->user()->role === 'karyawan')
                        <input type="email" name="email" class="form-control" value="{{ $karyawan->email }}" readonly required>
                        <small class="text-muted">Email tidak dapat diubah</small>
                    @else
                        <input type="email" name="email" class="form-control" value="{{ $karyawan->email }}" required>
                    @endif
                </div>

                {{-- Jabatan --}}
                <div class="col-md-6 mb-3">
                    <label>Jabatan</label>
                    @if(auth()->user()->role === 'karyawan')
                        <input type="text" name="jabatan" class="form-control" value="{{ $karyawan->jabatan }}" readonly required>
                        <small class="text-muted">Jabatan tidak dapat diubah</small>
                    @else
                        <input type="text" name="jabatan" class="form-control" value="{{ $karyawan->jabatan }}" required>
                    @endif
                </div>

                {{-- Unit Kerja --}}
                <div class="col-md-6 mb-3">
                    <label>Unit Kerja</label>
                    @if(auth()->user()->role === 'karyawan')
                        {{-- Untuk karyawan, gunakan hidden input untuk mengirim nilai --}}
                        <input type="hidden" name="unit_kerja" value="{{ $karyawan->unit_kerja }}">
                        <input type="text" class="form-control" value="{{ $karyawan->unit_kerja }}" readonly>
                        <small class="text-muted">Unit kerja tidak dapat diubah</small>
                    @else
                        <select name="unit_kerja" class="form-control" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @php
                                $units = [
                                    'Sistem dan Harmonisasi Akreditasi',
                                    'Pusat Data dan Informasi',
                                    'Penguatan Penerapan Standar dan Penilaian Kesesuaian',
                                    'Akreditasi Lembaga Inspeksi dan Lembaga Sertifikasi'
                                ];
                            @endphp
                            @foreach($units as $u)
                                <option value="{{ $u }}" {{ $karyawan->unit_kerja == $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                {{-- Gender --}}
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    @if(auth()->user()->role === 'karyawan')
                        <input type="hidden" name="gender" value="{{ $karyawan->gender }}">
                        <input type="text" class="form-control" value="{{ $karyawan->gender }}" readonly>
                        <small class="text-muted">Gender tidak dapat diubah</small>
                    @else
                        <select name="gender" class="form-control" required>
                            <option value="">-- Pilih Gender --</option>
                            <option value="Laki-laki" {{ $karyawan->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $karyawan->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    @endif
                </div>

                {{-- Tanggal Lahir --}}
                <div class="col-md-6 mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ isset($karyawanRequest) && $karyawanRequest ? $karyawanRequest->tanggal_lahir : $karyawan->tanggal_lahir }}">
                </div>

                {{-- Telepon --}}
                <div class="col-md-6 mb-3">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ isset($karyawanRequest) && $karyawanRequest ? $karyawanRequest->telepon : $karyawan->telepon }}">
                </div>

                {{-- Alamat --}}
                <div class="col-md-12 mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control">{{ isset($karyawanRequest) && $karyawanRequest ? $karyawanRequest->alamat : $karyawan->alamat }}</textarea>
                </div>

                {{-- Foto Baru --}}
                <div class="col-md-12 mb-3">
                    <label>Foto Baru (Opsional)</label>
                    <input type="file" name="foto" class="form-control">

                    @php
                        $fotoToShow = null;
                        if (isset($karyawanRequest) && $karyawanRequest && $karyawanRequest->foto) {
                            $fotoToShow = $karyawanRequest->foto;
                        } else {
                            $fotoToShow = $karyawan->foto;
                        }
                    @endphp

                    @if($fotoToShow)
                        <div class="mt-2">
                            <strong>Foto Saat Ini:</strong><br>
                            <img src="{{ route('karyawan.foto', $karyawan->id) }}"
                                 width="120"
                                 class="img-thumbnail mt-1">
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Kembali</a>

        </form>

    </div>
</div>

@endsection
