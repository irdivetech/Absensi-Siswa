{{-- ============================================================
     MOBILE DASHBOARD FOR SISWA
     Modern App-Style Experience
     ============================================================ --}}

{{-- 1. Hero Card: Greeting & Status Absen Hari Ini --}}
<div class="m-hero-card">
  <div class="d-flex justify-content-between align-items-start mb-2">
    <div>
      <div class="m-brand-sub text-white-50 mb-1">
        <i class='bx bx-book-reader me-1'></i> {{ $siswa->kelas->nama_kelas ?? 'Siswa' }}
      </div>
      <h2 class="m-hero-user mb-0">{{ $siswa->nama_siswa }}</h2>
    </div>
    <div class="text-end">
      <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill shadow-sm">
        {{ $persentaseKehadiran }}% Hadir
      </span>
    </div>
  </div>

  {{-- Status Presensi Hari Ini Alert Box --}}
  <div class="p-3 mt-3 rounded-3" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px);">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        @if($absensiHariIni)
          <i class='bx bxs-check-circle fs-3 text-white'></i>
          <div>
            <div class="fw-bold small text-white">Presensi Hari Ini Terverifikasi</div>
            <div class="text-white-50" style="font-size: 0.72rem;">
              Status: <strong>{{ strtoupper($absensiHariIni->status) }}</strong>
              @if($absensiHariIni->waktu_scan)
                • {{ \Carbon\Carbon::parse($absensiHariIni->waktu_scan)->format('H:i') }} WIB
              @endif
            </div>
          </div>
        @else
          <i class='bx bx-time-five fs-3 text-warning'></i>
          <div>
            <div class="fw-bold small text-white">Belum Presensi Hari Ini</div>
            <div class="text-white-50" style="font-size: 0.72rem;">Silakan scan QR di kelas sekarang</div>
          </div>
        @endif
      </div>
      @if(!$absensiHariIni)
        <a href="{{ route('scan.index') }}" class="btn btn-sm btn-warning rounded-pill px-3 py-1 fw-bold text-dark shadow-sm">
          Scan
        </a>
      @endif
    </div>
  </div>
</div>

{{-- 2. Big Prominent Action: Scanner CTA --}}
<a href="{{ route('scan.index') }}" class="m-card text-decoration-none d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #2b3147 0%, #1a1f2c 100%); color: #ffffff; border: none; box-shadow: 0 8px 20px rgba(26, 31, 44, 0.25);">
  <div class="d-flex align-items-center gap-3">
    <div class="m-stat-icon" style="background: var(--m-primary-gradient); color: #fff;">
      <i class='bx bx-qr-scan fs-3'></i>
    </div>
    <div>
      <h6 class="mb-1 text-white fw-bold">Presensi Cepat QR</h6>
      <span class="text-white-50 small" style="font-size: 0.72rem;">Buka kamera untuk scan barcode absensi</span>
    </div>
  </div>
  <i class='bx bx-chevron-right text-white-50 fs-3'></i>
</a>

{{-- 3. Quick Action Grid --}}
<div class="m-action-grid">
  <a href="{{ route('scan.index') }}" class="m-action-item">
    <div class="m-action-icon" style="color: #696cff; background: #eef1ff;">
      <i class='bx bx-qr-scan'></i>
    </div>
    <span class="m-action-label">Scan QR</span>
  </a>

  <a href="{{ route('siswa.jadwal.index') }}" class="m-action-item">
    <div class="m-action-icon" style="color: #03c3ec; background: #e3f8ff;">
      <i class='bx bx-calendar-event'></i>
    </div>
    <span class="m-action-label">Jadwal</span>
  </a>

  <a href="{{ route('siswa.wallet.index') }}" class="m-action-item">
    <div class="m-action-icon" style="color: #ffab00; background: #fff8e6;">
      <i class='bx bx-wallet'></i>
    </div>
    <span class="m-action-label">Dompet</span>
  </a>

  <a href="{{ route('siswa.reports.my') }}" class="m-action-item">
    <div class="m-action-icon" style="color: #71dd37; background: #edfae5;">
      <i class='bx bx-radar'></i>
    </div>
    <span class="m-action-label">Laporan</span>
  </a>
</div>

{{-- 4. Statistik Kehadiran Grid --}}
<div class="d-flex align-items-center justify-content-between mb-2">
  <h6 class="fw-bold mb-0 text-dark small text-uppercase" style="letter-spacing: 0.5px;">Statistik Kehadiran</h6>
  <span class="text-muted small" style="font-size: 0.75rem;">Total: {{ $totalPertemuan }} Pertemuan</span>
</div>

<div class="m-stat-grid">
  <div class="m-stat-card">
    <div class="m-stat-icon success">
      <i class='bx bx-check-circle'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-success">{{ $totalHadir - $totalTerlambat }}</h3>
      <p class="m-stat-label">Hadir Tepat</p>
    </div>
  </div>

  <div class="m-stat-card">
    <div class="m-stat-icon warning">
      <i class='bx bx-time-five'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-warning">{{ $totalTerlambat }}</h3>
      <p class="m-stat-label">Terlambat</p>
    </div>
  </div>

  <div class="m-stat-card">
    <div class="m-stat-icon info">
      <i class='bx bx-envelope'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-info">{{ $totalIzin + $totalSakit }}</h3>
      <p class="m-stat-label">Izin / Sakit</p>
    </div>
  </div>

  <div class="m-stat-card">
    <div class="m-stat-icon danger">
      <i class='bx bx-user-x'></i>
    </div>
    <div>
      <h3 class="m-stat-value text-danger">{{ $totalAlpha }}</h3>
      <p class="m-stat-label">Alpha</p>
    </div>
  </div>
</div>

{{-- 5. Jadwal Hari Ini --}}
@php
  $jadwalHariIniList = [];
  if (isset($jadwalMatrix) && isset($namaHariIni) && isset($jadwalMatrix[$namaHariIni])) {
    $jadwalHariIniList = $jadwalMatrix[$namaHariIni];
  }
@endphp

@if(!empty($jadwalHariIniList) && count($jadwalHariIniList) > 0)
  <div class="m-card mb-3">
    <div class="m-card-header">
      <h6 class="m-card-title">
        <i class='bx bx-calendar text-primary'></i> Jadwal Hari Ini ({{ $namaHariIni }})
      </h6>
      <a href="{{ route('siswa.jadwal.index') }}" class="small text-primary fw-bold text-decoration-none">Lihat Semua</a>
    </div>

    <div class="d-flex flex-column gap-2">
      @foreach($jadwalHariIniList as $j)
        <div class="p-2 rounded-3 bg-light d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-label-primary px-2 py-1 rounded" style="font-size: 0.7rem;">
              {{ $j->slot->jam_mulai ?? '' }}
            </span>
            <div>
              <div class="fw-bold small text-dark">{{ $j->mataPelajaran->nama_mapel ?? 'Mapel' }}</div>
              <div class="text-muted" style="font-size: 0.7rem;">{{ $j->guru->nama ?? 'Guru' }}</div>
            </div>
          </div>
          <span class="badge bg-label-info text-capitalize" style="font-size: 0.65rem;">
            Jam ke-{{ $j->slot->jam_ke ?? '-' }}
          </span>
        </div>
      @endforeach
    </div>
  </div>
@endif

{{-- 6. Riwayat Kehadiran Terbaru (Touch-friendly Card List) --}}
<div class="m-card mb-4">
  <div class="m-card-header">
    <h6 class="m-card-title">
      <i class='bx bx-history text-primary'></i> Riwayat Presensi Terbaru
    </h6>
  </div>

  <div class="d-flex flex-column gap-2">
    @forelse($riwayatAbsensi as $detail)
      @php
        $badgeClass = match($detail->status) {
          'hadir' => 'bg-label-success',
          'izin' => 'bg-label-warning',
          'sakit' => 'bg-label-info',
          'alpha' => 'bg-label-danger',
          default => 'bg-label-secondary'
        };
        $iconClass = match($detail->status) {
          'hadir' => 'bx-check-circle text-success',
          'izin' => 'bx-envelope text-warning',
          'sakit' => 'bx-plus-medical text-info',
          'alpha' => 'bx-x-circle text-danger',
          default => 'bx-time text-secondary'
        };
      @endphp
      <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between" style="background: #fafbfc;">
        <div class="d-flex align-items-center gap-2">
          <i class='bx {{ $iconClass }} fs-4'></i>
          <div>
            <div class="fw-bold small text-dark">{{ $detail->absensi->nama_absensi ?? 'Sesi Absensi' }}</div>
            <div class="text-muted" style="font-size: 0.72rem;">
              {{ $detail->absensi->tanggal ? \Carbon\Carbon::parse($detail->absensi->tanggal)->format('d M Y') : 'N/A' }}
              @if($detail->waktu_scan)
                • Jam {{ \Carbon\Carbon::parse($detail->waktu_scan)->format('H:i') }}
              @endif
            </div>
          </div>
        </div>
        <span class="badge {{ $badgeClass }} text-uppercase fw-bold" style="font-size: 0.72rem;">
          {{ $detail->status }}
        </span>
      </div>
    @empty
      <div class="text-center py-4 text-muted small">
        <i class='bx bx-folder-open fs-2 d-block mb-1 text-muted'></i>
        Belum ada riwayat kehadiran tercatat.
      </div>
    @endforelse
  </div>
</div>
