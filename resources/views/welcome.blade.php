<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kepegawaian BSN</title>

        <style>
         html, body {
             margin: 0;
             padding: 0;
             font-family: Arial, Helvetica, sans-serif;
             background-color: #f5f7fb;
             color: #111827;
         }

         .navbar {
             display: flex;
             align-items: center;
             justify-content: space-between;
             padding: 12px 48px;
             border-bottom: 1px solid #e5e7eb;
             background-color: #ffffff;
             position: sticky;
             top: 0;
             z-index: 10;
         }

         .navbar-left {
             display: flex;
             align-items: center;
             gap: 16px;
         }

         .navbar-logo img {
             height: 44px;
             width: auto;
             display: block;
         }

         .page-indicator {
             font-size: 14px;
             color: #6b7280;
         }

         .page-indicator span {
             color: #16a34a;
             font-weight: bold;
             border-bottom: 3px solid #16a34a;
             padding-bottom: 2px;
         }

         .navbar-right {
             display: flex;
             align-items: center;
             gap: 24px;
             font-size: 14px;
         }

         .nav-link {
             color: #374151;
             text-decoration: none;
             padding-bottom: 2px;
         }

         .nav-link:hover {
             color: #111827;
         }

         .nav-link-active {
             font-weight: 600;
             border-bottom: 2px solid #16a34a;
         }

         .nav-dropdown {
             position: relative;
         }

         .nav-dropdown-toggle {
             display: inline-flex;
             align-items: center;
             gap: 4px;
             cursor: pointer;
             background: transparent;
             border: none;
             padding: 0;
             padding-bottom: 2px;
             font-size: 14px;
             color: #374151;
         }

         .nav-dropdown-toggle:hover {
             color: #111827;
         }

         .nav-dropdown-toggle::after {
             content: '\25BE'; /* panah bawah */
             font-size: 10px;
             transform: translateY(1px);
         }

         .nav-dropdown-menu {
             position: absolute;
             top: 100%;
             right: 0;
             margin-top: 10px;
             min-width: 190px;
             background-color: #ffffff;
             border-radius: 10px;
             box-shadow: 0 14px 35px rgba(15, 23, 42, 0.18);
             border: 1px solid #e5e7eb;
             padding: 6px 0;
             display: none;
             z-index: 20;
         }

         .nav-dropdown.is-open .nav-dropdown-menu {
             display: block;
         }

         .nav-dropdown-item {
             display: block;
             padding: 8px 14px;
             font-size: 14px;
             color: #374151;
             text-decoration: none;
             white-space: nowrap;
             transition: background-color 0.15s ease, color 0.15s ease, padding-left 0.15s ease;
         }

         .nav-dropdown-item:hover {
             background-color: #f3f4f6;
             color: #111827;
             padding-left: 18px;
         }

         .login-button {
             padding: 8px 18px;
             border-radius: 999px;
             background-color: #16a34a;
             color: #ffffff;
             border: none;
             font-size: 14px;
             font-weight: 600;
             text-decoration: none;
             box-shadow: 0 10px 25px rgba(22, 163, 74, 0.25);
         }

         .login-button:hover {
             background-color: #15803d;
         }

         .content {
             min-height: calc(100vh - 140px);
             padding: 40px 24px 32px 24px;
             max-width: 1120px;
             margin: 0 auto;
         }

         .roles-section-title {
             font-size: 18px;
             font-weight: 600;
             margin-bottom: 16px;
             color: #111827;
         }

         .roles-grid {
             display: grid;
             grid-template-columns: repeat(3, minmax(0, 1fr));
             gap: 16px;
         }

         .role-card {
             background-color: #ffffff;
             border-radius: 12px;
             padding: 16px 18px;
             border: 1px solid #e5e7eb;
             box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
         }

         .role-title {
             font-size: 14px;
             font-weight: 600;
             margin-bottom: 4px;
         }

         .role-badge {
             display: inline-flex;
             align-items: center;
             padding: 2px 8px;
             border-radius: 999px;
             font-size: 11px;
             margin-bottom: 6px;
         }

         .role-badge-staff {
             background-color: #eff6ff;
             color: #1d4ed8;
         }

         .role-badge-manager {
             background-color: #fef3c7;
             color: #b45309;
         }

         .role-badge-admin {
             background-color: #fee2e2;
             color: #b91c1c;
         }

         .role-text {
             font-size: 13px;
             color: #4b5563;
         }

         .section-block {
             background-color: #ffffff;
             border-radius: 16px;
             padding: 24px 24px 20px 24px;
             box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
             border: 1px solid #e5e7eb;
         }

         .stats-section {
             margin-top: 40px;
             margin-bottom: 32px;
         }

         .stats-title {
             font-size: 18px;
             font-weight: 600;
             margin-bottom: 12px;
             color: #111827;
         }

         .stats-subtitle {
             font-size: 13px;
             color: #6b7280;
             margin-bottom: 16px;
         }

         .stats-grid {
             display: grid;
             grid-template-columns: repeat(3, minmax(0, 1fr));
             gap: 16px;
         }

         .stat-card {
             background-color: #ffffff;
             border-radius: 12px;
             padding: 14px 16px;
             border: 1px solid #e5e7eb;
             box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
         }

         .stat-label {
             font-size: 12px;
             text-transform: uppercase;
             letter-spacing: 0.06em;
             color: #6b7280;
             margin-bottom: 4px;
         }

         .stat-value {
             font-size: 24px;
             font-weight: 700;
             color: #111827;
         }

         .stat-footnote {
             font-size: 11px;
             color: #9ca3af;
             margin-top: 4px;
         }

         .stats-status {
             font-size: 12px;
             color: #6b7280;
             margin-top: 8px;
         }

         .stat-kehadiran-card {
             display: inline-flex;
             align-items: center;
             padding: 4px 10px;
             border-radius: 999px;
             background-color: #dcfce7;
             color: #166534;
             font-size: 12px;
             font-weight: 600;
         }

         .footer {
             border-top: 1px solid #e5e7eb;
             padding: 12px 40px;
             font-size: 12px;
             color: #6b7280;
             text-align: center;
             background-color: #ffffff;
         }

         @media (max-width: 768px) {
             .navbar {
                 padding: 10px 20px;
             }

             .content {
                 padding: 24px 20px 32px 20px;
             }

             .hero {
                 grid-template-columns: minmax(0, 1fr);
             }

             .roles-grid {
                 grid-template-columns: minmax(0, 1fr);
             }
         }
        </style>
    </head>
    <body>
        <header class="navbar">
            <div class="navbar-left">
                <div class="navbar-logo">
                    <img src="{{ asset('uploads/asset/BSN Logo.gif') }}" alt="BSN Logo">
                </div>
                <div class="page-indicator">
                    <span>Kepegawaian BSN</span> | Halaman Utama
                </div>
            </div>

            <nav class="navbar-right">
                <a href="#halaman-utama" class="nav-link nav-link-active">Halaman Utama</a>

                <div class="nav-dropdown">
                    <button type="button" class="nav-link nav-dropdown-toggle">
                        Profil
                    </button>
                    <div class="nav-dropdown-menu" role="menu" aria-label="Menu Profil">
                        <a href="#tentang-bsn" class="nav-dropdown-item" role="menuitem">Tentang BSN</a>
                        <a href="#struktur-organisasi" class="nav-dropdown-item" role="menuitem">Struktur Organisasi</a>
                    </div>
                </div>

                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="login-button">Login</a>
                @else
                    <span class="login-button">Login</span>
                @endif
            </nav>
        </header>

        <main class="content">
            <section class="stats-section section-block" aria-label="Statistik Kepegawaian">
                <h2 class="stats-title">Statistik Kepegawaian</h2>
                <div class="stats-grid">
                    <article class="stat-card">
                        <div class="stat-label">Total Karyawan</div>
                        <div class="stat-value" id="stat-total-karyawan">-</div>
                        <div class="stat-footnote">Seluruh data karyawan terdaftar.</div>
                    </article>

                    <article class="stat-card">
                        <div class="stat-label">Total Manager</div>
                        <div class="stat-value" id="stat-total-manager">-</div>
                        <div class="stat-footnote">Karyawan dengan jabatan manager.</div>
                    </article>

                    <article class="stat-card">
                        <div class="stat-label">Total Unit Kerja</div>
                        <div class="stat-value" id="stat-total-unit">-</div>
                        <div class="stat-footnote">Unit kerja unik yang tercatat.</div>
                    </article>
                </div>
                <div class="stats-status">
                    <span class="stat-kehadiran-card">
                        Kehadiran: <span id="stat-kehadiran-hari-ini">0</span>
                    </span>
                </div>
                <div class="stats-status" id="stats-status-text">Mengambil data statistik dari API...</div>
            </section>

            <section id="profil" class="section-block">
                <h2 class="roles-section-title">Peran Pengguna dalam Sistem</h2>
                <div class="roles-grid">
                    <article class="role-card">
                        <div class="role-badge role-badge-staff">Staff</div>
                        <div class="role-title">Staff</div>
                        <p class="role-text">
                            Mengelola dan memperbarui data pribadi, mengajukan permohonan terkait kepegawaian,
                            serta melihat status persetujuan dari atasan.
                        </p>
                    </article>

                    <article class="role-card">
                        <div class="role-badge role-badge-manager">Manager</div>
                        <div class="role-title">Manager</div>
                        <p class="role-text">
                            Meninjau dan menyetujui pengajuan dari Staff di unitnya, memantau komposisi tim,
                            serta menjadi pintu pertama dalam proses persetujuan.
                        </p>
                    </article>

                    <article class="role-card">
                        <div class="role-badge role-badge-admin">Superadmin</div>
                        <div class="role-title">Superadmin</div>
                        <p class="role-text">
                            Mengelola seluruh data master kepegawaian, pengaturan pengguna dan hak akses,
                            serta melakukan pengawasan menyeluruh terhadap aktivitas sistem.
                        </p>
                    </article>
                </div>
            </section>
        </main>

        <footer class="footer">
            &copy; {{ date('Y') }} BSN. All rights reserved.
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Statistik kepegawaian dari API
                var totalKaryawanEl = document.getElementById('stat-total-karyawan');
                var totalManagerEl = document.getElementById('stat-total-manager');
                var totalUnitEl = document.getElementById('stat-total-unit');
                var statusTextEl = document.getElementById('stats-status-text');
                var kehadiranValueEl = document.getElementById('stat-kehadiran-hari-ini');

                if (totalKaryawanEl && totalManagerEl && totalUnitEl && statusTextEl) {
                    statusTextEl.textContent = 'Mengambil data statistik dari API...';
                    statusTextEl.style.color = '#6b7280';

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
                            console.log('Data karyawan untuk statistik:', json);

                            var items = Array.isArray(json) ? json : (json.data || []);

                            var totalKaryawan = items.length;
                            var totalManager = 0;
                            var unitSet = {};

                            items.forEach(function (item) {
                                var jabatan = (item.jabatan || '').toLowerCase();
                                if (jabatan.includes('manager')) {
                                    totalManager++;
                                }

                                if (item.unit_kerja) {
                                    unitSet[item.unit_kerja] = true;
                                }
                            });

                            var totalUnit = Object.keys(unitSet).length;

                            totalKaryawanEl.textContent = totalKaryawan;
                            totalManagerEl.textContent = totalManager;
                            totalUnitEl.textContent = totalUnit;

                            statusTextEl.textContent = 'Statistik berhasil dimuat dari backend.';
                            statusTextEl.style.color = '#16a34a';
                        })
                        .catch(function (error) {
                            console.error(error);
                            statusTextEl.textContent = 'Terjadi kesalahan saat memuat data statistik dari API. Silakan cek koneksi backend.';
                            statusTextEl.style.color = '#b91c1c';
                        });
                }

                // Statistik kehadiran hari ini dari API absensi
                function muatKehadiranHariIniLanding() {
                    if (!kehadiranValueEl) {
                        return;
                    }

                    fetch('{{ url('/api/absen/kehadiran-hari-ini') }}', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                        .then(function (response) {
                            if (!response.ok) {
                                throw new Error('Gagal memuat data kehadiran');
                            }
                            return response.json();
                        })
                        .then(function (json) {
                            var jumlah = json && typeof json.jumlah_kehadiran !== 'undefined'
                                ? json.jumlah_kehadiran
                                : 0;

                            kehadiranValueEl.textContent = jumlah;
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                }

                muatKehadiranHariIniLanding();

                // Dropdown Profil: buka/tutup dengan klik
                var navDropdown = document.querySelector('.nav-dropdown');
                var navToggle = navDropdown ? navDropdown.querySelector('.nav-dropdown-toggle') : null;

                if (navDropdown && navToggle) {
                    navToggle.addEventListener('click', function (event) {
                        event.stopPropagation();
                        navDropdown.classList.toggle('is-open');
                    });

                    // Tutup dropdown ketika klik di luar
                    document.addEventListener('click', function () {
                        navDropdown.classList.remove('is-open');
                    });
                }
            });
        </script>
    </body>
</html>
