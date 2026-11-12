@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pusat Bantuan /</span> Panduan Aplikasi
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="bx bx-book-open me-2"></i> Buku Manual Penggunaan Aplikasi Q-Absen</h5>
                </div>
                <div class="card-body mt-3">
                    <p>Selamat datang di Buku Panduan Aplikasi! Berikut adalah penjelasan mengenai alur dan fitur-fitur yang tersedia sesuai dengan peran (role) Anda saat ini.</p>
                    
                    <div class="accordion mt-3" id="accordionPanduan">

                        @if($role === 'admin')
                        <!-- Panduan Admin -->
                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingAdmin1">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdmin1" aria-expanded="false" aria-controls="collapseAdmin1">
                                    1. Dashboard & Laporan Kehadiran
                                </button>
                            </h2>
                            <div id="collapseAdmin1" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Halaman <strong>Dashboard</strong> menyajikan ringkasan statistik aplikasi, termasuk total siswa, guru, kelas, serta tingkat kehadiran harian (Hadir, Terlambat, Alpha). Anda dapat melakukan <strong>Cetak Laporan Kehadiran Global</strong> dalam bentuk Excel atau PDF dengan memilih rentang tanggal tertentu pada form di bagian atas dashboard.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingAdmin2">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdmin2" aria-expanded="false" aria-controls="collapseAdmin2">
                                    2. Master Data (Jurusan, Kelas, Guru, Siswa, Mapel)
                                </button>
                            </h2>
                            <div id="collapseAdmin2" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Menu <strong>Data Master</strong> digunakan untuk mengelola data inti aplikasi.
                                    <ul>
                                        <li><strong>Jurusan & Kelas:</strong> Buat data jurusan terlebih dahulu, lalu buat kelas yang terhubung ke jurusan tersebut.</li>
                                        <li><strong>Guru & Siswa:</strong> Anda bisa menambahkan secara manual atau menggunakan fitur sinkronisasi API. Setiap user baru otomatis dibuatkan akun untuk login.</li>
                                        <li><strong>Mata Pelajaran:</strong> Daftar mata pelajaran yang akan diajarkan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingAdmin3">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdmin3" aria-expanded="false" aria-controls="collapseAdmin3">
                                    3. Sinkronisasi API Eksternal
                                </button>
                            </h2>
                            <div id="collapseAdmin3" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Jika sekolah menggunakan sistem informasi lain (misal API Dapodik/Internal), gunakan menu <strong>Sync API (Kelas/Guru/Siswa)</strong>.
                                    <br>Langkah-langkah:
                                    <ol>
                                        <li>Klik tombol <strong>Tarik Data dari API Eksternal</strong> untuk mengambil data mentah.</li>
                                        <li>Data yang ditarik berstatus <code>draft</code>.</li>
                                        <li>Klik tombol <strong>Aktivasi</strong> pada data yang ingin dimasukkan ke database Q-Absen secara permanen.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingAdmin4">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdmin4" aria-expanded="false" aria-controls="collapseAdmin4">
                                    4. Jadwal Pelajaran (Otomatis & Manual)
                                </button>
                            </h2>
                            <div id="collapseAdmin4" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Menu <strong>Jadwal Pelajaran</strong> mengatur kapan guru mengajar. Guru tidak dapat membuat sesi absensi di luar jadwal mereka.
                                    <ul>
                                        <li><strong>Generate Jadwal:</strong> Admin dapat menggunakan fitur generate acak untuk mengisi slot jadwal secara otomatis.</li>
                                        <li><strong>Edit Manual:</strong> Anda bisa mengubah guru/mapel pada setiap slot jam pelajaran dengan menekan tombol edit pada tabel jadwal mingguan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingAdmin5">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdmin5" aria-expanded="false" aria-controls="collapseAdmin5">
                                    5. Penilaian & Monitoring
                                </button>
                            </h2>
                            <div id="collapseAdmin5" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    <ul>
                                        <li><strong>Kategori Penilaian:</strong> Atur aspek yang dinilai untuk Guru (oleh Admin) maupun Siswa (oleh Guru). Contoh: Kedisiplinan, Kerapihan.</li>
                                        <li><strong>Penilaian Guru:</strong> Admin secara berkala memberikan rating kinerja kepada masing-masing guru.</li>
                                        <li><strong>Monitoring:</strong> Admin dapat melihat rekapitulasi nilai (dalam bentuk grafik Radar) seluruh siswa dan guru.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingAdmin6">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdmin6" aria-expanded="false" aria-controls="collapseAdmin6">
                                    6. Gamifikasi: Poin & Marketplace
                                </button>
                            </h2>
                            <div id="collapseAdmin6" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Sistem memberikan <strong>Poin Integritas</strong> kepada siswa (misal: hadir +2, terlambat -1).
                                    <ul>
                                        <li><strong>Pengaturan Poin:</strong> Ubah bobot poin untuk setiap kehadiran/pelanggaran.</li>
                                        <li><strong>Marketplace:</strong> Kelola "Item Token" (contoh: Token WFH, Toleransi Telat) yang bisa dibeli siswa dengan poin mereka.</li>
                                        <li><strong>Leaderboard:</strong> Papan peringkat poin tertinggi antar siswa di sekolah.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingAdmin7">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdmin7" aria-expanded="false" aria-controls="collapseAdmin7">
                                    7. Helpdesk & Chatbot
                                </button>
                            </h2>
                            <div id="collapseAdmin7" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Menu <strong>Helpdesk</strong> menampung keluhan siswa/guru.
                                    <ul>
                                        <li>Setiap tiket baru akan dibalas otomatis oleh <strong>Virtual Assistant (Bot)</strong>.</li>
                                        <li>Jika bot tidak bisa menyelesaikan masalah, tiket akan di-eskalasi dengan label <strong><span class="text-danger">🚨 BUTUH ADMIN</span></strong>.</li>
                                        <li>Admin dapat membalas pesan tersebut secara manual, yang otomatis menonaktifkan balasan bot untuk tiket tersebut.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingAdmin8">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdmin8" aria-expanded="false" aria-controls="collapseAdmin8">
                                    8. Pengaturan Sistem (Geofence & Koordinat)
                                </button>
                            </h2>
                            <div id="collapseAdmin8" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Pada <strong>Pengaturan Sistem</strong>, Anda dapat:
                                    <ul>
                                        <li>Mengaktifkan/menonaktifkan mode batas wilayah (Geofence) saat absen.</li>
                                        <li>Menentukan titik koordinat sekolah dan batas radius (dalam meter). Jika Geofence aktif, siswa di luar radius ini tidak akan bisa absensi.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif


                        @if($role === 'guru')
                        <!-- Panduan Guru -->
                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingGuru1">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseGuru1" aria-expanded="false" aria-controls="collapseGuru1">
                                    1. Dashboard Guru
                                </button>
                            </h2>
                            <div id="collapseGuru1" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    <strong>Dashboard</strong> menampilkan ringkasan data khusus kelas/mapel yang Anda ajar hari ini. Terdapat grafik kehadiran siswa secara langsung (real-time) dan rekapitulasi singkat.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingGuru2">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseGuru2" aria-expanded="false" aria-controls="collapseGuru2">
                                    2. Membuka & Menutup Sesi Absensi
                                </button>
                            </h2>
                            <div id="collapseGuru2" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    <strong>Alur Absensi:</strong>
                                    <ol>
                                        <li>Pilih menu <strong>Data Absensi</strong> lalu klik "Buat Absensi Baru".</li>
                                        <li>Pilih Kelas dan durasi aktif sesi (misal: 60 menit). Sistem akan menampilkan QR Code besar di layar (proyektor).</li>
                                        <li>Siswa memindai (scan) QR Code tersebut melalui HP mereka masing-masing.</li>
                                        <li>Sesi akan tertutup otomatis saat waktu habis, atau Anda bisa menekan tombol "Tutup Sesi" secara manual. Siswa yang tidak absen hingga sesi ditutup otomatis tercatat sebagai <strong>Alpha</strong>.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingGuru3">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseGuru3" aria-expanded="false" aria-controls="collapseGuru3">
                                    3. Validasi Token Siswa (WFH / Izin / Terlambat)
                                </button>
                            </h2>
                            <div id="collapseGuru3" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Siswa dapat membeli "Token" dari Poin mereka. Jika mereka ingin menggunakannya, mereka akan memberikan <strong>Kode Unik</strong> (misal: WFH-XYZ123) kepada Anda.
                                    <br><br>
                                    <strong>Cara Validasi:</strong> Buka menu <strong>Validasi Token</strong>, masukkan kode tersebut. Jika valid, status absen siswa tersebut (misal dari Alpha menjadi Hadir/WFH) akan otomatis diubah oleh sistem untuk sesi hari itu.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingGuru4">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseGuru4" aria-expanded="false" aria-controls="collapseGuru4">
                                    4. Penilaian & Monitoring Karakter Siswa
                                </button>
                            </h2>
                            <div id="collapseGuru4" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Sebagai guru, Anda bertugas memantau perkembangan karakter siswa:
                                    <ul>
                                        <li><strong>Penilaian Siswa:</strong> Berikan nilai (1-5) untuk kategori karakter (misal: Sopan Santun, Kerajinan) kepada siswa di kelas yang Anda ajar.</li>
                                        <li><strong>Monitoring:</strong> Lihat perkembangan karakter kelas dalam grafik radar interaktif.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingGuru5">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseGuru5" aria-expanded="false" aria-controls="collapseGuru5">
                                    5. Jadwal Mengajar & Laporan Kinerjaku
                                </button>
                            </h2>
                            <div id="collapseGuru5" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    <ul>
                                        <li><strong>Jadwal Mengajar:</strong> Menampilkan jadwal mingguan Anda secara mendetail. Anda hanya bisa membuka absensi sesuai jadwal ini.</li>
                                        <li><strong>Laporan Kinerjaku:</strong> Menampilkan grafik radar dari hasil penilaian Admin terhadap kinerja mengajar Anda.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif


                        @if($role === 'siswa')
                        <!-- Panduan Siswa -->
                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingSiswa1">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSiswa1" aria-expanded="false" aria-controls="collapseSiswa1">
                                    1. Dashboard & Jadwal Pelajaran
                                </button>
                            </h2>
                            <div id="collapseSiswa1" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    <strong>Dashboard</strong> menampilkan riwayat absensimu, total Poin Integritas yang dimiliki, dan grafik kehadiran.
                                    Melalui menu <strong>Jadwal Pelajaran</strong>, kamu bisa melihat seluruh jadwal mata pelajaranmu untuk satu minggu ke depan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingSiswa2">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSiswa2" aria-expanded="false" aria-controls="collapseSiswa2">
                                    2. Cara Melakukan Absensi (Scan QR & Wajah)
                                </button>
                            </h2>
                            <div id="collapseSiswa2" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Sistem absensi kita menggunakan teknologi <strong>Face Recognition</strong> (Pengenalan Wajah) anti-curang.
                                    <br><br>
                                    <strong>Langkah Absen:</strong>
                                    <ol>
                                        <li>Buka menu <strong>Scanner Absensi</strong> di HP kamu. Izinkan akses Kamera dan Lokasi (GPS).</li>
                                        <li>Arahkan kamera ke QR Code yang ditampilkan oleh Guru di depan kelas.</li>
                                        <li>Sistem akan meminta foto wajahmu (Selfie). Pastikan wajah terlihat jelas tanpa masker/kacamata gelap.</li>
                                        <li>Selesai! Kamu akan mendapatkan Poin Integritas jika hadir tepat waktu.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingSiswa3">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSiswa3" aria-expanded="false" aria-controls="collapseSiswa3">
                                    3. Aturan Keterlambatan
                                </button>
                            </h2>
                            <div id="collapseSiswa3" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Sesi absen memiliki batas waktu (contoh: 60 menit). Ada aturan khusus saat kamu absen:
                                    <ul>
                                        <li><strong>0-10 Menit:</strong> Hadir tepat waktu (Mendapat Poin ++).</li>
                                        <li><strong>11-20 Menit:</strong> Terlambat (Mendapat potongan poin).</li>
                                        <li><strong>Lebih dari 20 Menit:</strong> Tombol absen terkunci, kamu dianggap Alpha (kecuali menggunakan Token Toleransi Telat).</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingSiswa4">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSiswa4" aria-expanded="false" aria-controls="collapseSiswa4">
                                    4. Dompet Integritas & Penukaran Token
                                </button>
                            </h2>
                            <div id="collapseSiswa4" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Setiap kehadiran baik memberimu Poin. Poin ini terkumpul di <strong>Dompet Integritas</strong>.
                                    <br><br>
                                    Kamu bisa menukarkan Poin di <strong>Marketplace</strong> untuk mendapatkan "Token Sakti", seperti:
                                    <ul>
                                        <li><strong>Token WFH / Izin Mendadak:</strong> Jika kamu sakit/izin, beli token ini, lalu berikan <em>Kode Token</em> ke Guru untuk divalidasi. Jika divalidasi 1 Guru, status absenmu akan otomatis aman (WFH) selama <strong>satu hari penuh</strong>.</li>
                                        <li><strong>Token Bebas Denda Telat:</strong> Digunakan saat kamu datang sangat terlambat agar absen tidak dikunci.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card mb-2 border">
                            <h2 class="accordion-header text-body d-flex justify-content-between" id="headingSiswa5">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSiswa5" aria-expanded="false" aria-controls="collapseSiswa5">
                                    5. Helpdesk (Pusat Bantuan & Kendala)
                                </button>
                            </h2>
                            <div id="collapseSiswa5" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                                <div class="accordion-body">
                                    Jika kamu mengalami kendala (Kamera error, Lokasi tidak terdeteksi, atau lupa absen), jangan panik!
                                    <ul>
                                        <li>Buka menu <strong>Helpdesk Terpadu</strong> dan buat tiket laporan baru.</li>
                                        <li>Asisten Virtual (Robot Chatbot) kami akan langsung membalas dan memberikan tips penyelesaian cepat.</li>
                                        <li>Jika masalah belum selesai, ketik <strong>"4"</strong> untuk memanggil Admin Manusia agar keluhanmu segera ditangani.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div> <!-- End Accordion -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
