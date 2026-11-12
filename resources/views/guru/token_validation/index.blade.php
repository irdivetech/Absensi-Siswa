@extends('layouts.app')
@section('title', 'Validasi Token Siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <i class="bx bx-scan fs-1 d-block mb-2"></i>
                    <h4 class="mb-1 text-white fw-bold">Validasi Token Siswa</h4>
                    <p class="text-white-50 mb-0 small">Masukkan kode token yang diberikan siswa</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('guru.token.validate') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Kode Token <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="redemption_code"
                                class="form-control form-control-lg text-center fw-bold border-2"
                                placeholder="Contoh: WFH-A3X9K2"
                                maxlength="10"
                                style="font-size: 1.5rem; letter-spacing: 3px; text-transform: uppercase;"
                                required
                                autofocus
                                value="{{ old('redemption_code') }}"
                            >
                            <div class="form-text text-center mt-2">
                                <i class="bx bx-info-circle me-1"></i> Kode 8-10 karakter yang diaktifkan siswa dari wallet
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Catatan <span class="text-muted">(Opsional)</span></label>
                            <textarea
                                name="usage_note"
                                class="form-control border"
                                rows="2"
                                placeholder="Catatan tambahan untuk validasi ini..."
                            >{{ old('usage_note') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow" onclick="return confirm('Validasi token ini? Efek akan langsung diterapkan ke status absensi siswa.')">
                            <i class="bx bx-check-shield me-2 fs-5"></i> Validasi & Terapkan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bx bx-help-circle me-1"></i> Panduan Tipe Token</h6>
                    <div class="table-responsive">
                        <table class="table table-sm border mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Awalan</th>
                                    <th>Tipe</th>
                                    <th>Efek</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-success">WFH-xxx</span></td>
                                    <td>WFH / Remote</td>
                                    <td>Alpha → Hadir <strong>(semua sesi hari ini)</strong></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">IZN-xxx</span></td>
                                    <td>Izin Mendadak</td>
                                    <td>Alpha → Izin <strong>(sesi Anda saja)</strong></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning text-dark">TLT-xxx</span></td>
                                    <td>Toleransi Telat</td>
                                    <td>Hapus catatan terlambat <strong>(sesi Anda)</strong></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">CST-xxx</span></td>
                                    <td>Custom</td>
                                    <td>Dicatat sebagai penukaran reward</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
