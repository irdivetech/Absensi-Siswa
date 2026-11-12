@extends('layouts.app')

@section('title', 'Pendaftaran Wajah & Fiksasi Siswa API')

@section('content')
<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Aktivasi Data & Pendaftaran Wajah</h4>
            <p class="text-muted small">Mensinkronkan draf siswa <strong>{{ $draft->nama }}</strong> dari API menjadi Siswa resmi berakun.</p>
        </div>
        <a href="{{ route('admin.api_siswa.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali ke Draft
        </a>
    </div>

    @if($existingSiswa)
        <div class="alert alert-warning">
            <i class="bx bx-info-circle me-2"></i> <strong>Peringatan Info:</strong> Siswa dengan NIS <b>{{ $draft->no_induk }}</b> sudah ada di Database Siswa manual (Belum punya rekaman wajah). Fiksasi ini akan <b>Mengupdate</b> (menyatukan) data manual dengan data dari API, bukan membuat ganda.
        </div>
    @endif

    <div class="row">
        <!-- DETAIL DATA API -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 card-title">Biodata dari API (Terkunci)</h5>
                </div>
                <div class="card-body pt-3">
                    <table class="table table-borderless table-sm">
                        <tr><td width="30%" class="text-muted">Nama Lengkap</td><td>: <strong>{{ $draft->nama }}</strong></td></tr>
                        <tr><td class="text-muted">NIS / No Induk</td><td>: {{ $draft->no_induk }}</td></tr>
                        <tr><td class="text-muted">NISN</td><td>: {{ $draft->nisn }}</td></tr>
                        <tr><td class="text-muted">Jenis Kelamin</td><td>: {{ $draft->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td></tr>
                        <tr><td class="text-muted">TTL</td><td>: {{ $draft->tempat_lahir }}, {{ $draft->tanggal_lahir }}</td></tr>
                        <tr><td class="text-muted">Agama ID</td><td>: {{ $draft->agama_id }}</td></tr>
                        <tr><td class="text-muted">Rombel API</td><td>: <span class="badge bg-secondary">{{ $draft->nama_rombel ?? '-' }}</span></td></tr>
                        <tr><td class="text-muted">No Telepon</td><td>: {{ $draft->no_telp ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Alamat</td><td>: {{ $draft->alamat ?? '-' }}, RT {{ $draft->rt ?? '-' }} / RW {{ $draft->rw ?? '-' }}, {{ $draft->desa_kelurahan ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Nama Ayah/Ibu</td><td>: {{ $draft->nama_ayah ?? '-' }} / {{ $draft->nama_ibu ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- FORM FIKSASI -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow border-0 border-top border-primary border-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary"><i class="bx bx-check-shield me-2"></i>Tindakan Fiksasi</h5>
                </div>
                <form action="{{ route('admin.api_siswa.aktivasi.store', $draft->id) }}" method="POST" id="formFiksasi">
                    @csrf
                    <div class="card-body">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Kelas/Rombel Valid di Sistem <span class="text-danger">*</span></label>
                            <select name="id_kelas" class="form-select select2" required>
                                <option value="">-- Tentukan Kelas Sebenarnya --</option>
                                @foreach($list_kelas as $k)
                                    <option value="{{ $k->id_kelas }}" {{ ($existingSiswa && $existingSiswa->id_kelas == $k->id_kelas) ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }} ({{ $k->jurusan->kode_jurusan ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih kelas di database lokal untuk menyatukan relasi diabsen.</small>
                        </div>

                        <label class="form-label fw-bold mt-2">Daftarkan Wajah Siswa <span class="text-danger">*</span></label>
                        <div class="text-center rounded" style="background:#f8f9fa; padding:15px; border:1px dashed #ccc;">

                            {{-- Area Kamera & Overlay Guide --}}
                            <div class="video-container mx-auto mb-2 position-relative" style="width: 250px; height: 250px; background-color: #000; border-radius: 12px; overflow: hidden; display: flex; justify-content: center; align-items: center; border: 3px solid #eee;">
                                <video id="webcam" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>
                                {{-- Bingkai Pemandu Bentuk Wajah --}}
                                <div id="face-guide" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 150px; height: 190px; border: 2px solid rgba(255,255,255,0.5); border-radius: 50% 50% 50% 50% / 40% 40% 60% 60%; box-shadow: 0 0 0 9999px rgba(0,0,0,0.6); pointer-events: none;"></div>
                            </div>

                            {{-- Badge Status Jarak --}}
                            <div class="mt-2 mb-3 text-center">
                                <span id="camera-status-text" class="badge bg-secondary px-4 py-2" style="font-size: 0.95rem;"><i class="bx bx-loader-alt bx-spin me-1"></i> Mendeteksi wajah...</span>
                            </div>

                            <button type="button" class="btn btn-sm btn-primary text-white mb-2 shadow" onclick="takeSnapshot()" id="btnManualSnap" disabled>
                                <i class="bx bx-camera me-1"></i> Jepret Wajah
                            </button>

                            {{-- Area Hasil Foto --}}
                            <div id="results" style="display:none;" class="mt-2 text-center">
                                <img id="prev-img" src="" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border:2px solid #28a745;">
                                <p class="text-success small fw-bold mt-1 mb-0"><i class="bx bx-check-circle"></i> Wajah Siap Disimpan!</p>
                            </div>

                            {{-- Form Field Wajah Hidden --}}
                            <input type="hidden" name="image_data" id="image_data" required>
                            <input type="hidden" name="face_descriptor" id="face_descriptor" required>
                            <canvas id="canvas" style="display:none;"></canvas>
                        </div>
                    </div>
                    <div class="card-footer text-end mt-2">
                        <button type="submit" class="btn btn-primary" id="btnSubmit" onclick="return confirm('Wajah & Data akan disimpan menetap ke dalam Database Asli. Apakah Anda yakin?')">
                            <i class="bx bx-save me-1"></i> Simpan Wajah & Aktifkan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/face-api.min.js') }}"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const imageDataInput = document.getElementById('image_data');
        const faceDescriptorInput = document.getElementById('face_descriptor');
        const prevImg = document.getElementById('prev-img');
        const resultsDiv = document.getElementById('results');
        const statusBadge = document.getElementById('camera-status-text');

        let streamActive = null;
        let tinyLoaded = false;
        let modelsLoaded = false;
        let autoCaptureId = null;

        // Canvas reusable untuk detect loop (tidak bikin baru tiap frame)
        const tmpC = document.createElement('canvas');
        let tmpCtx = null;

        const faceGuide = document.getElementById('face-guide');
        const btnSnap = document.getElementById('btnManualSnap');

        // ── FASE 1: Load TinyFaceDetector + Start Camera SEKALIGUS ──
        async function init() {
            const MODEL_URL = window.location.origin + '/models';
            try {
                statusBadge.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Memuat AI...';

                // Load model paling ringan DAN kamera secara paralel
                const [_, stream] = await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    navigator.mediaDevices.getUserMedia({ video: true })
                ]);

                tinyLoaded = true;
                streamActive = stream;
                video.srcObject = stream;
                video.play();
                statusBadge.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Mendeteksi wajah...';

                // Mulai detect loop langsung
                video.addEventListener('loadeddata', () => {
                    tmpC.width = video.videoWidth;
                    tmpC.height = video.videoHeight;
                    tmpCtx = tmpC.getContext('2d');
                    startDetectLoop();
                });

                // FASE 2: Load model berat di background (tidak blocking)
                Promise.all([
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]).then(() => {
                    modelsLoaded = true;
                    console.log("Semua model AI dimuat!");
                });

            } catch (err) {
                console.error("Init error:", err);
                Swal.fire('Error', 'Gagal memuat: ' + err.message, 'error');
            }
        }
        init();

        // ── DETECT LOOP (ringan, inputSize 160) ─────────────────────
        function startDetectLoop() {
            const detectLoop = async () => {
                if (!tinyLoaded || prevImg.src.length > 100) {
                    autoCaptureId = requestAnimationFrame(detectLoop);
                    return;
                }
                // Reuse canvas — hanya clear & redraw
                tmpCtx.setTransform(-1, 0, 0, 1, tmpC.width, 0);
                tmpCtx.drawImage(video, 0, 0, tmpC.width, tmpC.height);
                try {
                    const det = await faceapi.detectSingleFace(tmpC, new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: 0.3 }));
                    if (det) {
                        const ratio = (det.box.width * det.box.height) / (tmpC.width * tmpC.height);
                        if (ratio > 0.15 && ratio < 0.60) {
                            statusBadge.className = 'badge bg-success px-4 py-2';
                            statusBadge.style.fontSize = '0.95rem';
                            statusBadge.innerHTML = '<i class="bx bx-check-circle me-1"></i> Wajah Siap! Silakan Jepret';
                            faceGuide.style.borderColor = '#28a745';
                            btnSnap.disabled = false;
                        } else if (ratio < 0.15) {
                            statusBadge.className = 'badge bg-warning text-dark px-4 py-2';
                            statusBadge.style.fontSize = '0.95rem';
                            statusBadge.innerHTML = '<i class="bx bx-arrow-back me-1"></i> Jarak terlalu jauh — Maju lebih dekat';
                            faceGuide.style.borderColor = '#ffc107';
                            btnSnap.disabled = true;
                        } else {
                            statusBadge.className = 'badge bg-warning text-dark px-4 py-2';
                            statusBadge.style.fontSize = '0.95rem';
                            statusBadge.innerHTML = '<i class="bx bx-arrow-from-left me-1"></i> Jarak terlalu dekat — Mundur sedikit';
                            faceGuide.style.borderColor = '#ffc107';
                            btnSnap.disabled = true;
                        }
                    } else {
                        statusBadge.className = 'badge bg-danger px-4 py-2';
                        statusBadge.style.fontSize = '0.95rem';
                        statusBadge.innerHTML = '<i class="bx bx-error-circle me-1"></i> Wajah tidak terdeteksi';
                        faceGuide.style.borderColor = 'rgba(255,255,255,0.3)';
                        btnSnap.disabled = true;
                    }
                } catch(e) {}
                autoCaptureId = requestAnimationFrame(detectLoop);
            };
            detectLoop();
        }


        // ── Cleanup saat keluar halaman ─────────────────────────────
        window.addEventListener("beforeunload", function () {
            cancelAnimationFrame(autoCaptureId);
            if (streamActive) { streamActive.getTracks().forEach(t => t.stop()); }
        });

        // ── Helper: Crop & Save ────────────────────────────────────
        function processAndSaveImage(fullDetection, baseCanvas) {
            const ctx = canvas.getContext('2d');
            const size = 400;
            canvas.width = size; canvas.height = size;
            const vW = baseCanvas.width, vH = baseCanvas.height, aR = vW / vH;
            let sX, sY, sW, sH;
            if (aR > 1) { sH = vH; sW = vH; sX = (vW - vH) / 2; sY = 0; }
            else         { sW = vW; sH = vW; sX = 0; sY = (vH - vW) / 2; }
            ctx.drawImage(baseCanvas, sX, sY, sW, sH, 0, 0, size, size);

            Swal.close();
            const dataURL = canvas.toDataURL('image/jpeg', 0.9);
            imageDataInput.value = dataURL;
            faceDescriptorInput.value = JSON.stringify(Array.from(fullDetection.descriptor));
            prevImg.src = dataURL;
            resultsDiv.style.display = 'block';
            document.getElementById('btnManualSnap').style.display = 'none';
            Swal.fire({ icon: 'success', title: 'Wajah Terkunci!', text: 'Data wajah siap disimpan!', timer: 1500, showConfirmButton: false });
        }

        // ── Fungsi Jepret Manual ────────────────────────────────────
        window.takeSnapshot = async function () {
            if (!modelsLoaded) { Swal.fire('Sabar...', 'Model AI belum selesai dimuat.', 'warning'); return; }

            Swal.fire({ title: 'Jepret!', text: 'Memproses Biometrik...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const tmpC = document.createElement('canvas');
            tmpC.width = video.videoWidth; tmpC.height = video.videoHeight;
            const ctx = tmpC.getContext('2d');
            ctx.translate(tmpC.width, 0); ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0, tmpC.width, tmpC.height);

            const detection = await faceapi
                .detectSingleFace(tmpC, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.3 }))
                .withFaceLandmarks().withFaceDescriptor();

            if (!detection) {
                Swal.fire({ icon: 'warning', title: 'Wajah Tidak Sempurna', text: 'Pastikan wajah lurus, tidak blur, dan terang.', confirmButtonColor: '#ff3e1d' });
                return;
            }
            processAndSaveImage(detection, tmpC);
        };

        // ── Validasi form sebelum submit ────────────────────────────
        document.getElementById('formFiksasi').addEventListener('submit', function(e) {
            if (!imageDataInput.value || !faceDescriptorInput.value) {
                e.preventDefault();
                Swal.fire('Oops!', 'Anda harus menjepret wajah terlebih dahulu!', 'warning');
            }
        });

    }); // END DOMContentLoaded
    </script>
@endpush
