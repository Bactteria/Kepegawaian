@extends('adminlte::page')

@section('title', 'Profil Karyawan')

@section('content_header')
    <h1></h1>
@endsection

@section('content')
<style>
    /* Profile Card Styles */
    .profile-card {
        border: none;
        border-radius: 16px;
        padding: 28px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        padding-bottom: 20px;
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 20px;
    }
    
    .profile-info {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 1;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e5e7eb;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .profile-avatar:hover {
        transform: scale(1.05);
    }
    
    .profile-name {
        font-weight: 700;
        font-size: 24px;
        color: #111827;
        margin-bottom: 4px;
    }
    
    .profile-role {
        color: #6b7280;
        font-size: 16px;
        margin-bottom: 4px;
    }
    
    .profile-address {
        color: #9ca3af;
        font-size: 13px;
    }
    
    /* Section Card Styles */
    .section-card {
        border: none;
        border-radius: 12px;
        padding: 24px;
        background: #ffffff;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.2s ease;
    }
    
    .section-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .section-title {
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 20px;
        color: #111827;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .section-title::before {
        content: '';
        width: 4px;
        height: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }
    
    /* Info Grid Styles */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px 32px;
    }
    
    .info-item {
        padding: 12px;
        border-radius: 8px;
        background: #f9fafb;
        transition: background 0.2s ease;
    }
    
    .info-item:hover {
        background: #f3f4f6;
    }
    
    .info-item small {
        display: block;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .info-item div {
        font-weight: 600;
        font-size: 15px;
        color: #111827;
        word-break: break-word;
    }
    
    /* Button Styles */
    .btn-action {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    /* Alert Styles */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }
    
    .alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    /* Table Styles */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    
    .table-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px 24px;
        font-weight: 700;
        font-size: 18px;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        background: #f9fafb;
        color: #374151;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
        padding: 16px;
    }
    
    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .table tbody tr {
        transition: background 0.2s ease;
    }
    
    .table tbody tr:hover {
        background: #f9fafb;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .img-thumbnail {
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        transition: transform 0.2s ease;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.1);
    }
    
    /* Header Actions */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding: 20px 0;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    
    /* Pagination */
    .pagination {
        margin-top: 24px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }
    
    .empty-state i {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 16px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
    }
</style>

{{-- Alert Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@php
    $isSuperadmin = auth()->user()->role === 'superadmin';
@endphp

@if(!$isSuperadmin && empty($karyawan))
    {{-- Karyawan belum isi data --}}
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>Data Belum Lengkap</strong>
            <p class="mb-0 mt-1">Data default karyawan Anda belum dibuat oleh superadmin. Silakan tunggu.</p>
        </div>
    </div>
@else
    @if($isSuperadmin)
        {{-- Superadmin View: Daftar Karyawan --}}
        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-users"></i> Daftar Karyawan
            </h2>
            <a href="{{ route('karyawan.create') }}" class="btn btn-primary btn-action">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
        </div>

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                                <th>Umur</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>Jabatan</th>
                                <th>Unit Kerja</th>
                                <th>Gender</th>
                                <th>Telepon</th>
                                <th style="width: 180px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawan as $k)
                                <tr>
                                    <td class="text-muted">
                                        {{ ($karyawan->currentPage() - 1) * $karyawan->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        @if($k->foto)
                                            <img src="{{ route('karyawan.foto', $k->id) }}" width="50" height="50" class="img-thumbnail">
                                        @else
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $k->nama }}</strong></td>
                                    <td>
                                        @if($k->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse($k->tanggal_lahir)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($k->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse($k->tanggal_lahir)->age }} tahun
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ \Illuminate\Support\Str::limit($k->alamat ?? '-', 30) }}</span>
                                    </td>
                                    <td>{{ $k->email }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $k->jabatan }}</span>
                                    </td>
                                    <td>{{ $k->unit_kerja ?? '-' }}</td>
                                    <td>
                                        @if($k->gender)
                                            <span class="badge bg-secondary">{{ $k->gender }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $k->telepon ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('karyawan.edit', $k->id) }}" class="btn btn-warning btn-sm btn-action" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('karyawan.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan ini?')">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-action" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p class="mt-3 mb-0">Belum ada data karyawan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($karyawan->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-center">
                            {{ $karyawan->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- View untuk karyawan (hanya data sendiri) --}}
        @if($karyawan && $karyawan->request)
            @if($karyawan->request->status === 'pending')
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div class="ms-2">
                        <strong>Pengajuan sedang diproses</strong>
                        <p class="mb-0 mt-1" style="font-size: 13px;">Kelengkapan data yang Anda ajukan sedang menunggu persetujuan superadmin.</p>
                    </div>
                </div>
            @elseif($karyawan->request->status === 'rejected')
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="ms-2">
                        <strong>Pengajuan ditolak</strong>
                        <p class="mb-0 mt-1" style="font-size: 13px;">{{ $karyawan->request->rejected_reason ?? 'Pengajuan Anda ditolak oleh superadmin.' }}</p>
                    </div>
                </div>
            @endif
        @endif

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <div class="ms-2">
                <strong>Panduan pengisian data karyawan</strong>
                <p class="mb-0 mt-1" style="font-size: 13px;">
                    Data <strong>Telepon, Alamat, Tanggal Lahir, dan Foto</strong> dapat diajukan sendiri oleh karyawan (termasuk Manager).
                    Data <strong>Jabatan, Unit Kerja, Email resmi, dan atasan (Manager)</strong dikelola oleh <strong>HRD / Superadmin</strong>.
                </p>
            </div>
        </div>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-info">
                    <img class="profile-avatar" 
                         src="{{ $karyawan && $karyawan->foto ? route('karyawan.foto', $karyawan->id) : 'https://ui-avatars.com/api/?name='.urlencode($karyawan->nama ?? auth()->user()->name).'&background=667eea&color=fff&size=128' }}" 
                         alt="Foto Profil">
                    <div>
                        <div class="profile-name">{{ $karyawan->nama ?? auth()->user()->name }}</div>
                        <div class="profile-role">
                            <i class="fas fa-briefcase"></i> {{ $karyawan->jabatan ?? '-' }}
                        </div>
                        <div class="profile-address">
                            <i class="fas fa-map-marker-alt"></i> {{ \Illuminate\Support\Str::limit($karyawan->alamat ?? '-', 50) }}
                        </div>
                    </div>
                </div>
                @if($karyawan)
                    <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-primary btn-action">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                @endif
            </div>

            <div class="section-card">
                <div class="section-title">
                    <i class="fas fa-user-circle"></i> Informasi Pribadi
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <small><i class="fas fa-user"></i> Nama Lengkap</small>
                        <div>{{ $karyawan->nama ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <small><i class="fas fa-envelope"></i> Email</small>
                        <div>{{ $karyawan->email ?? auth()->user()->email }}</div>
                    </div>
                    <div class="info-item">
                        <small><i class="fas fa-birthday-cake"></i> Tanggal Lahir</small>
                        <div>
                            @if(!empty($karyawan->tanggal_lahir))
                                {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d-m-Y') }}
                                @php
                                    $age = \Carbon\Carbon::parse($karyawan->tanggal_lahir)->age;
                                @endphp
                                <span class="text-muted" style="font-size:12px;">&ndash; {{ $age }} tahun</span>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <small><i class="fas fa-map-marker-alt"></i> Alamat Lengkap</small>
                        <div>{{ $karyawan->alamat ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <small><i class="fas fa-phone"></i> Telepon</small>
                        <div>{{ $karyawan->telepon ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <small><i class="fas fa-venus-mars"></i> Gender</small>
                        <div>
                            @if($karyawan->gender)
                                <span class="badge bg-secondary">{{ $karyawan->gender }}</span>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <small><i class="fas fa-briefcase"></i> Jabatan</small>
                        <div>
                            @if($karyawan->jabatan)
                                <span class="badge bg-info">{{ $karyawan->jabatan }}</span>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <small><i class="fas fa-building"></i> Unit Kerja</small>
                        <div>{{ $karyawan->unit_kerja ?? '-' }}</div>
                    </div>
                    
                </div>
            </div>
        </div>
    @endif
@endif

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Contoh pemanggilan API daftar karyawan
    fetch('{{ url('/api/karyawan') }}', {
        headers: {
            'Accept': 'application/json'
        }
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Gagal memuat data karyawan dari API');
            }
            return response.json();
        })
        .then(function (json) {
            console.log('Data karyawan dari API:', json);
            // Di sini nanti bisa dikembangkan untuk menampilkan data via JS.
        })
        .catch(function (error) {
            console.error(error);
        });
});
</script>
@endpush
