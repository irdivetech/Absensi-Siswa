@extends('layouts.app')
@section('title', 'Manajemen Jadwal Pelajaran')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0">
                <i class="bx bx-calendar-week me-2 text-primary"></i> 
                Manajemen Jadwal Kelas
            </h4>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <form action="{{ route('admin.jadwal.generate') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning fw-bold shadow-sm" onclick="return confirm('PERINGATAN!\n\nTindakan ini akan mereset dan menghancurkan seluruh data jadwal yang lama, lalu menggantinya dengan jadwal acak yang baru. \n\nYakin lanjutkan?')">
                    <i class="bx bx-bolt-circle fs-5 me-1"></i> Generate Jadwal Acak
                </button>
            </form>
        </div>
    </div>

    <!-- Pilihan Kelas -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body py-3 d-flex align-items-center">
            <h6 class="mb-0 me-3"><i class="bx bx-filter-alt me-1"></i> Filter Kelas:</h6>
            <form action="{{ route('admin.jadwal.index') }}" method="GET" class="d-flex" id="filterForm">
                <select name="id_kelas" class="form-select border-primary fw-semibold" onchange="document.getElementById('filterForm').submit()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ $selectedKelasId == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Tabel Jadwal -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            @if($selectedKelasId)
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered table-hover mb-0" style="min-width: 1000px;">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center text-white" style="width: 15%">WAKTU</th>
                                @foreach($days as $day)
                                    <th class="text-center text-white" style="width: 17%">{{ strtoupper($day) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slots as $slot)
                                @php
                                    $isBreak = $slot->is_istirahat;
                                    $timeDisplay = substr($slot->jam_mulai, 0, 5) . ' - ' . substr($slot->jam_selesai, 0, 5);
                                @endphp
                                
                                @if($isBreak)
                                    <!-- Baris Istirahat -->
                                    <tr class="bg-lighter">
                                        <td class="text-center fw-bold bg-label-secondary">
                                            <div>Istirahat</div>
                                            <small class="text-muted text-xs">{{ $timeDisplay }}</small>
                                        </td>
                                        <td colspan="5" class="text-center fw-bold text-muted bg-label-secondary p-3">
                                            <i class="bx bx-coffee me-1"></i> WAKTU ISTIRAHAT
                                        </td>
                                    </tr>
                                @else
                                    <!-- Baris Pelajaran -->
                                    <tr>
                                        <td class="text-center bg-label-primary fw-semibold align-middle">
                                            <div class="text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Jam ke-{{ $slot->jam_ke }}</div>
                                            <div class="badge bg-primary px-3">{{ $timeDisplay }}</div>
                                        </td>
                                        
                                        @foreach($days as $day)
                                            <td class="align-middle position-relative p-2" style="height: 90px; border-left: 1px solid #ebedf2;">
                                                
                                                {{-- Verifikasi apakah slot ini applicable unuk day ini (khusus jumat slot terbatas) --}}
                                                @php
                                                    $dbType = ($day === 'Jumat') ? 'jumat' : 'senin-kamis';
                                                    $isApplicable = in_array($slot->hari_berlaku, [$dbType, 'semua']);
                                                @endphp

                                                @if(!$isApplicable)
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-lighter rounded" style="opacity: 0.5;">
                                                        <i class="bx bx-x fs-4 text-muted"></i>
                                                    </div>
                                                @else
                                                    @if(isset($jadwalMatrix[$day][$slot->id]))
                                                        @php
                                                            $j = $jadwalMatrix[$day][$slot->id];
                                                        @endphp
                                                        <div class="bg-label-info p-2 rounded h-100 border border-info border-dashed position-relative d-flex flex-column justify-content-center shadow-sm" style="border-style: dashed !important;">
                                                            <div class="fw-bold text-dark text-truncate mb-1" style="font-size: 0.85rem;" title="{{ $j->mataPelajaran->nama_mapel ?? 'Mapel Tidak Diketahui' }}">
                                                                {{ $j->mataPelajaran->nama_mapel ?? 'Mapel Tidak Diketahui' }}
                                                            </div>
                                                            <div class="text-muted d-flex align-items-center text-truncate" style="font-size: 0.75rem;" title="{{ $j->guru->nama ?? 'Guru Dihapus' }}">
                                                                <i class="bx bx-user me-1"></i> {{ $j->guru->nama ?? 'Guru Dihapus' }}
                                                            </div>
                                                            
                                                            <!-- Edit Button Overlay -->
                                                            <button class="btn btn-icon btn-sm btn-primary position-absolute top-0 end-0 m-1 opacity-75 hover-opacity-100" style="width: 20px; height: 20px;" data-bs-toggle="modal" data-bs-target="#editModal{{$day}}{{$slot->id}}" title="Ubah Guru / Kosongkan">
                                                                <i class="bx bx-edit text-xs"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="bg-lighter p-2 rounded h-100 border border-secondary border-dashed position-relative d-flex align-items-center justify-content-center" style="border-style: dashed !important;">
                                                            <span class="text-muted text-xs"><i class="bx bx-sleep me-1"></i> Kosong</span>
                                                            
                                                            <!-- Edit Button Overlay -->
                                                            <button class="btn btn-icon btn-sm btn-secondary position-absolute top-0 end-0 m-1 opacity-50 hover-opacity-100" style="width: 20px; height: 20px;" data-bs-toggle="modal" data-bs-target="#editModal{{$day}}{{$slot->id}}" title="Isi Guru">
                                                                <i class="bx bx-plus text-xs"></i>
                                                            </button>
                                                        </div>
                                                    @endif

                                                    <!-- Modal Edit Slot -->
                                                    <div class="modal fade" id="editModal{{$day}}{{$slot->id}}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <form action="{{ route('admin.jadwal.update') }}" method="POST" class="modal-content">
                                                                @csrf
                                                                <input type="hidden" name="hari" value="{{ $day }}">
                                                                <input type="hidden" name="jadwal_slot_id" value="{{ $slot->id }}">
                                                                <input type="hidden" name="id_kelas" value="{{ $selectedKelasId }}">
                                                                
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title text-white">Revisi Manual Guru</h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-muted d-block uppercase text-xs fw-bold">Slot Jadwal</label>
                                                                        <h6 class="m-0 fw-bold">{{ $day }}, Jam ke-{{ $slot->jam_ke }} ({{ $timeDisplay }})</h6>
                                                                    </div>
                                                                    
                                                                    <hr class="my-3">
                                                                    
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Pilih Guru Pengajar</label>
                                                                        <select name="id_guru" class="form-select border-primary" required>
                                                                            <option value="">-- Kosongkan Slot Ini --</option>
                                                                            @foreach($gurus as $g)
                                                                                @php
                                                                                    $isSelected = isset($jadwalMatrix[$day][$slot->id]) && $jadwalMatrix[$day][$slot->id]->id_guru == $g->id_guru;
                                                                                @endphp
                                                                                <option value="{{ $g->id_guru }}" {{ $isSelected ? 'selected' : '' }}>
                                                                                    {{ $g->nama }} — ({{ $g->mataPelajaran->nama_mapel ?? 'Mapel Kosong' }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="form-text text-warning mt-2">
                                                                            <i class="bx bx-info-circle"></i> Sistem akan menolak jika guru tersebut mengajar kelas lain di jam yang persis sama.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-primary fw-bold"><i class="bx bx-save me-1"></i> Simpan Pilihan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif

                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bx bx-buildings text-muted opacity-50 mb-3" style="font-size: 4rem;"></i>
                    <h5 class="text-muted fw-normal">Silakan pilih kelas terlebih dahulu melalui filter di atas.</h5>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hover-opacity-100 { opacity: 0; transition: opacity 0.2s; }
    td:hover .hover-opacity-100 { opacity: 1; }
    .border-dashed { border-style: dashed !important; }
</style>
@endsection
