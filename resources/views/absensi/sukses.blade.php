@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg text-center p-5 border-0">
                <div class="card-body">
                    @php
                        $isLate = request('late') == '1';
                    @endphp

                    <div class="mb-4">
                        <div class="avatar avatar-xl mx-auto {{ $isLate ? 'bg-label-secondary' : 'bg-label-success' }} rounded-circle p-3" style="width: 100px; height: 100px;">
                            <i class='bx {{ $isLate ? 'bx-time-five' : 'bx-check' }} fw-bold' style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    
                    @if($isLate)
                        <h2 class="card-title text-secondary fw-bold mb-2">Terlambat!</h2>
                        <p class="text-muted mb-4">Anda berhasil absen, namun tercatat <span class="fw-bold">Terlambat</span>.</p>
                        <div class="alert alert-secondary d-inline-block px-5">
                    @else
                        <h2 class="card-title text-success fw-bold mb-2">Absensi Berhasil!</h2>
                        <p class="text-muted mb-4">Terima kasih, kehadiran Anda telah tercatat tepat waktu.</p>
                        <div class="alert alert-success d-inline-block px-5">
                    @endif
                        <span class="fw-bold">Waktu Tercatat</span><br>
                        <span class="fs-4">{{ now()->format('H:i') }} WIB</span>
                        <br>
                        <small>{{ now()->translatedFormat('l, d F Y') }}</small>
                    </div>

                    <div class="mt-5">
                        @php
                            $dashboardRoute = route('login');
                            if(auth()->check()) {
                                if(auth()->user()->isSiswa()) $dashboardRoute = route('siswa.dashboard');
                                elseif(auth()->user()->isGuru()) $dashboardRoute = route('guru.dashboard');
                                elseif(auth()->user()->isAdmin()) $dashboardRoute = route('dashboard');
                            }
                        @endphp
                        <a href="{{ $dashboardRoute }}" class="btn btn-primary btn-lg px-5">
                            <i class="bx bx-home me-2"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection