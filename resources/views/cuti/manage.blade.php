@extends('adminlte::page')

@section('title', 'Manajemen Cuti Karyawan')

@section('content_header')
    <h1></h1>
@endsection

@section('content')
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-plane-departure me-2"></i>Manajemen Cuti Karyawan</h5>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3" style="font-size: 13px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="table-responsive" style="font-size: 13px;">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama</th>
                        <th>Periode</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuti as $index => $item)
                        <tr>
                            <td class="text-muted">{{ $cuti->firstItem() + $index }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}</td>
                            <td>{{ ucfirst($item->jenis) }}</td>
                            <td>
                                @if($item->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($item->status === 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-secondary">Ditangguhkan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <form action="{{ route('cuti.update-status', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('cuti.update-status', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="ditangguhkan">
                                        <button type="submit" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('cuti.update-status', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="btn btn-warning btn-sm text-dark">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada pengajuan cuti.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cuti->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-end">
                    {{ $cuti->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
