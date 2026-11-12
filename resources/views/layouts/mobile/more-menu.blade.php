@php
  $user = auth()->user();
  $roleName = $user && $user->role ? strtolower($user->role->nama_role) : 'guest';
@endphp

{{-- Backdrop for Bottom Sheets --}}
<div class="m-sheet-backdrop" id="mSheetBackdrop" onclick="closeAllSheets()"></div>

{{-- ============================================================
     MORE MENU BOTTOM SHEET
     ============================================================ --}}
<div class="m-bottom-sheet" id="mMoreMenuSheet">
  <div class="m-sheet-handle"></div>
  <div class="m-sheet-header">
    <h2 class="m-sheet-title"><i class='bx bx-grid-alt me-1 text-primary'></i> Semua Menu & Fitur</h2>
    <button type="button" class="m-sheet-close" onclick="closeAllSheets()">
      <i class='bx bx-x'></i>
    </button>
  </div>
  
  <div class="m-sheet-body">
    {{-- SISWA LINKS --}}
    @if($roleName === 'siswa')
      <div class="m-menu-group-title">Akademik & Presensi</div>
      <div class="m-menu-list">
        <a href="{{ route('siswa.dashboard') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-home-circle'></i></div>
            <span class="m-menu-row-label">Dashboard Siswa</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('scan.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #696cff;"><i class='bx bx-qr-scan'></i></div>
            <span class="m-menu-row-label">Scanner Absensi Mandiri</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('siswa.jadwal.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-calendar-event'></i></div>
            <span class="m-menu-row-label">Jadwal Pelajaran</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('siswa.reports.my') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-radar'></i></div>
            <span class="m-menu-row-label">Laporan Sikap & Karakter</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
      </div>

      <div class="m-menu-group-title">Integritas & Bantuan</div>
      <div class="m-menu-list">
        <a href="{{ route('siswa.wallet.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #ffab00;"><i class='bx bx-wallet'></i></div>
            <span class="m-menu-row-label">Dompet & Marketplace Token</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('siswa.helpdesk.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #03c3ec;"><i class='bx bx-support'></i></div>
            <span class="m-menu-row-label">Helpdesk Terpadu</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('panduan.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-book-open'></i></div>
            <span class="m-menu-row-label">Panduan Aplikasi</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
      </div>

    {{-- GURU LINKS --}}
    @elseif($roleName === 'guru')
      <div class="m-menu-group-title">Jadwal & Presensi</div>
      <div class="m-menu-list">
        <a href="{{ route('guru.dashboard') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-home-circle'></i></div>
            <span class="m-menu-row-label">Dashboard Guru</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('guru.jadwal.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-calendar-week'></i></div>
            <span class="m-menu-row-label">Jadwal Mengajar</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('absensi.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-spreadsheet'></i></div>
            <span class="m-menu-row-label">Data Sesi Absensi</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('kehadiran.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #71dd37;"><i class='bx bx-list-check'></i></div>
            <span class="m-menu-row-label">Data Kehadiran</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
      </div>

      <div class="m-menu-group-title">Evaluasi & Monitoring</div>
      <div class="m-menu-list">
        <a href="{{ route('guru.assessments.siswa') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #ffab00;"><i class='bx bx-star'></i></div>
            <span class="m-menu-row-label">Penilaian Sikap Siswa</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('guru.token.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #696cff;"><i class='bx bx-scan'></i></div>
            <span class="m-menu-row-label">Validasi Token Siswa</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('guru.monitoring.siswa') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-pie-chart-alt'></i></div>
            <span class="m-menu-row-label">Pantau Karakter Kelas</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('guru.monitoring.recap') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-printer'></i></div>
            <span class="m-menu-row-label">Rekapitulasi Cetak</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('guru.reports.my') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-radar'></i></div>
            <span class="m-menu-row-label">Laporan Kinerjaku</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
      </div>

    {{-- ADMIN LINKS --}}
    @else
      <div class="m-menu-group-title">Data Master</div>
      <div class="m-menu-list">
        <a href="{{ route('jurusan.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-buildings'></i></div>
            <span class="m-menu-row-label">Data Jurusan</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('kelas.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-door-open'></i></div>
            <span class="m-menu-row-label">Data Kelas</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('guru.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-user-pin'></i></div>
            <span class="m-menu-row-label">Data Guru</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('mata-pelajaran.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-book-bookmark'></i></div>
            <span class="m-menu-row-label">Mata Pelajaran</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('siswa.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-user-badge'></i></div>
            <span class="m-menu-row-label">Data Siswa</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
      </div>

      <div class="m-menu-group-title">Akademik & Gamifikasi</div>
      <div class="m-menu-list">
        <a href="{{ route('admin.jadwal.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-calendar-week'></i></div>
            <span class="m-menu-row-label">Jadwal Pelajaran</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('admin.poin.leaderboard.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #ffab00;"><i class='bx bx-trophy'></i></div>
            <span class="m-menu-row-label">Leaderboard Sekolah</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('admin.poin.marketplace.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #696cff;"><i class='bx bx-store'></i></div>
            <span class="m-menu-row-label">Marketplace Token</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('settings.index') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon"><i class='bx bx-cog'></i></div>
            <span class="m-menu-row-label">Pengaturan Sistem</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
        <a href="{{ route('admin.helpdesk.dashboard') }}" class="m-menu-row">
          <div class="m-menu-row-left">
            <div class="m-menu-row-icon" style="color: #03c3ec;"><i class='bx bx-support'></i></div>
            <span class="m-menu-row-label">Helpdesk SLA</span>
          </div>
          <i class='bx bx-chevron-right text-muted'></i>
        </a>
      </div>
    @endif

    {{-- Universal links --}}
    <div class="m-menu-group-title">Sistem</div>
    <div class="m-menu-list">
      <a href="{{ route('panduan.index') }}" class="m-menu-row">
        <div class="m-menu-row-left">
          <div class="m-menu-row-icon"><i class='bx bx-book-open'></i></div>
          <span class="m-menu-row-label">Panduan Aplikasi</span>
        </div>
        <i class='bx bx-chevron-right text-muted'></i>
      </a>
      <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'desktop']) }}" class="m-menu-row" style="background: rgba(105, 108, 255, 0.06);">
        <div class="m-menu-row-left">
          <div class="m-menu-row-icon" style="color: #696cff;"><i class='bx bx-desktop'></i></div>
          <span class="m-menu-row-label text-primary">Ganti ke Tampilan Desktop</span>
        </div>
        <i class='bx bx-arrow-to-right text-primary'></i>
      </a>
    </div>
  </div>
</div>

{{-- ============================================================
     PROFILE & ACCOUNT BOTTOM SHEET
     ============================================================ --}}
<div class="m-bottom-sheet" id="mProfileSheet">
  <div class="m-sheet-handle"></div>
  <div class="m-sheet-header">
    <h2 class="m-sheet-title"><i class='bx bx-user me-1 text-primary'></i> Akun Saya</h2>
    <button type="button" class="m-sheet-close" onclick="closeAllSheets()">
      <i class='bx bx-x'></i>
    </button>
  </div>
  
  <div class="m-sheet-body">
    <div class="text-center py-3 mb-2">
      <img src="{{ $user->foto_profile ?? asset('assets/img/avatars/1.png') }}" alt="{{ $user->username ?? 'User' }}" class="rounded-circle shadow-sm mb-2" style="width: 72px; height: 72px; object-fit: cover; border: 3px solid #696cff;">
      <h5 class="mb-0 fw-bold">{{ $user->nama ?? $user->username }}</h5>
      <span class="badge bg-label-primary text-uppercase mt-1">{{ $user->role->nama_role ?? 'User' }}</span>
      <div class="text-muted small mt-1">{{ $user->email ?? '' }}</div>
    </div>

    <div class="m-menu-list">
      <a href="{{ route('profile.index') }}" class="m-menu-row">
        <div class="m-menu-row-left">
          <div class="m-menu-row-icon"><i class='bx bx-user-circle'></i></div>
          <span class="m-menu-row-label">Edit Profil & Password</span>
        </div>
        <i class='bx bx-chevron-right text-muted'></i>
      </a>
      
      <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'desktop']) }}" class="m-menu-row">
        <div class="m-menu-row-left">
          <div class="m-menu-row-icon"><i class='bx bx-desktop'></i></div>
          <span class="m-menu-row-label">Mode Tampilan Desktop</span>
        </div>
        <i class='bx bx-chevron-right text-muted'></i>
      </a>
      
      <form action="{{ route('logout') }}" method="POST" class="w-100 mt-2">
        @csrf
        <button type="submit" class="btn btn-danger w-100 py-2 d-flex align-items-center justify-content-center gap-2 rounded-pill shadow-sm" style="border: none;">
          <i class='bx bx-power-off'></i>
          <span class="fw-bold">Keluar / Logout</span>
        </button>
      </form>
    </div>
  </div>
</div>

<script>
function toggleMoreMenuSheet() {
  const sheet = document.getElementById('mMoreMenuSheet');
  const backdrop = document.getElementById('mSheetBackdrop');
  if (sheet.classList.contains('show')) {
    closeAllSheets();
  } else {
    closeAllSheets();
    backdrop.classList.add('show');
    sheet.classList.add('show');
    document.body.style.overflow = 'hidden';
  }
}

function toggleProfileSheet() {
  const sheet = document.getElementById('mProfileSheet');
  const backdrop = document.getElementById('mSheetBackdrop');
  if (sheet.classList.contains('show')) {
    closeAllSheets();
  } else {
    closeAllSheets();
    backdrop.classList.add('show');
    sheet.classList.add('show');
    document.body.style.overflow = 'hidden';
  }
}

function closeAllSheets() {
  const backdrop = document.getElementById('mSheetBackdrop');
  if (backdrop) backdrop.classList.remove('show');
  document.querySelectorAll('.m-bottom-sheet').forEach(s => s.classList.remove('show'));
  document.body.style.overflow = '';
}
</script>
