@extends('layouts.app')

@section('content')
    <div class="container @if(!empty($isMobile) && $isMobile) p-0 @else mt-4 @endif">
        <div class="row justify-content-center">
            <div class="col-md-6 col-12">
                <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-header text-white text-center py-3 position-relative" style="background: var(--m-primary-gradient, #696cff);">
                        @if(!empty($isMobile) && $isMobile)
                            <a href="{{ url()->previous() }}" class="position-absolute start-0 top-50 translate-middle-y ms-3 text-white text-decoration-none">
                                <i class='bx bx-chevron-left fs-2'></i>
                            </a>
                        @endif
                        <h5 class="mb-0 fw-bold text-white d-flex align-items-center justify-content-center gap-2">
                            <i class='bx bx-qr-scan'></i> Presensi Mandiri Siswa
                        </h5>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        @if(isset($userSiswa) && $userSiswa)
                            <div class="p-3 mb-3 rounded-3 d-flex align-items-center gap-2" style="background: rgba(105, 108, 255, 0.08); border: 1px solid rgba(105, 108, 255, 0.2);">
                                <i class="bx bxs-user-pin text-primary fs-3"></i>
                                <div>
                                    <div class="fw-bold small text-dark">Halo, {{ $userSiswa->nama_siswa }}! 👋</div>
                                    <small class="text-muted" style="font-size: 0.72rem;">Arahkan kamera ke QR Code yang ditampilkan oleh Guru</small>
                                </div>
                            </div>
                        @else
                            <div class="mb-3" id="pilih-kelas-container">
                                <label class="form-label fw-bold">1. Pilih Kelas</label>
                                <select id="select-kelas" class="form-select form-select-lg" style="border-radius: 12px;">
                                    <option value="" selected disabled>-- Pilih Kelas --</option>
                                    @foreach($list_kelas as $k)
                                        <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <div id="wrapper-siswa" class="mb-4" style="display:none;">
                                <label class="form-label fw-bold">2. Pilih Nama Anda</label>
                                <select id="select-siswa" class="form-select form-select-lg" style="border-radius: 12px;">
                                    <option value="" selected disabled>-- Loading Siswa... --</option>
                                </select>
                            </div>
                        @endif

                        <div id="scanner-section" style="@if(isset($userSiswa)) display:block; @else display: none; @endif">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-label-danger px-2 py-1"><i class="bx bx-camera me-1"></i> Kamera Aktif</span>
                                <small class="text-muted" style="font-size: 0.72rem;">Pastikan pencahayaan cukup</small>
                            </div>
                            <div class="scanner-viewport-wrapper position-relative">
                                <div id="reader"></div>
                                <div class="scanner-scanline"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>


    <script>
        const selectKelas = document.getElementById('select-kelas');
        const selectSiswa = document.getElementById('select-siswa');
        const wrapperSiswa = document.getElementById('wrapper-siswa');
        const scannerSection = document.getElementById('scanner-section');
        
        // Data Siswa Login (jika ada)
        const userSiswa = @json($userSiswa ?? null);

        let html5QrcodeScanner;

        // Init Scanner
        function initScanner() {
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                    fps: 15,
                    qrbox: 250,
                    disableFlip: true
                });
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            }
        }

        // Jika Siswa Login -> Langsung Init Scanner
        if (userSiswa) {
            initScanner();
        }

        // Logika Pilih Kelas -> Ambil Siswa (Hanya jika selectKelas ada)
        if (selectKelas) {
            selectKelas.addEventListener('change', function () {
                const idKelas = this.value;
                wrapperSiswa.style.display = 'block';
                scannerSection.style.display = 'none';
    
                fetch(`/get-siswa/${idKelas}`)
                    .then(res => res.json())
                    .then(data => {
                        selectSiswa.innerHTML = '<option value="" selected disabled>-- Pilih Nama --</option>';
                        data.forEach(siswa => {
                            selectSiswa.innerHTML += `<option value="${siswa.id_siswa}">${siswa.nama_siswa}</option>`;
                        });
                    });
            });
        }

        // Logika Pilih Nama -> Buka Kamera (Hanya jika selectSiswa ada)
        if (selectSiswa) {
            selectSiswa.addEventListener('change', function () {
                if (this.value) {
                    scannerSection.style.display = 'block';
                    initScanner();
                }
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Ambil ID Siswa: Prioritas User Login, fallback ke Select Manual
            const idSiswa = userSiswa ? userSiswa.id_siswa : (selectSiswa ? selectSiswa.value : null);

            if (!idSiswa) {
                Swal.fire('Error', 'Identitas siswa tidak ditemukan!', 'error');
                return;
            }

            // Matikan scanner agar tidak bentrok dengan kamera face recognition nanti
            html5QrcodeScanner.clear();

            fetch("{{ route('scan.proses') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_token: decodedText, id_siswa: idSiswa })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // REDIRECT KE HALAMAN VERIFIKASI WAJAH
                        // Gunakan URL yang dikirim dari backend
                        window.location.href = data.redirect_url;
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message,
                            confirmButtonColor: '#ff3e1d'
                        }).then(() => {
                            // Nyalakan kembali scanner jika gagal
                            location.reload();
                        });
                    }
                });
        }
        
        function onScanFailure(error) {
            // console.warn(`Code scan error = ${error}`);
        }
    </script>

    <style>
        /* Mengatasi mirroring dan styling reader */
        #reader video {
            transform: scaleX(-1) !important;
            border-radius: 14px;
            object-fit: cover !important;
            width: 100% !important;
        }

        #reader {
            border: none !important;
            border-radius: 14px;
            overflow: hidden;
            background: #000;
        }

        .scanner-viewport-wrapper {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .scanner-scanline {
            position: absolute;
            top: 10%;
            left: 5%;
            right: 5%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #696cff, #71dd37, transparent);
            box-shadow: 0 0 10px #696cff;
            animation: scanlineMove 2.2s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 10;
        }

        @keyframes scanlineMove {
            0% { top: 12%; opacity: 0.8; }
            50% { opacity: 1; }
            100% { top: 88%; opacity: 0.8; }
        }
    </style>
@endsection