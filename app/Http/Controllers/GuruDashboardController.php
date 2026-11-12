<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\DetailAbsensi;
use App\Exports\GuruAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // Statistik personal guru
        // Total absensi yang dibuat oleh guru ini
        $totalAbsensi = Absensi::where('dibuat_oleh', $userId)->count();
        
        // Sesi absensi aktif yang dibuat guru ini
        $sesiAktif = Absensi::where('dibuat_oleh', $userId)
            ->where('status', 'aktif')
            ->count();
        
        // Statistik kehadiran hari ini dari absensi yang dibuat guru
        $hadirHariIni = DetailAbsensi::whereHas('absensi', function($q) use ($userId) {
            $q->where('dibuat_oleh', $userId)
              ->whereDate('tanggal', now());
        })->where('status', 'hadir')->count();
        
        $alpaHariIni = DetailAbsensi::whereHas('absensi', function($q) use ($userId) {
            $q->where('dibuat_oleh', $userId)
              ->whereDate('tanggal', now());
        })->where('status', 'alpha')->count();

        $terlambatHariIni = DetailAbsensi::whereHas('absensi', function($q) use ($userId) {
            $q->where('dibuat_oleh', $userId)
              ->whereDate('tanggal', now());
        })->where('status', 'terlambat')->count();
        
        // 5 Sesi absensi terbaru yang dibuat guru ini
        $sesiTerakhir = Absensi::with('kelas')
            ->where('dibuat_oleh', $userId)
            ->latest()
            ->take(5)
            ->get();

        $namaHariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][date('w')];
        $slots = \App\Models\JadwalSlot::orderBy('jam_ke')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jadwalMatrix = [];

        $namaGuru = auth()->user()->name;
        $guruProfile = \App\Models\Guru::where('id_user', $userId)->first();
        if ($guruProfile && $slots->isNotEmpty()) {
            $namaGuru = $guruProfile->nama;
            
            // Ambil jadwal mengajar full week
            $jadwals = \App\Models\Jadwal::with(['slot', 'kelas', 'mataPelajaran'])
                ->where('id_guru', $guruProfile->id_guru)
                ->get();
                
            foreach ($jadwals as $j) {
                $jadwalMatrix[ucfirst($j->hari)][$j->jadwal_slot_id] = $j;
            }
        }

        return view('guru.dashboard', compact(
            'totalAbsensi',
            'sesiAktif',
            'hadirHariIni',
            'alpaHariIni',
            'terlambatHariIni',
            'sesiTerakhir',
            'namaGuru',
            'jadwalMatrix',
            'slots',
            'days',
            'namaHariIni'
        ));
    }

    public function export(Request $request)
    {
        $userId = auth()->id();
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $exportType = $request->input('export_type', 'excel');

        $guruProfile = \App\Models\Guru::where('id_user', $userId)->first();
        $namaGuru = $guruProfile ? $guruProfile->nama : auth()->user()->name;

        $detailAbsensis = DetailAbsensi::with(['absensi.kelas', 'siswa'])
            ->whereHas('absensi', function($q) use ($userId, $startDate, $endDate) {
                $q->where('dibuat_oleh', $userId)
                  ->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->get()
            ->sortBy(function($detail) {
                // sort by date asc, then by siswa name
                return $detail->absensi->tanggal . $detail->siswa->nama_siswa;
            });

        if ($exportType === 'excel') {
            return Excel::download(new GuruAbsensiExport($detailAbsensis, $namaGuru, $startDate, $endDate), 'Laporan_Kelas_' . $namaGuru . '_' . $startDate . '_to_' . $endDate . '.xlsx');
        } elseif ($exportType === 'pdf') {
            $pdf = Pdf::loadView('exports.guru_report', ['detailAbsensis' => $detailAbsensis, 'guruName' => $namaGuru, 'startDate' => $startDate, 'endDate' => $endDate]);
            return $pdf->download('Laporan_Kelas_' . $namaGuru . '_' . $startDate . '_to_' . $endDate . '.pdf');
        }

        return back();
    }
}
