@extends('adminlte::page')

@section('title', 'Absen Kehadiran')

@section('content_header')
    <h1></h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-gradient-primary text-white rounded-top-3" style="background: linear-gradient(135deg, #4f46e5, #06b6d4);">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Absen Kehadiran</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success" style="font-size: 13px;">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger" style="font-size: 13px;">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <p class="text-muted mb-4" style="font-size: 13px;">
                    Gunakan tombol di bawah ini untuk melakukan <strong>Check In</strong> saat mulai bekerja dan
                    <strong>Check Out</strong> saat selesai bekerja. Fitur ini masih berupa tampilan demo dan siap
                    dikembangkan dengan penyimpanan ke database.
                </p>

                <div class="d-flex flex-column flex-md-row gap-2 mb-3">
                    <form action="{{ route('absen.store') }}" method="POST" class="flex-fill">
                        @csrf
                        <input type="hidden" name="tipe" value="check_in">
                        <button type="submit" class="btn btn-success w-100 py-2" @if(!empty($sudahCheckInHariIni)) disabled @endif>
                            <i class="fas fa-sign-in-alt me-2"></i>@if(!empty($sudahCheckInHariIni))Sudah Check In Hari Ini @else Check In @endif
                        </button>
                    </form>
                    <form action="{{ route('absen.store') }}" method="POST" class="flex-fill mt-2 mt-md-0">
                        @csrf
                        <input type="hidden" name="tipe" value="check_out">
                        <button type="submit" class="btn btn-danger w-100 py-2" @if(!empty($sudahCheckOutHariIni)) disabled @endif>
                            <i class="fas fa-sign-out-alt me-2"></i>@if(!empty($sudahCheckOutHariIni))Sudah Check Out Hari Ini @else Check Out @endif
                        </button>
                    </form>
                </div>

                <div class="alert alert-info mb-3" style="font-size: 13px;">
                    <i class="fas fa-info-circle me-2"></i>
                    Waktu saat ini (WIB): <strong>{{ now('Asia/Jakarta')->format('d M Y, H:i') }}</strong>.
                    Integrasi logika absen (penyimpanan, validasi, dsb.) dapat ditambahkan kemudian sesuai kebutuhan.
                </div>

                @if(isset($riwayat) && $riwayat->count())
                    <div class="table-responsive" style="font-size: 13px;">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    @if(!empty($isSuperadmin))
                                        <th>Nama</th>
                                    @endif
                                    <th>Jenis</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayat as $index => $item)
                                    <tr>
                                        <td class="text-muted">
                                            @if(!empty($isSuperadmin) && method_exists($riwayat, 'firstItem'))
                                                {{ $riwayat->firstItem() + $index }}
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </td>
                                        @if(!empty($isSuperadmin))
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                        @endif
                                        <td>
                                            @if($item->tipe === 'check_in')
                                                <span class="badge bg-success">Check In</span>
                                            @else
                                                <span class="badge bg-danger">Check Out</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->waktu->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($isSuperadmin) && method_exists($riwayat, 'hasPages') && $riwayat->hasPages())
                        <div class="mt-2 d-flex justify-content-end">
                            {{ $riwayat->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
