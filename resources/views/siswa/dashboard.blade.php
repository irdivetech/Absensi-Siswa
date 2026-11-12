@extends('layouts.app')

@section('content')
  @if(!empty($isMobile) && $isMobile)
    @include('siswa.mobile-dashboard')
  @else
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-lg-8 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-7">
              <div class="card-body">
                <h5 class="card-title text-primary">Selamat Datang, {{ $siswa->nama_siswa }}! 📚</h5>
                <p class="mb-4">
                  Tingkat kehadiran Anda: <span class="fw-bold text-success">{{ $persentaseKehadiran }}%</span>
                </p>
                <a href="{{ route('scan.index') }}" class="btn btn-sm btn-outline-primary">Scan Absensi</a>
              </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                  alt="View Badge User">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <i class='bx bx-pie-chart-alt-2 text-info fs-3'></i>
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Pertemuan Kelas</span>
                <h3 class="card-title mb-2">{{ $totalPertemuan }} Sesi</h3>
                <small class="text-muted">Tingkat Kehadiran: {{ $persentaseKehadiran }}%</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-6 col-md-4 col-lg mb-4">
        <div class="card text-center h-100">
          <div class="card-body">
            <div class="avatar bg-label-success p-2 mb-2 mx-auto" style="width: 50px; height: 50px;">
              <i class='bx bx-check fs-3'></i>
            </div>
            <span class="d-block mb-1">Hadir Tepat</span>
            <h4 class="card-title text-nowrap mb-2 text-success">{{ $totalHadir - $totalTerlambat }}</h4>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg mb-4">
        <div class="card text-center h-100">
          <div class="card-body">
            <div class="avatar bg-label-secondary p-2 mb-2 mx-auto" style="width: 50px; height: 50px;">
              <i class='bx bx-time-five fs-3'></i>
            </div>
            <span class="d-block mb-1">Terlambat</span>
            <h4 class="card-title text-nowrap mb-2 text-secondary">{{ $totalTerlambat }}</h4>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg mb-4">
        <div class="card text-center h-100">
          <div class="card-body">
            <div class="avatar bg-label-warning p-2 mb-2 mx-auto" style="width: 50px; height: 50px;">
              <i class='bx bx-envelope fs-3'></i>
            </div>
            <span class="d-block mb-1">Izin</span>
            <h4 class="card-title text-nowrap mb-2 text-warning">{{ $totalIzin }}</h4>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg mb-4">
        <div class="card text-center h-100">
          <div class="card-body">
            <div class="avatar bg-label-info p-2 mb-2 mx-auto" style="width: 50px; height: 50px;">
              <i class='bx bx-plus-medical fs-3'></i>
            </div>
            <span class="d-block mb-1">Sakit</span>
            <h4 class="card-title text-nowrap mb-2 text-info">{{ $totalSakit }}</h4>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg mb-4">
        <div class="card text-center h-100">
          <div class="card-body">
            <div class="avatar bg-label-danger p-2 mb-2 mx-auto" style="width: 50px; height: 50px;">
              <i class='bx bx-x fs-3'></i>
            </div>
            <span class="d-block mb-1">Alpha</span>
            <h4 class="card-title text-nowrap mb-2 text-danger">{{ $totalAlpha }}</h4>
          </div>
        </div>
      </div>
    </div>

    @if($absensiHariIni)
    <div class="row">
      <div class="col-12 mb-4">
        <div class="alert alert-success d-flex align-items-center" role="alert">
          <i class='bx bx-check-circle fs-4 me-2'></i>
          <div>
            <strong>Status Hari Ini:</strong> {{ ucfirst($absensiHariIni->status) }} 
            @if($absensiHariIni->waktu_scan)
              pada {{ \Carbon\Carbon::parse($absensiHariIni->waktu_scan)->format('H:i') }}
            @endif
          </div>
        </div>
      </div>
    </div>
    @endif



    <div class="row">
      <div class="col-md-12 mb-4">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0">Riwayat Kehadiran Saya</h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive text-nowrap">
              <table class="table">
                <thead>
                  <tr>
                    <th>Nama Absensi</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Waktu Scan</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($riwayatAbsensi as $detail)
                    <tr>
                      <td><strong>{{ $detail->absensi->nama_absensi ?? 'N/A' }}</strong></td>
                      <td>{{ $detail->absensi->kelas->nama_kelas ?? 'N/A' }}</td>
                      <td>{{ $detail->absensi->tanggal ? \Carbon\Carbon::parse($detail->absensi->tanggal)->format('d M Y') : 'N/A' }}</td>
                      <td>
                        @php
                          $badgeClass = match($detail->status) {
                            'hadir' => 'bg-label-success',
                            'izin' => 'bg-label-warning',
                            'sakit' => 'bg-label-info',
                            'alpha' => 'bg-label-danger',
                            default => 'bg-label-secondary'
                          };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                          {{ strtoupper($detail->status) }}
                        </span>
                      </td>
                      <td>
                        {{ $detail->waktu_scan ? \Carbon\Carbon::parse($detail->waktu_scan)->format('H:i') : '-' }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center p-3">Belum ada riwayat kehadiran.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
@endsection
