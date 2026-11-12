@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Akademik /</span> Jadwal Pelajaran</h4>

    <!-- Jadwal Kelasku Mingguan (Grid Sesuai Admin) -->
    <div class="row">
      <div class="col-12 mb-4">
        <div class="card h-100 border-info border-top border-3 border-0 shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between pb-2 bg-white">
            <h5 class="card-title m-0 fw-bold"><i class="bx bx-calendar text-info me-2"></i> Jadwal Kelasku Sebuku Mingguan</h5>
          </div>
          <div class="card-body p-0">
            @if(isset($slots) && $slots->count() > 0)
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover mb-0" style="min-width: 1000px;">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center text-white" style="width: 15%">WAKTU</th>
                            @foreach($days as $day)
                                <th class="text-center text-white {{ $day == $namaHariIni ? 'bg-info' : '' }}" style="width: 17%">
                                    {{ strtoupper($day) }}
                                    @if($day == $namaHariIni)<br><small class="badge bg-white text-info mt-1">HARI INI</small>@endif
                                </th>
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
                                <tr>
                                    <td class="text-center bg-label-primary fw-semibold align-middle">
                                        <div class="text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Jam ke-{{ $slot->jam_ke }}</div>
                                        <div class="badge bg-primary px-3">{{ $timeDisplay }}</div>
                                    </td>
                                    
                                    @foreach($days as $day)
                                        <td class="align-middle p-2" style="height: 90px; border-left: 1px solid #ebedf2;">
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
                                                    @php $j = $jadwalMatrix[$day][$slot->id]; @endphp
                                                    <div class="bg-label-info p-2 rounded h-100 border border-info border-dashed position-relative d-flex flex-column justify-content-center shadow-sm" style="border-style: dashed !important;">
                                                        <div class="fw-bold text-dark text-truncate mb-1" style="font-size: 0.85rem;" title="{{ $j->mataPelajaran->nama_mapel ?? 'Mapel Tidak Diketahui' }}">
                                                            {{ $j->mataPelajaran->nama_mapel ?? 'Mapel Tidak Diketahui' }}
                                                        </div>
                                                        <div class="text-muted d-flex align-items-center text-truncate mt-1" style="font-size: 0.75rem;">
                                                            <div class="avatar avatar-xs me-1">
                                                                <span class="avatar-initial rounded-circle bg-label-secondary"><i class="bx bx-user text-xs"></i></span>
                                                            </div>
                                                            <span>{{ $j->guru->nama ?? 'Guru Kosong' }}</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="bg-lighter p-2 rounded h-100 border border-secondary border-dashed position-relative d-flex align-items-center justify-content-center" style="border-style: dashed !important; opacity:0.6;">
                                                        <span class="text-muted text-xs"><i class="bx bx-sleep me-1"></i> Kosong</span>
                                                    </div>
                                                @endif
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
              <div class="alert alert-secondary text-center mb-0 p-5 mt-3 mx-3">
                <i class="bx bx-calendar-x fs-1 mb-2 text-muted"></i>
                <h6 class="mb-0 text-muted">Aduh! Belum ada block jam pelajaran sama sekali nih dari sekolah.</h6>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
