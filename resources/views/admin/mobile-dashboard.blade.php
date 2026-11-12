{{-- ============================================================
     MOBILE DASHBOARD FOR ADMIN
     Modern App-Style Experience
     ============================================================ --}}

{{-- 1. Hero Card --}}
<div class="m-hero-card">
  <div class="d-flex justify-content-between align-items-start mb-2">
    <div>
      <div class="m-brand-sub text-white-50 mb-1">
        <i class='bx bx-shield-quarter me-1'></i> Administrator Sekolah
      </div>
      <h2 class="m-hero-user mb-0">{{ Auth::user()->nama ?? 'Admin' }}</h2>
    </div>
    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill shadow-sm">
      {{ $sesiAktif }} Sesi Aktif
    </span>
  </div>

  <div class="p-3 mt-3 rounded-3" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px);">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <div class="fw-bold small text-white">Monitoring Global Hari Ini</div>
        <div class="text-white-50" style="font-size: 0.72rem;">{{ $hadirHariIni }} Siswa Hadir • {{ $statAlpa }} Alpa</div>
      </div>
      <a href="{{ route('admin.monitoring.siswa') }}" class="btn btn-sm btn-light rounded-pill px-3 py-1 fw-bold text-primary shadow-sm">
        Pantau
      </a>
    </div>
  </div>
</div>

{{-- 2. Master Data Quick Numbers (Horizontal Scroll / Grid) --}}
<div class="d-flex align-items-center justify-content-between mb-2">
  <h6 class="fw-bold mb-0 text-dark small text-uppercase" style="letter-spacing: 0.5px;">Data Master Sekolah</h6>
  <span class="text-muted small" style="font-size: 0.75rem;">Total Siswa: {{ $totalSiswa }}</span>
</div>

<div class="m-scroll-x mb-3">
  <a href="{{ route('siswa.index') }}" class="m-chip-btn">
    <i class='bx bx-user-badge text-primary'></i>
    <span><strong>{{ $totalSiswa }}</strong> Siswa</span>
  </a>
  <a href="{{ route('guru.index') }}" class="m-chip-btn">
    <i class='bx bx-user-pin text-info'></i>
    <span><strong>{{ $totalGuru }}</strong> Guru</span>
  </a>
  <a href="{{ route('kelas.index') }}" class="m-chip-btn">
    <i class='bx bx-door-open text-warning'></i>
    <span><strong>{{ $totalKelas }}</strong> Kelas</span>
  </a>
  <a href="{{ route('jurusan.index') }}" class="m-chip-btn">
    <i class='bx bx-buildings text-danger'></i>
    <span><strong>{{ $totalJurusan }}</strong> Jurusan</span>
  </a>
  <a href="{{ route('mata-pelajaran.index') }}" class="m-chip-btn">
    <i class='bx bx-book-open text-success'></i>
    <span><strong>{{ $totalMapel }}</strong> Mapel</span>
  </a>
</div>

{{-- 3. Quick Action Grid (8 features) --}}
<div class="m-card p-3 mb-3">
  <h6 class="fw-bold mb-3 small text-muted text-uppercase" style="letter-spacing: 0.5px;">Aksi Cepat Admin</h6>
  <div class="m-action-grid mb-0">
    <a href="{{ route('siswa.index') }}" class="m-action-item">
      <div class="m-action-icon" style="color: #696cff; background: #eef1ff;">
        <i class='bx bx-user-badge'></i>
      </div>
      <span class="m-action-label">Siswa</span>
    </a>

    <a href="{{ route('guru.index') }}" class="m-action-item">
      <div class="m-action-icon" style="color: #03c3ec; background: #e3f8ff;">
        <i class='bx bx-user-pin'></i>
      </div>
      <span class="m-action-label">Guru</span>
    </a>

    <a href="{{ route('admin.jadwal.index') }}" class="m-action-item">
      <div class="m-action-icon" style="color: #71dd37; background: #edfae5;">
        <i class='bx bx-calendar-week'></i>
      </div>
      <span class="m-action-label">Jadwal</span>
    </a>

    <a href="{{ route('admin.poin.leaderboard.index') }}" class="m-action-item">
      <div class="m-action-icon" style="color: #ffab00; background: #fff8e6;">
        <i class='bx bx-trophy'></i>
      </div>
      <span class="m-action-label">Rank</span>
    </a>

    <a href="{{ route('admin.monitoring.siswa') }}" class="m-action-item">
      <div class="m-action-icon" style="color: #696cff; background: #f0f2ff;">
        <i class='bx bx-pie-chart-alt-2'></i>
      </div>
      <span class="m-action-label">Monitoring</span>
    </a>

    <a href="{{ route('admin.poin.marketplace.index') }}" class="m-action-item">
      <div class="m-action-icon" style="color: #e83e8c; background: #fdecf2;">
        <i class='bx bx-store'></i>
      </div>
      <span class="m-action-label">Market</span>
    </a>

    <a href="{{ route('admin.helpdesk.dashboard') }}" class="m-action-item">
      <div class="m-action-icon" style="color: #20c997; background: #e8f9f5;">
        <i class='bx bx-support'></i>
      </div>
      <span class="m-action-label">Helpdesk</span>
    </a>

    <a href="javascript:void(0);" onclick="toggleMoreMenuSheet()" class="m-action-item">
      <div class="m-action-icon" style="color: #6c757d; background: #f1f3f5;">
        <i class='bx bx-grid-alt'></i>
      </div>
      <span class="m-action-label">Lainnya</span>
    </a>
  </div>
</div>

{{-- 4. Kehadiran Siswa Hari Ini Grid --}}
<div class="d-flex align-items-center justify-content-between mb-2">
  <h6 class="fw-bold mb-0 text-dark small text-uppercase" style="letter-spacing: 0.5px;">Presensi Hari Ini</h6>
  <span class="badge bg-label-info">{{ $sesiAktif }} Sesi Berjalan</span>
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
      <h3 class="m-stat-value text-danger">{{ $statAlpa }}</h3>
      <p class="m-stat-label">Alpa</p>
    </div>
  </div>

  <div class="m-stat-card">
    <div class="m-stat-icon info">
      <i class='bx bx-broadcast'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-info">{{ $sesiAktif }}</h3>
      <p class="m-stat-label">Sesi Aktif</p>
    </div>
  </div>
</div>

{{-- 5. Export Kehadiran Accordion --}}
<div class="m-card mb-3">
  <div class="d-flex align-items-center justify-content-between" onclick="document.getElementById('mExportAdminForm').classList.toggle('d-none')" style="cursor: pointer;">
    <div class="d-flex align-items-center gap-2">
      <i class='bx bx-printer fs-4 text-primary'></i>
      <div>
        <h6 class="mb-0 fw-bold small text-dark">Cetak Laporan Global</h6>
        <span class="text-muted" style="font-size: 0.72rem;">Export rekapitulasi kehadiran Excel / PDF</span>
      </div>
    </div>
    <i class='bx bx-chevron-down text-muted fs-4'></i>
  </div>

  <div id="mExportAdminForm" class="d-none mt-3 pt-3 border-top">
    <form action="{{ route('dashboard.export') }}" method="GET">
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

{{-- 6. Sesi Absensi Terakhir --}}
<div class="m-card mb-4">
  <div class="m-card-header">
    <h6 class="m-card-title">
      <i class='bx bx-history text-primary'></i> Sesi Absensi Terakhir
    </h6>
    <a href="{{ route('kehadiran.index') }}" class="small text-primary fw-bold text-decoration-none">Lihat Semua</a>
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
              {{ $sesi->kelas->nama_kelas ?? 'Kelas' }}
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
        Belum ada sesi absensi terbaru.
      </div>
    @endforelse
  </div>
</div>
