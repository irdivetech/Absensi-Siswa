@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    {{-- Notifikasi Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">Data Siswa</h5>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                <i class="bx bx-user-plus me-1"></i> Tambah Siswa
            </button>
        </div>

        <div class="card-body">
            {{-- Form Pencarian --}}
            <form action="{{ route('siswa.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Cari Siswa</label>
                        <input type="text" name="q" class="form-control" placeholder="Cari NIS atau Nama..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filter Kelas</label>
                        <select name="kelas" class="form-select">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($list_kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filter Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">-- Semua Gender --</option>
                            <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-2"><i class="bx bx-search"></i> Cari</button>
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary w-100"><i class="bx bx-refresh"></i> Reset</a>
                    </div>
                </div>
            </form>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>L/P</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($siswas as $s)
                            <tr>
                                <td>
                                    @if($s->foto)
                                        <img src="{{ asset('storage/siswa/' . $s->foto) }}" width="40" class="rounded-circle">
                                    @else
                                        <div class="avatar avatar-sm bg-secondary rounded-circle"></div>
                                    @endif
                                </td>
                                <td>{{ $s->nis }}</td>
                                <td><strong>{{ $s->nama_siswa }}</strong></td>
                                <td>{{ $s->kelas->nama_kelas ?? 'N/A' }}</td>
                                <td>{{ $s->jenis_kelamin }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if(!$s->face_descriptor)
                                            <button class="btn btn-sm btn-info text-white btn-tambah-wajah" 
                                                data-id="{{ $s->id_siswa }}" 
                                                data-nama="{{ $s->nama_siswa }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalTambahWajah"
                                                title="Tambah Wajah">
                                                <i class="bx bx-camera"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-warning text-white btn-edit-siswa"
                                            data-id="{{ $s->id_siswa }}"
                                            data-nis="{{ $s->nis }}"
                                            data-nama="{{ $s->nama_siswa }}"
                                            data-kelas="{{ $s->id_kelas }}"
                                            data-gender="{{ $s->jenis_kelamin }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditSiswa"
                                            title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <form action="{{ route('siswa.destroy', $s->id_siswa) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data siswa belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Siswa --}}
    <div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Siswa Baru & Pendaftaran Wajah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('siswa.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            {{-- SISI KIRI: Input Data Diri --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">NIS</label>
                                    <input type="text" name="nis" class="form-control" placeholder="Masukkan NIS" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <input type="text" name="nama_siswa" class="form-control" placeholder="Nama Lengkap"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kelas</label>
                                    <select name="id_kelas" class="form-select" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($list_kelas as $k)
                                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- SISI KANAN: Input Kamera --}}
                            <div class="col-md-6 text-center border-start">
                                <label class="form-label fw-bold d-block">Ambil Dataset Wajah</label>

                                {{-- Container Video --}}
                                <div class="video-container mx-auto position-relative">
                                    <video id="webcam" autoplay playsinline></video>
                                    {{-- Bingkai Pemandu Bentuk Wajah Manusia --}}
                                    <div id="face-guide" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 140px; height: 180px; border: 2px solid rgba(255,255,255,0.5); border-radius: 50% 50% 50% 50% / 40% 40% 60% 60%; box-shadow: 0 0 0 9999px rgba(0,0,0,0.6); pointer-events: none;"></div>
                                </div>

                                <div class="mt-2 mb-3">
                                    <span id="camera-status-text" class="badge bg-secondary px-4 py-2" style="font-size: 0.95rem;"><i class="bx bx-loader-alt bx-spin me-1"></i> Mendeteksi wajah...</span>
                                </div>

                                <button type="button" class="btn btn-sm btn-primary text-white mb-3 shadow" onclick="takeSnapshot()" id="btnSnap1" disabled>
                                    <i class="bx bx-camera me-1"></i> Jepret Wajah
                                </button>

                                {{-- Area Hasil Foto --}}
                                <div id="results" style="display:none;" class="mt-2">
                                    <p class="small fw-bold mb-1">Hasil Jepretan:</p>
                                    <img id="prev-img" src="" class="img-thumbnail mb-1">
                                    <p class="text-success small fw-bold mb-0"><i class="bx bx-check-circle"></i> Wajah Siap!</p>
                                </div>

                                {{-- Data Hidden --}}
                                <input type="hidden" name="image_data" id="image_data" required>
                                <input type="hidden" name="face_descriptor" id="face_descriptor">
                                <canvas id="canvas" style="display:none;"></canvas>
                            </div>{{-- end col-md-6 kanan --}}
                        </div>{{-- end row --}}
                    </div>{{-- end modal-body --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Siswa & Wajah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Edit Siswa --}}
    <div class="modal fade" id="modalEditSiswa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditSiswa" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIS</label>
                            <input type="text" name="nis" id="edit_nis" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama_siswa" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kelas</label>
                            <select name="id_kelas" id="edit_kelas" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($list_kelas as $k)
                                    <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="edit_gender" class="form-select" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Wajah --}}
    <div class="modal fade" id="modalTambahWajah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pendaftaran Wajah: <span id="wajah_nama_siswa" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahWajah" method="POST">
                    @csrf
                    <div class="modal-body text-center">
                        <label class="form-label fw-bold d-block">Ambil Dataset Wajah</label>
                        <div class="video-container mx-auto position-relative">
                            <video id="webcam_wajah" autoplay playsinline></video>
                            <div id="face-guide_wajah" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 140px; height: 180px; border: 2px solid rgba(255,255,255,0.5); border-radius: 50% 50% 50% 50% / 40% 40% 60% 60%; box-shadow: 0 0 0 9999px rgba(0,0,0,0.6); pointer-events: none;"></div>
                        </div>
                        <div class="mt-2 mb-3 text-center">
                            <span id="camera-status-text_wajah" class="badge bg-secondary px-4 py-2" style="font-size: 0.95rem;"><i class="bx bx-loader-alt bx-spin me-1"></i> Mendeteksi wajah...</span>
                        </div>

                        <button type="button" class="btn btn-sm btn-primary text-white mb-3 shadow" onclick="takeSnapshotWajah()" id="btnSnapW" disabled>
                            <i class="bx bx-camera me-1"></i> Jepret Wajah
                        </button>
                        <div id="results_wajah" style="display:none;" class="mt-2">
                            <p class="small fw-bold mb-1">Hasil Jepretan:</p>
                            <img id="prev-img_wajah" src="" class="img-thumbnail mb-1">
                            <p class="text-success small fw-bold mb-0"><i class="bx bx-check-circle"></i> Wajah Siap!</p>
                        </div>
                        <input type="hidden" name="image_data" id="image_data_wajah" required>
                        <input type="hidden" name="face_descriptor" id="face_descriptor_wajah" required>
                        <canvas id="canvas_wajah" style="display:none;"></canvas>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Wajah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Mengatur tampilan kamera agar kotak sempurna di tengah */
        .video-container {
            width: 220px;
            height: 220px;
            background-color: #000;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 3px solid #eee;
            margin-bottom: 10px;
        }

        #webcam, #webcam_wajah {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Crop otomatis sisi landscape agar jadi kotak */
            transform: scaleX(-1);
            /* Efek Cermin saat live agar tidak bingung */
        }

        #prev-img, #prev-img_wajah {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/face-api.min.js') }}"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── ELEMEN DOM ─────────────────────────────────────────────
        const video              = document.getElementById('webcam');
        const canvas             = document.getElementById('canvas');
        const imageDataInput     = document.getElementById('image_data');
        const faceDescriptorInput= document.getElementById('face_descriptor');
        const prevImg            = document.getElementById('prev-img');
        const resultsDiv         = document.getElementById('results');
        const statusBadge        = document.getElementById('camera-status-text');

        let streamActive = null;
        let tinyLoaded = false;
        let modelsLoaded = false;
        let autoCaptureId = null;
        let autoCaptureIdWajah = null;

        // ── LOAD MODELS (fase 2 tahap) ─────────────────────────────
        (async function() {
            const MODEL_URL = window.location.origin + '/models';
            try {
                // Fase 1: Model ringan dulu
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                tinyLoaded = true;
                console.log("TinyFaceDetector Loaded");

                // Fase 2: Model berat di background
                Promise.all([
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]).then(() => { modelsLoaded = true; console.log("Semua model dimuat!"); });
            } catch(e) { console.error("Gagal load model:", e); }
        })();

        // ── MODAL TAMBAH SISWA ──────────────────────────────────────
        const modalTambah = document.getElementById('modalTambahSiswa');
        const faceGuide1  = document.getElementById('face-guide');
        const btnSnap1    = document.getElementById('btnSnap1');

        modalTambah.addEventListener('shown.bs.modal', function () {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return;
            statusBadge.className = 'badge bg-secondary px-4 py-2';
            statusBadge.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Mendeteksi wajah...';
            btnSnap1.disabled = true;

            navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
                streamActive = stream;
                video.srcObject = stream;
                video.play();

                // Reusable canvas — dibuat 1x saat kamera nyala
                const tmpC = document.createElement('canvas');
                let tmpCtx = null;

                video.addEventListener('loadeddata', function onData() {
                    tmpC.width = video.videoWidth;
                    tmpC.height = video.videoHeight;
                    tmpCtx = tmpC.getContext('2d');
                    video.removeEventListener('loadeddata', onData);
                });

                const detectLoop = async () => {
                    if (!tinyLoaded || prevImg.src.length > 100) {
                        autoCaptureId = requestAnimationFrame(detectLoop);
                        return;
                    }
                    if (!tmpCtx && video.videoWidth > 0) {
                        tmpC.width = video.videoWidth; tmpC.height = video.videoHeight;
                        tmpCtx = tmpC.getContext('2d');
                    }
                    if (!tmpCtx) { autoCaptureId = requestAnimationFrame(detectLoop); return; }

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
                                faceGuide1.style.borderColor = '#28a745';
                                btnSnap1.disabled = false;
                            } else if (ratio < 0.15) {
                                statusBadge.className = 'badge bg-warning text-dark px-4 py-2';
                                statusBadge.style.fontSize = '0.95rem';
                                statusBadge.innerHTML = '<i class="bx bx-arrow-back me-1"></i> Jarak terlalu jauh — Maju lebih dekat';
                                faceGuide1.style.borderColor = '#ffc107';
                                btnSnap1.disabled = true;
                            } else {
                                statusBadge.className = 'badge bg-warning text-dark px-4 py-2';
                                statusBadge.style.fontSize = '0.95rem';
                                statusBadge.innerHTML = '<i class="bx bx-arrow-from-left me-1"></i> Jarak terlalu dekat — Mundur sedikit';
                                faceGuide1.style.borderColor = '#ffc107';
                                btnSnap1.disabled = true;
                            }
                        } else {
                            statusBadge.className = 'badge bg-danger px-4 py-2';
                            statusBadge.style.fontSize = '0.95rem';
                            statusBadge.innerHTML = '<i class="bx bx-error-circle me-1"></i> Wajah tidak terdeteksi';
                            faceGuide1.style.borderColor = 'rgba(255,255,255,0.3)';
                            btnSnap1.disabled = true;
                        }
                    } catch(e) {}
                    autoCaptureId = requestAnimationFrame(detectLoop);
                };
                detectLoop();
            }).catch(err => Swal.fire('Error', 'Gagal akses kamera: ' + err.message, 'error'));
        });

        modalTambah.addEventListener('hidden.bs.modal', function () {
            cancelAnimationFrame(autoCaptureId);
            if (streamActive) { streamActive.getTracks().forEach(t => t.stop()); streamActive = null; }
            resultsDiv.style.display = 'none';
            prevImg.src = '';
            imageDataInput.value = '';
            faceDescriptorInput.value = '';
        });

        // ── FUNGSI JEPRET (modal tambah siswa) ────────────────────
        window.takeSnapshot = async function () {
            if (!modelsLoaded) { Swal.fire('Tunggu', 'Model AI sedang dimuat...', 'info'); return; }

            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const size = 400;
            canvas.width = size; canvas.height = size;
            const ctx = canvas.getContext('2d');
            ctx.translate(size, 0); ctx.scale(-1, 1);
            const vW = video.videoWidth, vH = video.videoHeight, aR = vW / vH;
            let sX, sY, sW, sH;
            if (aR > 1) { sH = vH; sW = vH; sX = (vW - vH) / 2; sY = 0; }
            else         { sW = vW; sH = vW; sX = 0; sY = (vH - vW) / 2; }
            ctx.drawImage(video, sX, sY, sW, sH, 0, 0, size, size);

            const detection = await faceapi
                .detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.3 }))
                .withFaceLandmarks().withFaceDescriptor();

            if (!detection) {
                Swal.fire({ icon: 'warning', title: 'Wajah Tidak Terdeteksi', text: 'Pastikan wajah terlihat jelas dan pencahayaan cukup.', confirmButtonColor: '#ff3e1d' });
                return;
            }
            const dataURL = canvas.toDataURL('image/jpeg', 0.9);
            imageDataInput.value = dataURL;
            faceDescriptorInput.value = JSON.stringify(Array.from(detection.descriptor));
            prevImg.src = dataURL;
            resultsDiv.style.display = 'block';
            Swal.fire({ icon: 'success', title: 'Wajah Terdeteksi!', text: 'Data wajah berhasil diambil.', timer: 1500, showConfirmButton: false });
        };

        // ── MODAL TAMBAH WAJAH ─────────────────────────────────────
        const videoWajah = document.getElementById('webcam_wajah');
        const canvasWajah = document.getElementById('canvas_wajah');
        const imageDataInputWajah = document.getElementById('image_data_wajah');
        const faceDescriptorInputWajah = document.getElementById('face_descriptor_wajah');
        const prevImgWajah = document.getElementById('prev-img_wajah');
        const resultsDivWajah = document.getElementById('results_wajah');
        const modalWajah = document.getElementById('modalTambahWajah');

        if (modalWajah) {
            const faceGuideW     = document.getElementById('face-guide_wajah');
            const btnSnapW       = document.getElementById('btnSnapW');
            const scanStatusTxtW = document.getElementById('camera-status-text_wajah');

            modalWajah.addEventListener('shown.bs.modal', function () {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return;
                scanStatusTxtW.className = 'badge bg-secondary px-4 py-2';
                scanStatusTxtW.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Mendeteksi wajah...';
                btnSnapW.disabled = true;

                navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
                    streamActive = stream;
                    videoWajah.srcObject = stream;
                    videoWajah.play();

                    const tmpCW = document.createElement('canvas');
                    let tmpCtxW = null;

                    const detectLoopW = async () => {
                        if (!tinyLoaded || prevImgWajah.src.length > 100) {
                            autoCaptureIdWajah = requestAnimationFrame(detectLoopW);
                            return;
                        }
                        if (!tmpCtxW && videoWajah.videoWidth > 0) {
                            tmpCW.width = videoWajah.videoWidth; tmpCW.height = videoWajah.videoHeight;
                            tmpCtxW = tmpCW.getContext('2d');
                        }
                        if (!tmpCtxW) { autoCaptureIdWajah = requestAnimationFrame(detectLoopW); return; }

                        tmpCtxW.setTransform(-1, 0, 0, 1, tmpCW.width, 0);
                        tmpCtxW.drawImage(videoWajah, 0, 0, tmpCW.width, tmpCW.height);
                        try {
                            const det = await faceapi.detectSingleFace(tmpCW, new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: 0.3 }));
                            if (det) {
                                const ratio = (det.box.width * det.box.height) / (tmpCW.width * tmpCW.height);
                                if (ratio > 0.15 && ratio < 0.60) {
                                    scanStatusTxtW.className = 'badge bg-success px-4 py-2';
                                    scanStatusTxtW.style.fontSize = '0.95rem';
                                    scanStatusTxtW.innerHTML = '<i class="bx bx-check-circle me-1"></i> Wajah Siap! Silakan Jepret';
                                    faceGuideW.style.borderColor = '#28a745';
                                    btnSnapW.disabled = false;
                                } else if (ratio < 0.15) {
                                    scanStatusTxtW.className = 'badge bg-warning text-dark px-4 py-2';
                                    scanStatusTxtW.style.fontSize = '0.95rem';
                                    scanStatusTxtW.innerHTML = '<i class="bx bx-arrow-back me-1"></i> Jarak terlalu jauh — Maju lebih dekat';
                                    faceGuideW.style.borderColor = '#ffc107';
                                    btnSnapW.disabled = true;
                                } else {
                                    scanStatusTxtW.className = 'badge bg-warning text-dark px-4 py-2';
                                    scanStatusTxtW.style.fontSize = '0.95rem';
                                    scanStatusTxtW.innerHTML = '<i class="bx bx-arrow-from-left me-1"></i> Jarak terlalu dekat — Mundur sedikit';
                                    faceGuideW.style.borderColor = '#ffc107';
                                    btnSnapW.disabled = true;
                                }
                            } else {
                                scanStatusTxtW.className = 'badge bg-danger px-4 py-2';
                                scanStatusTxtW.style.fontSize = '0.95rem';
                                scanStatusTxtW.innerHTML = '<i class="bx bx-error-circle me-1"></i> Wajah tidak terdeteksi';
                                faceGuideW.style.borderColor = 'rgba(255,255,255,0.3)';
                                btnSnapW.disabled = true;
                            }
                        } catch(e) {}
                        autoCaptureIdWajah = requestAnimationFrame(detectLoopW);
                    };
                    detectLoopW();
                }).catch(err => Swal.fire('Error', 'Gagal akses kamera: ' + err.message, 'error'));
            });

            modalWajah.addEventListener('hidden.bs.modal', function () {
                cancelAnimationFrame(autoCaptureIdWajah);
                if (streamActive) { streamActive.getTracks().forEach(t => t.stop()); streamActive = null; }
                resultsDivWajah.style.display = 'none';
                prevImgWajah.src = '';
                imageDataInputWajah.value = '';
                faceDescriptorInputWajah.value = '';
            });
        }

        // ── FUNGSI JEPRET MODAL TAMBAH SISWA ──────────────────────
        window.takeSnapshot = async function () {
            if (!modelsLoaded) { Swal.fire('Loading...', 'Model AI sedang dimuat', 'info'); return; }
            const ctx = canvas.getContext('2d');
            const size = 400;
            canvas.width = size; canvas.height = size;
            ctx.translate(size, 0); ctx.scale(-1, 1);
            const vW = video.videoWidth, vH = video.videoHeight;
            const aR = vW / vH;
            let sX, sY, sW, sH;
            if (aR > 1) { sH = vH; sW = vH; sX = (vW - vH) / 2; sY = 0; }
            else { sW = vW; sH = vW; sX = 0; sY = (vH - vW) / 2; }
            ctx.drawImage(video, sX, sY, sW, sH, 0, 0, size, size);

            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            const det = await faceapi.detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.3 })).withFaceLandmarks().withFaceDescriptor();
            if (!det) {
                Swal.fire({ icon: 'warning', title: 'Wajah Tidak Terdeteksi', text: 'Pastikan wajah terlihat jelas dan pencahayaan cukup.', confirmButtonColor: '#ff3e1d' });
                return;
            }
            const dataURL = canvas.toDataURL('image/jpeg', 0.9);
            imageDataInput.value = dataURL;
            faceDescriptorInput.value = JSON.stringify(Array.from(det.descriptor));
            prevImg.src = dataURL;
            resultsDiv.style.display = 'block';
            Swal.fire({ icon: 'success', title: 'Wajah Terdeteksi!', text: 'Silakan isi data lain dan simpan.', timer: 1500, showConfirmButton: false });
        };

        // ── FUNGSI JEPRET MODAL TAMBAH WAJAH ──────────────────────
        window.takeSnapshotWajah = async function () {
            if (!modelsLoaded) { Swal.fire('Loading...', 'Model AI sedang dimuat', 'info'); return; }
            const ctx = canvasWajah.getContext('2d');
            const size = 400;
            canvasWajah.width = size; canvasWajah.height = size;
            ctx.translate(size, 0); ctx.scale(-1, 1);
            const vW = videoWajah.videoWidth, vH = videoWajah.videoHeight;
            const aR = vW / vH;
            let sX, sY, sW, sH;
            if (aR > 1) { sH = vH; sW = vH; sX = (vW - vH) / 2; sY = 0; }
            else { sW = vW; sH = vW; sX = 0; sY = (vH - vW) / 2; }
            ctx.drawImage(videoWajah, sX, sY, sW, sH, 0, 0, size, size);

            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            const det = await faceapi.detectSingleFace(canvasWajah, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.3 })).withFaceLandmarks().withFaceDescriptor();
            if (!det) {
                Swal.fire({ icon: 'warning', title: 'Wajah Tidak Terdeteksi', text: 'Pastikan wajah terlihat jelas dan pencahayaan cukup.', confirmButtonColor: '#ff3e1d' });
                return;
            }
            const dataURL = canvasWajah.toDataURL('image/jpeg', 0.9);
            imageDataInputWajah.value = dataURL;
            faceDescriptorInputWajah.value = JSON.stringify(Array.from(det.descriptor));
            prevImgWajah.src = dataURL;
            resultsDivWajah.style.display = 'block';
            Swal.fire({ icon: 'success', title: 'Wajah Terdeteksi!', text: 'Data wajah siap disimpan.', timer: 1500, showConfirmButton: false });
        };

        // ── LISTENER EDIT & TAMBAH WAJAH ──────────────────────────
        document.addEventListener('click', function(e) {
            const btnWajah = e.target.closest('.btn-tambah-wajah');
            if (btnWajah) {
                document.getElementById('wajah_nama_siswa').textContent = btnWajah.dataset.nama;
                document.getElementById('formTambahWajah').action = `/siswa/${btnWajah.dataset.id}/wajah`;
            }
            const btnEdit = e.target.closest('.btn-edit-siswa');
            if (btnEdit) {
                const id = btnEdit.dataset.id;
                document.getElementById('edit_nis').value = btnEdit.dataset.nis;
                document.getElementById('edit_nama').value = btnEdit.dataset.nama;
                document.getElementById('edit_kelas').value = btnEdit.dataset.kelas;
                document.getElementById('edit_gender').value = btnEdit.dataset.gender;
                document.getElementById('formEditSiswa').action = `/siswa/${id}`;
            }
        });

        }); // END DOMContentLoaded
    </script>
@endpush