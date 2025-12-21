@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
<style>
    /* Kartu statistik utama */
    .stat-card {
        border-radius: 18px;
        padding: 20px 22px;
        background: radial-gradient(circle at top left, #eff6ff 0, #ffffff 42%, #f9fafb 100%);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(56, 189, 248, 0.15), transparent 55%);
        opacity: 0.9;
        pointer-events: none;
    }

    .stat-card > * {
        position: relative;
        z-index: 1;
    }

    .stat-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 4px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
    }

    .stat-sub {
        font-size: 12px;
        color: #9ca3af;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1d4ed8;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.25);
    }

    /* Kartu konten / ringkasan */
    .chart-card {
        border-radius: 18px;
        padding: 20px 22px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06);
        border: 1px solid #e5e7eb;
        margin-bottom: 18px;
    }

    .chart-placeholder {
        height: 230px;
        border-radius: 14px;
        background: linear-gradient(135deg, #eef2ff, #eff6ff);
        border: 1px dashed #c7d2fe;
    }

    .table-mini tr th,
    .table-mini tr td {
        padding: 6px 10px;
        font-size: 13px;
    }

    .table-mini tr th {
        color: #6b7280;
        font-weight: 600;
    }

    .table-mini tr td {
        color: #111827;
    }

    @media (max-width: 768px) {
        .stat-card {
            padding: 16px 16px;
            flex-direction: row;
        }

        .stat-value {
            font-size: 22px;
        }

        .chart-card {
            padding: 16px 16px;
        }
    }
</style>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="stat-card">
            <div>
                <div class="stat-label">Komposisi Karyawan</div>
                <div class="stat-value">{{ $totalKaryawan ?? 0 }}</div>
                <div class="stat-sub">Total karyawan berdasarkan jenis kelamin</div>
                <div class="stat-sub" style="margin-top:6px; font-size:11px;">
                    <span style="display:inline-flex;align-items:center;margin-right:8px;">
                        <span style="width:10px;height:10px;border-radius:9999px;background:#f97316;display:inline-block;margin-right:4px;"></span>
                        Laki-laki: {{ $jumlahLaki ?? 0 }}
                    </span>
                    <span style="display:inline-flex;align-items:center;">
                        <span style="width:10px;height:10px;border-radius:9999px;background:#b91c1c;display:inline-block;margin-right:4px;"></span>
                        Perempuan: {{ $jumlahPerempuan ?? 0 }}
                    </span>
                </div>
            </div>
            <div style="width:120px;height:120px;">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div>
                <div class="stat-label">Komposisi Generasi</div>
                <div class="stat-value">{{ array_sum($generationCounts ?? []) }}</div>
                <div class="stat-sub">Baby Boomers, Gen X, Gen Y, Gen Z</div>
            </div>
            <div style="width:180px;height:120px;">
                <canvas id="generationChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Statistik Bulanan</h5>
                <span class="text-muted" style="font-size:12px;">Mockup tampilan grafik</span>
            </div>
            <div class="chart-placeholder"></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-card">
            <h5 class="mb-3">Ringkasan Cepat</h5>
            <table class="table table-borderless table-mini mb-0">
                <tr>
                    <th>Total Karyawan</th>
                    <td class="text-right">{{ $totalKaryawan ?? 0 }}</td>
                </tr>
                @if(auth()->user()->role === 'superadmin')
                    <tr>
                        <th>Total Superadmin &amp; Admin</th>
                        <td class="text-right">{{ $totalAdmin ?? 0 }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Pengguna Aktif</th>
                    <td class="text-right">{{ auth()->user()->name }}</td>
                </tr>
                <tr>
                    <th>Kehadiran Hari Ini</th>
                    <td class="text-right"><span id="jumlah-kehadiran-mini">0</span></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-12">
        <div class="chart-card">
            <h5 class="mb-2">Klasifikasi Data &amp; Hak Akses</h5>
            <p class="mb-2" style="font-size:13px; color:#6b7280;">
                Ringkasan berikut menjelaskan data bawaan yang disiapkan di sistem dan
                data / fitur apa saja yang dapat diisi oleh masing-masing peran.
            </p>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <h6 class="mb-1" style="font-size:13px; font-weight:700;">Superadmin</h6>
                    <ul class="mb-0" style="padding-left:18px; font-size:12px; color:#4b5563;">
                        <li>Mengelola akun pengguna (superadmin, admin, karyawan).</li>
                        <li>Menentukan jabatan &amp; unit kerja karyawan.</li>
                        <li>Menyusun struktur manager &ndash; staff.</li>
                        <li>Menyetujui &amp; mengelola data master kepegawaian.</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-2">
                    <h6 class="mb-1" style="font-size:13px; font-weight:700;">Manager</h6>
                    <ul class="mb-0" style="padding-left:18px; font-size:12px; color:#4b5563;">
                        <li>Melihat data karyawan pada unit kerjanya.</li>
                        <li>Meninjau &amp; memproses pengajuan cuti staff.</li>
                        <li>Memantau riwayat kehadiran tim.</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-2">
                    <h6 class="mb-1" style="font-size:13px; font-weight:700;">Staff / Karyawan</h6>
                    <ul class="mb-0" style="padding-left:18px; font-size:12px; color:#4b5563;">
                        <li>Mengisi &amp; memperbarui data profil pribadi (nama, kontak, alamat, foto).</li>
                        <li>Melakukan absensi (check in / check out).</li>
                        <li>Mengajukan permohonan cuti dan melihat statusnya.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function initGenderChart() {
        var ctx = document.getElementById('genderChart');
        if (!ctx) {
            return;
        }

        var male = {{ $jumlahLaki ?? 0 }};
        var female = {{ $jumlahPerempuan ?? 0 }};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [male, female],
                    backgroundColor: ['#f97316', '#b91c1c'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.parsed || 0;
                                return label + ': ' + value + ' orang';
                            }
                        }
                    }
                }
            }
        });
    }

    function initGenerationChart() {
        var ctx = document.getElementById('generationChart');
        if (!ctx) {
            return;
        }

        var generationData = @json($generationCounts ?? []);
        var labels = Object.keys(generationData);
        var data = Object.values(generationData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Karyawan',
                    data: data,
                    backgroundColor: ['#f97316', '#ea580c', '#c2410c', '#9a3412'],
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.parsed.y || 0;
                                return label + ': ' + value + ' orang';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    const KEHADIRAN_ENDPOINT = '{{ url('/api/absen/kehadiran-hari-ini') }}';

    async function muatKehadiranHariIni() {
        try {
            const response = await fetch(KEHADIRAN_ENDPOINT, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal memuat data kehadiran');
            }

            const data = await response.json();
            const jumlah = typeof data.jumlah_kehadiran === 'number'
                ? data.jumlah_kehadiran
                : 0;

            const elUtama = document.getElementById('jumlah-kehadiran');
            const elMini = document.getElementById('jumlah-kehadiran-mini');

            if (elUtama) {
                elUtama.textContent = jumlah;
            }

            if (elMini) {
                elMini.textContent = jumlah;
            }
        } catch (error) {
            console.error('Gagal memuat kehadiran hari ini:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initGenderChart();
        initGenerationChart();
        muatKehadiranHariIni();
        setInterval(muatKehadiranHariIni, 30000);
    });
</script>
@endsection
