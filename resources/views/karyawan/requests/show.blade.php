@extends('adminlte::page')

@section('title', 'Detail Pengajuan Kelengkapan')

@section('content_header')
    <h1>Detail Pengajuan Kelengkapan</h1>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <strong>Ringkasan</strong>
        <a href="{{ route('karyawan.requests.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <h5 class="mb-3">Informasi Karyawan</h5>
        <table class="table table-bordered table-sm">
            <tr>
                <th style="width: 220px">Nama</th>
                <td>{{ $karyawan->nama }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $karyawan->email }}</td>
            </tr>
            <tr>
                <th>Unit Kerja</th>
                <td>{{ $karyawan->unit_kerja }}</td>
            </tr>
            <tr>
                <th>Status Pengajuan</th>
                <td>
                    @if($req->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($req->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @else
                        <span class="badge badge-danger">Rejected</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Diajukan</th>
                <td>{{ $req->updated_at?->format('d/m/Y H:i') }}</td>
            </tr>
            @if($req->status === 'rejected' && $req->rejected_reason)
                <tr>
                    <th>Alasan Ditolak</th>
                    <td>{{ $req->rejected_reason }}</td>
                </tr>
            @endif
        </table>

        <h5 class="mb-3 mt-4">Perbandingan Data</h5>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th style="width: 220px">Field</th>
                    <th>Data Saat Ini</th>
                    <th>Data Diajukan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Telepon</td>
                    <td>{{ $karyawan->telepon ?? '-' }}</td>
                    <td>{{ $req->telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>{{ $karyawan->alamat ?? '-' }}</td>
                    <td>{{ $req->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td>{{ $karyawan->tanggal_lahir ? \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $req->tanggal_lahir ? \Carbon\Carbon::parse($req->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <td>Foto</td>
                    <td>
                        @if($karyawan->foto)
                            <a href="{{ route('karyawan.foto', $karyawan->id) }}" target="_blank" rel="noopener">
                                <img src="{{ route('karyawan.foto', $karyawan->id) }}" width="120" class="img-thumbnail">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($req->foto)
                            <a href="{{ route('karyawan.request.foto', $karyawan->id) }}" target="_blank" rel="noopener">
                                <img src="{{ route('karyawan.request.foto', $karyawan->id) }}" width="120" class="img-thumbnail">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        @if($req->status === 'pending')
            <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                <form action="{{ route('karyawan.requests.approve', $karyawan->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success" onclick="return confirm('Setujui pengajuan ini?')">Approve</button>
                </form>

                <form action="{{ route('karyawan.requests.reject', $karyawan->id) }}" method="POST" style="min-width: 320px;">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="rejected_reason" class="form-control" placeholder="Alasan penolakan (opsional)">
                        <button class="btn btn-danger" onclick="return confirm('Tolak pengajuan ini?')">Reject</button>
                    </div>
                </form>
            </div>
        @else
            <div class="text-muted">Pengajuan sudah diproses.</div>
        @endif
    </div>
</div>

@endsection
