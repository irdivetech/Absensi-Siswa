@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Helpdesk /</span> Dashboard Analitik</h4>

    <div class="row">
        <!-- SLA: Response Time -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Avg Response Time</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $avgResponseTime ? round($avgResponseTime) : 0 }}</h4>
                                <small class="text-primary">Menit</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-primary rounded p-2">
                                <i class='bx bx-time-five bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLA: Resolution Time -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Avg Resolution Time</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $avgResolutionTime ? round($avgResolutionTime) : 0 }}</h4>
                                <small class="text-success">Menit</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded p-2">
                                <i class='bx bx-check-double bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tiket Aktif -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Tiket Open/Proses</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ ($ticketStats['open'] ?? 0) + ($ticketStats['in_progress'] ?? 0) }}</h4>
                                <small class="text-warning">Tiket</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-warning rounded p-2">
                                <i class='bx bx-envelope-open bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Rating -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Tingkat Kepuasan</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</h4>
                                <small class="text-info">/ 5.0</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-info rounded p-2">
                                <i class='bx bx-star bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Kategori -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sebaran Kategori Kendala</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($categoryStats as $stat)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $stat->category }}
                            <span class="badge bg-primary rounded-pill">{{ $stat->total }} Laporan</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center">Belum ada data.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
