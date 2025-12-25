@extends('adminlte::page')

@section('title', 'Pengajuan Kelengkapan Karyawan')

@section('content_header')
    <h1>Pengajuan Kelengkapan Karyawan</h1>
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
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Unit Kerja</th>
                    <th>Status</th>
                    <th>Diajukan</th>
                    <th style="width: 320px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td>{{ ($requests->currentPage() - 1) * $requests->perPage() + $loop->iteration }}</td>
                        <td>{{ $req->karyawan->nama ?? '-' }}</td>
                        <td>{{ $req->karyawan->email ?? '-' }}</td>
                        <td>{{ $req->karyawan->unit_kerja ?? '-' }}</td>
                        <td>
                            @if($req->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($req->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $req->updated_at?->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex flex-wrap" style="gap: 6px;">
                                <a href="{{ route('karyawan.requests.show', $req->karyawan_id) }}" class="btn btn-primary btn-sm">
                                    Detail
                                </a>
                                @if($req->status === 'pending')
                                    <form action="{{ route('karyawan.requests.approve', $req->karyawan_id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm" onclick="return confirm('Setujui pengajuan ini?')">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('karyawan.requests.reject', $req->karyawan_id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rejected_reason" value="Ditolak oleh superadmin">
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak pengajuan ini?')">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Sudah diproses</span>
                                @endif
                            </div>

                            @if($req->status === 'rejected' && $req->rejected_reason)
                                <div class="text-muted mt-2" style="font-size: 12px;">
                                    Alasan: {{ $req->rejected_reason }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada pengajuan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $requests->links() }}
        </div>
    </div>
</div>

@endsection
