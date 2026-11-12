@php
  $user = auth()->user();
  $roleName = $user && $user->role ? strtolower($user->role->nama_role) : 'guest';
@endphp

<header class="m-app-header">
  <a href="{{ $roleName === 'siswa' ? route('siswa.dashboard') : ($roleName === 'guru' ? route('guru.dashboard') : route('dashboard')) }}" class="m-header-brand">
    <div class="m-brand-logo">
      <i class='bx bx-qr-scan'></i>
    </div>
    <div class="m-brand-info">
      <h1 class="m-brand-title">Q-Absen</h1>
      <span class="m-brand-sub">
        <span class="m-status-dot"></span>
        {{ ucfirst($roleName) }}
      </span>
    </div>
  </a>

  <div class="m-header-actions">
    {{-- Search Trigger button --}}
    <button type="button" class="m-header-btn" id="mSearchBtn" onclick="toggleMobileSearch()" title="Cari data">
      <i class='bx bx-search'></i>
    </button>

    {{-- Switcher to Desktop view --}}
    <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'desktop']) }}" class="m-header-btn" title="Ganti ke Tampilan Desktop">
      <i class='bx bx-desktop'></i>
    </a>

    {{-- User Avatar / Profile Menu Trigger --}}
    <button type="button" class="m-header-btn p-0 border-0" onclick="toggleProfileSheet()" title="Menu Akun">
      <img src="{{ $user->foto_profile ?? asset('assets/img/avatars/1.png') }}" alt="{{ $user->username ?? 'User' }}" class="m-header-avatar">
    </button>
  </div>
</header>

{{-- Mobile Search Overlay (Expandable) --}}
<div id="mSearchOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 1048; background: #ffffff; padding: calc(var(--m-safe-top, 0px) + 12px) 16px 14px 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
  <div class="d-flex align-items-center gap-2">
    <div class="input-group input-group-merge shadow-none flex-grow-1" style="background: #f5f6fa; border-radius: 12px; padding: 4px 8px;">
      <span class="input-group-text border-0 bg-transparent py-0"><i class="bx bx-search text-muted"></i></span>
      <input type="text" id="mSearchInput" class="form-control border-0 bg-transparent shadow-none" placeholder="Cari siswa, guru, kelas..." autocomplete="off">
    </div>
    <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle" onclick="toggleMobileSearch()">
      <i class="bx bx-x fs-4"></i>
    </button>
  </div>
  <div id="mSearchResults" style="max-height: 60vh; overflow-y: auto; margin-top: 10px;"></div>
</div>

<script>
function toggleMobileSearch() {
  const overlay = document.getElementById('mSearchOverlay');
  if (overlay.style.display === 'none' || !overlay.style.display) {
    overlay.style.display = 'block';
    setTimeout(() => document.getElementById('mSearchInput').focus(), 100);
  } else {
    overlay.style.display = 'none';
  }
}

// Live search for mobile
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('mSearchInput');
  const results = document.getElementById('mSearchResults');
  let timer;

  if (input) {
    input.addEventListener('input', function() {
      clearTimeout(timer);
      const q = this.value.trim();
      if (q.length < 2) {
        results.innerHTML = '';
        return;
      }
      results.innerHTML = '<div class="text-center py-3 text-muted"><i class="bx bx-loader-alt bx-spin"></i> Mencari...</div>';
      timer = setTimeout(() => {
        fetch("{{ route('search') }}?q=" + encodeURIComponent(q), {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
          if (!data || data.length === 0) {
            results.innerHTML = '<div class="text-center py-3 text-muted small">Tidak ada hasil ditemukan</div>';
            return;
          }
          let html = '<div class="list-group list-group-flush">';
          data.forEach(item => {
            html += `<a href="${item.url}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-2 px-1 border-0">
              <i class="bx ${item.icon} text-primary fs-5"></i>
              <div class="flex-grow-1 overflow-hidden">
                <div class="fw-semibold text-truncate small">${item.title}</div>
                <div class="text-muted text-truncate" style="font-size: 0.72rem;">${item.subtitle}</div>
              </div>
            </a>`;
          });
          html += '</div>';
          results.innerHTML = html;
        })
        .catch(() => {
          results.innerHTML = '<div class="text-center py-2 text-danger small">Gagal mencari</div>';
        });
      }, 300);
    });
  }
});
</script>
