
@extends('adminlte::page')

@section('title', 'Struktur Manager & Staff')

@section('content_header')
    <h1></h1>
@endsection

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding: 20px 0;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .info-text {
        font-size: 13px;
        color: #6b7280;
    }

    .manager-card {
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background-color: #ffffff;
        padding: 18px 20px;
        margin-bottom: 18px;
        box-shadow: 0 4px 8px rgba(15, 23, 42, 0.04);
    }

    .manager-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .manager-name {
        font-weight: 600;
        font-size: 16px;
        color: #111827;
    }

    .manager-meta {
        font-size: 12px;
        color: #6b7280;
    }

    .badge-role {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 999px;
    }

    .badge-role-manager {
        background-color: #eff6ff;
        color: #1d4ed8;
    }

    .staff-count {
        font-size: 12px;
        color: #374151;
    }

    .staff-table {
        margin-top: 8px;
    }

    .staff-table thead th {
        background-color: #f9fafb;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
    }

    .staff-table tbody td {
        font-size: 13px;
        vertical-align: middle;
    }

    .empty-state {
        text-align: center;
        padding: 40px 16px;
        color: #6b7280;
        font-size: 14px;
    }

    .empty-state i {
        font-size: 40px;
        color: #d1d5db;
        margin-bottom: 8px;
    }
</style>

<div class="page-header">
    <h2 class="page-title">
        <i class="fas fa-sitemap"></i> Struktur Manager &amp; Staff
    </h2>
</div>

<div class="mb-3">
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <span class="ms-2 info-text">
            Halaman ini menampilkan hubungan antara <strong>Manager</strong> dan <strong>Staff</strong> di sistem kepegawaian BSN.
            Setiap kartu menunjukkan satu manager, jumlah staff yang dibawahi, dan daftar staff di bawah supervisinya.
            Perubahan struktur (mengatur ulang staff di bawah manager tertentu) hanya dapat dilakukan oleh <strong>Superadmin</strong>,
            sedangkan Manager dan Staff hanya dapat melihat struktur yang sudah ditetapkan.
        </span>
    </div>
</div>

@if($groups->isEmpty())
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p class="mt-2 mb-0">Belum ada data manager dan staff yang dapat ditampilkan.</p>
    </div>
@else
    @foreach($groups as $group)
        @php
            $manager = $group['manager'];
            $staff = $group['staff'];
        @endphp

        <div class="manager-card">
            <div class="manager-header">
                <div>
                    <div class="manager-name">{{ $manager->nama }}</div>
                    <div class="manager-meta">
                        <span class="badge badge-role badge-role-manager">Manager</span>
                        <span class="ms-2">{{ $manager->jabatan }}</span>
                        @if($manager->unit_kerja)
                            <span class="ms-2 text-muted">&bull; {{ $manager->unit_kerja }}</span>
                        @endif
                    </div>
                </div>
                <div class="staff-count">
                    Membawahi <strong>{{ $staff->count() }}</strong> staff
                </div>
            </div>

            @if($staff->isEmpty())
                <div class="text-muted" style="font-size: 13px;">
                    Belum ada staff yang tercatat di bawah manager ini.
                </div>
            @else
                <div class="table-responsive staff-table">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th>Nama Staff</th>
                                <th>Jabatan</th>
                                <th>Unit Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $index => $s)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td>{{ $s->nama }}</td>
                                    <td>
                                        @if($s->jabatan)
                                            <span class="badge bg-secondary">{{ $s->jabatan }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $s->unit_kerja ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach
@endif

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Contoh pemanggilan API struktur manager-staff dari frontend (Blade)
    fetch('{{ url('/api/management-level') }}', {
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Gagal memuat data struktur manager-staff');
            }
            return response.json();
        })
        .then(function (json) {
            console.log('Data struktur manager-staff dari API:', json);
            // Di sini nanti bisa dikembangkan untuk render ulang tampilan berbasis data API.
        })
        .catch(function (error) {
            console.error(error);
        });
});
</script>
@endpush
