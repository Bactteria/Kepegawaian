@extends('adminlte::page')

@section('title', 'Form Pengajuan Cuti')

@section('content_header')
    <h1></h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-gradient-primary text-white rounded-top-3" style="background: linear-gradient(135deg, #0ea5e9, #8b5cf6);">
                <h5 class="mb-0"><i class="fas fa-plane-departure me-2"></i>Form Pengajuan Cuti</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success" style="font-size: 13px;">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" style="font-size: 13px;">
                        <i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan pada pengajuan cuti.
                    </div>
                @endif

                <p class="text-muted" style="font-size: 13px;">
                    Lengkapi form berikut untuk mengajukan cuti. Setelah fitur backend diimplementasikan,
                    pengajuan dapat dikirim untuk diproses oleh atasan dan HRD.
                </p>

                <form action="{{ route('cuti.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label small mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small mb-1">Jenis Cuti</label>
                        <select name="jenis" class="form-control">
                            <option value="">-- Pilih Jenis Cuti --</option>
                            <option value="tahunan" {{ old('jenis') === 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="sakit" {{ old('jenis') === 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                            <option value="melahirkan" {{ old('jenis') === 'melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                            <option value="lainnya" {{ old('jenis') === 'lainnya' ? 'selected' : '' }}>Cuti Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small mb-1">Alasan Cuti</label>
                        <textarea name="alasan" class="form-control" rows="3" placeholder="Tuliskan alasan pengajuan cuti...">{{ old('alasan') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($cuti) && $cuti->count())
            <div class="card shadow-sm border-0 rounded-3 mt-3">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Riwayat Pengajuan Cuti Saya</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive" style="font-size: 13px;">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    <th>Periode</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cuti as $index => $item)
                                    <tr>
                                        <td class="text-muted">{{ $cuti->firstItem() + $index }}</td>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 d-flex justify-content-end">
                        {{ $cuti->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
