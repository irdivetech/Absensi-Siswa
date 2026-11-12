@php
  $user = auth()->user();
  $roleName = $user && $user->role ? strtolower($user->role->nama_role) : 'guest';
@endphp

<nav class="m-bottom-nav">
  {{-- ============================================================
       SISWA BOTTOM NAVIGATION
       ============================================================ --}}
  @if($roleName === 'siswa')
    {{-- 1. Home --}}
    <a href="{{ route('siswa.dashboard') }}" class="m-nav-item {{ Request::is('siswa/dashboard') ? 'active' : '' }}">
      <i class='bx bx-home-alt-2 m-nav-icon'></i>
      <span class="m-nav-label">Home</span>
    </a>

    {{-- 2. Jadwal --}}
    <a href="{{ route('siswa.jadwal.index') }}" class="m-nav-item {{ Request::is('siswa/jadwal*') ? 'active' : '' }}">
      <i class='bx bx-calendar-event m-nav-icon'></i>
      <span class="m-nav-label">Jadwal</span>
    </a>

    {{-- 3. Center FAB: Scan QR --}}
    <div class="m-nav-fab-wrapper">
      <a href="{{ route('scan.index') }}" class="m-nav-fab pulse-anim {{ Request::is('scan*') ? 'active' : '' }}" title="Scan Absensi">
        <i class='bx bx-qr-scan'></i>
      </a>
      <span class="m-nav-fab-label">Scan</span>
    </div>

    {{-- 4. Dompet --}}
    <a href="{{ route('siswa.wallet.index') }}" class="m-nav-item {{ Request::is('dompet*') ? 'active' : '' }}">
      <i class='bx bx-wallet m-nav-icon'></i>
      <span class="m-nav-label">Dompet</span>
    </a>

    {{-- 5. More Menu Sheet --}}
    <a href="javascript:void(0);" onclick="toggleMoreMenuSheet()" class="m-nav-item {{ Request::is('siswa/laporanku*') || Request::is('siswa/helpdesk*') || Request::is('panduan*') || Request::is('profile*') ? 'active' : '' }}">
      <i class='bx bx-grid-alt m-nav-icon'></i>
      <span class="m-nav-label">Menu</span>
    </a>

  {{-- ============================================================
       GURU BOTTOM NAVIGATION
       ============================================================ --}}
  @elseif($roleName === 'guru')
    {{-- 1. Home --}}
    <a href="{{ route('guru.dashboard') }}" class="m-nav-item {{ Request::is('guru/dashboard') ? 'active' : '' }}">
      <i class='bx bx-home-alt-2 m-nav-icon'></i>
      <span class="m-nav-label">Home</span>
    </a>

    {{-- 2. Jadwal Mengajar --}}
    <a href="{{ route('guru.jadwal.index') }}" class="m-nav-item {{ Request::is('guru/jadwal*') ? 'active' : '' }}">
      <i class='bx bx-calendar-week m-nav-icon'></i>
      <span class="m-nav-label">Jadwal</span>
    </a>

    {{-- 3. Center FAB: Kehadiran Siswa --}}
    <div class="m-nav-fab-wrapper">
      <a href="{{ route('kehadiran.index') }}" class="m-nav-fab pulse-anim {{ Request::is('kehadiran*') ? 'active' : '' }}" title="Kelola Kehadiran">
        <i class='bx bx-check-shield'></i>
      </a>
      <span class="m-nav-fab-label">Kehadiran</span>
    </div>

    {{-- 4. Pantau Siswa --}}
    <a href="{{ route('guru.monitoring.siswa') }}" class="m-nav-item {{ Request::is('monitoring-kelas*') ? 'active' : '' }}">
      <i class='bx bx-pie-chart-alt-2 m-nav-icon'></i>
      <span class="m-nav-label">Pantau</span>
    </a>

    {{-- 5. More Menu Sheet --}}
    <a href="javascript:void(0);" onclick="toggleMoreMenuSheet()" class="m-nav-item {{ Request::is('absensi*') || Request::is('penilaian-siswa*') || Request::is('validasi-token*') || Request::is('guru/laporanku*') || Request::is('panduan*') ? 'active' : '' }}">
      <i class='bx bx-grid-alt m-nav-icon'></i>
      <span class="m-nav-label">Menu</span>
    </a>

  {{-- ============================================================
       ADMIN BOTTOM NAVIGATION
       ============================================================ --}}
  @else
    {{-- 1. Home --}}
    <a href="{{ route('dashboard') }}" class="m-nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
      <i class='bx bx-home-alt-2 m-nav-icon'></i>
      <span class="m-nav-label">Home</span>
    </a>

    {{-- 2. Monitoring --}}
    <a href="{{ route('admin.monitoring.siswa') }}" class="m-nav-item {{ Request::is('monitoring*') ? 'active' : '' }}">
      <i class='bx bx-bar-chart-alt-2 m-nav-icon'></i>
      <span class="m-nav-label">Monitor</span>
    </a>

    {{-- 3. Center FAB: Quick Actions Sheet --}}
    <div class="m-nav-fab-wrapper">
      <a href="javascript:void(0);" onclick="toggleMoreMenuSheet()" class="m-nav-fab pulse-anim" title="Semua Menu & Fitur">
        <i class='bx bx-plus'></i>
      </a>
      <span class="m-nav-fab-label">Aksi</span>
    </div>

    {{-- 4. Pengguna --}}
    <a href="{{ route('users.index') }}" class="m-nav-item {{ Request::is('users*') ? 'active' : '' }}">
      <i class='bx bx-group m-nav-icon'></i>
      <span class="m-nav-label">Users</span>
    </a>

    {{-- 5. More Menu Sheet --}}
    <a href="javascript:void(0);" onclick="toggleMoreMenuSheet()" class="m-nav-item {{ Request::is('jurusan*') || Request::is('kelas*') || Request::is('data-guru*') || Request::is('siswa*') || Request::is('settings*') || Request::is('helpdesk*') ? 'active' : '' }}">
      <i class='bx bx-grid-alt m-nav-icon'></i>
      <span class="m-nav-label">Menu</span>
    </a>
  @endif
</nav>
