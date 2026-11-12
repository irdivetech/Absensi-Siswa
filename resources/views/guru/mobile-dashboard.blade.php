{{-- ============================================================
     MOBILE DASHBOARD FOR GURU
     Modern App-Style Experience
     ============================================================ --}}

{{-- 1. Hero Card --}}
<div class="m-hero-card">
  <div class="d-flex justify-content-between align-items-start mb-2">
    <div>
      <div class="m-brand-sub text-white-50 mb-1">
        <i class='bx bx-briefcase-alt-2 me-1'></i> Dashboard Pengajar
      </div>
      <h2 class="m-hero-user mb-0">{{ $namaGuru }}</h2>
    </div>
    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill shadow-sm">
      {{ $sesiAktif }} Sesi Aktif
    </span>
  </div>

  <div class="p-3 mt-3 rounded-3" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px);">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <div class="fw-bold small text-white">Monitoring Presensi Hari Ini</div>
        <div class="text-white-50" style="font-size: 0.72rem;">{{ $hadirHariIni }} siswa hadir terpantau real-time</div>
      </div>
      <a href="{{ route('kehadiran.index') }}" class="btn btn-sm btn-light rounded-pill px-3 py-1 fw-bold text-primary shadow-sm">
        Buka
      </a>
    </div>
  </div>
</div>

{{-- 2. Primary CTA: Kelola Absensi Kelas --}}
<a href="{{ route('absensi.index') }}" class="m-card text-decoration-none d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #1b2838 0%, #101826 100%); color: #ffffff; border: none; box-shadow: 0 8px 20px rgba(16, 24, 38, 0.25);">
  <div class="d-flex align-items-center gap-3">
    <div class="m-stat-icon" style="background: var(--m-primary-gradient); color: #fff;">
      <i class='bx bx-broadcast fs-3'></i>
    </div>
    <div>
      <h6 class="mb-1 text-white fw-bold">Kelola Sesi Absensi</h6>
      <span class="text-white-50 small" style="font-size: 0.72rem;">Buka QR Code presensi untuk kelas hari ini</span>
    </div>
  </div>
  <i class='bx bx-chevron-right text-white-50 fs-3'></i>
</a>

{{-- 3. Quick Action Grid --}}
<div class="m-action-grid">
  <a href="{{ route('absensi.index') }}" class="m-action-item">
    <div class="m-action-icon" style="color: #696cff; background: #eef1ff;">
      <i class='bx bx-spreadsheet'></i>
    </div>
    <span class="m-action-label">Absensi</span>
  </a>

  <a href="{{ route('kehadiran.index') }}" class="m-action-item">
    <div class="m-action-icon" style="color: #71dd37; background: #edfae5;">
      <i class='bx bx-check-shield'></i>
    </div>
    <span class="m-action-label">Kehadiran</span>
  </a>

  <a href="{{ route('guru.jadwal.index') }}" class="m-action-item">
    <div class="m-action-icon" style="color: #03c3ec; background: #e3f8ff;">
      <i class='bx bx-calendar-week'></i>
    </div>
    <span class="m-action-label">Jadwal</span>
  </a>

  <a href="{{ route('guru.token.index') }}" class="m-action-item">
    <div class="m-action-icon" style="color: #ffab00; background: #fff8e6;">
      <i class='bx bx-scan'></i>
    </div>
    <span class="m-action-label">Token</span>
  </a>
</div>

{{-- 4. Kehadiran Hari Ini Stats --}}
<div class="d-flex align-items-center justify-content-between mb-2">
  <h6 class="fw-bold mb-0 text-dark small text-uppercase" style="letter-spacing: 0.5px;">Rekap Presensi Hari Ini</h6>
  <span class="text-muted small" style="font-size: 0.75rem;">Hari Ini</span>
</div>

<div class="m-stat-grid">
  <div class="m-stat-card">
    <div class="m-stat-icon success">
      <i class='bx bx-check-shield'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-success">{{ $hadirHariIni }}</h3>
      <p class="m-stat-label">Hadir</p>
    </div>
  </div>

  <div class="m-stat-card">
    <div class="m-stat-icon warning">
      <i class='bx bx-time-five'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-warning">{{ $terlambatHariIni }}</h3>
      <p class="m-stat-label">Terlambat</p>
    </div>
  </div>

  <div class="m-stat-card">
    <div class="m-stat-icon danger">
      <i class='bx bx-user-x'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-danger">{{ $alpaHariIni }}</h3>
      <p class="m-stat-label">Alpa</p>
    </div>
  </div>

  <div class="m-stat-card">
    <div class="m-stat-icon info">
      <i class='bx bx-folder'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-info">{{ $totalAbsensi }}</h3>
      <p class="m-stat-label">Total Sesi</p>
    </div>
  </div>
</div>

{{-- 5. Export Button Accordion --}}
<div class="m-card mb-3">
  <div class="d-flex align-items-center justify-content-between" onclick="document.getElementById('mExportGuruForm').classList.toggle('d-none')" style="cursor: pointer;">
    <div class="d-flex align-items-center gap-2">
      <i class='bx bx-printer fs-4 text-primary'></i>
      <div>
        <h6 class="mb-0 fw-bold small text-dark">Cetak Laporan Kehadiran</h6>
        <span class="text-muted" style="font-size: 0.72rem;">Export data presensi ke Excel / PDF</span>
      </div>
    </div>
    <i class='bx bx-chevron-down text-muted fs-4'></i>
  </div>

  <div id="mExportGuruForm" class="d-none mt-3 pt-3 border-top">
    <form action="{{ route('guru.dashboard.export') }}" method="GET">
      <div class="row g-2 mb-2">
        <div class="col-6">
          <label class="form-label small text-muted mb-1">Mulai</label>
          <input type="date" class="form-control form-control-sm" name="start_date" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="col-6">
          <label class="form-label small text-muted mb-1">Sampai</label>
          <input type="date" class="form-control form-control-sm" name="end_date" value="{{ date('Y-m-d') }}" required>
        </div>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" name="export_type" value="excel" class="btn btn-sm btn-success flex-grow-1 rounded-pill">
          <i class="bx bx-spreadsheet me-1"></i> Excel
        </button>
        <button type="submit" name="export_type" value="pdf" class="btn btn-sm btn-danger flex-grow-1 rounded-pill">
          <i class="bx bxs-file-pdf me-1"></i> PDF
        </button>
      </div>
    </form>
  </div>
</div>

{{-- 6. Riwayat Sesi Absensi Terakhir --}}
<div class="m-card mb-4">
  <div class="m-card-header">
    <h6 class="m-card-title">
      <i class='bx bx-history text-primary'></i> Sesi Absensi Terakhir
    </h6>
    <a href="{{ route('absensi.index') }}" class="small text-primary fw-bold text-decoration-none">Kelola</a>
  </div>

  <div class="d-flex flex-column gap-2">
    @forelse($sesiTerakhir as $sesi)
      <a href="{{ route('absensi.show', $sesi->id_absensi) }}" class="p-3 rounded-3 border d-flex align-items-center justify-content-between text-decoration-none" style="background: #fafbfc;">
        <div class="d-flex align-items-center gap-2">
          <div class="avatar avatar-sm rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(105, 108, 255, 0.1); color: #696cff;">
            <i class='bx bx-broadcast'></i>
          </div>
          <div>
            <div class="fw-bold small text-dark">{{ $sesi->nama_absensi ?? 'N/A' }}</div>
            <div class="text-muted" style="font-size: 0.72rem;">
              {{ $sesi->kelas->nama_kelas ?? 'Kelas' }} • {{ $sesi->tanggal ? \Carbon\Carbon::parse($sesi->tanggal)->format('d M Y') : 'N/A' }}
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center gap-1">
          <span class="badge {{ $sesi->status == 'aktif' ? 'bg-label-success' : 'bg-label-secondary' }} text-uppercase" style="font-size: 0.65rem;">
            {{ $sesi->status }}
          </span>
          <i class='bx bx-chevron-right text-muted fs-4'></i>
        </div>
      </a>
    @empty
      <div class="text-center py-4 text-muted small">
        <i class='bx bx-folder-open fs-2 d-block mb-1 text-muted'></i>
        Belum ada riwayat sesi absensi.
      </div>
    @endforelse
  </div>
</div>
