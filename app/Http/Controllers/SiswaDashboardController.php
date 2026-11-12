<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailAbsensi;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        // Get siswa data from logged-in user
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            // gracefully handle missing profile rather than crashing with 500
            $dummySiswa = (object)[
                'nama_siswa' => auth()->user()->username ?? 'Siswa',
                'id_siswa' => 0
            ];
            
            return view('siswa.dashboard', [
                'siswa' => $dummySiswa,
                'totalHadir' => 0,
                'totalIzin' => 0,
                'totalSakit' => 0,
                'totalAlpha' => 0,
                'totalTerlambat' => 0,
                'totalPertemuan' => 0,
                'persentaseKehadiran' => 0,
                'riwayatAbsensi' => collect(),
                'absensiHariIni' => null
            ])->with('error', 'Data profil Siswa Anda belum terhubung. Silakan hubungi Administrator.');
        }
        
        $idSiswa = $siswa->id_siswa;
        
        // Total kehadiran berdasarkan status
        $totalHadir = DetailAbsensi::where('id_siswa', $idSiswa)
            ->where('status', 'hadir')
            ->count();
            
        $totalIzin = DetailAbsensi::where('id_siswa', $idSiswa)
            ->where('status', 'izin')
            ->count();
            
        $totalSakit = DetailAbsensi::where('id_siswa', $idSiswa)
            ->where('status', 'sakit')
            ->count();
            
        $totalAlpha = DetailAbsensi::where('id_siswa', $idSiswa)
            ->where('status', 'alpha')
            ->count();
            
        $totalTerlambat = DetailAbsensi::where('id_siswa', $idSiswa)
            ->where('status', 'terlambat')
            ->count();
        
        // Total pertemuan (semua sesi yg diikutkan)
        $totalPertemuan = $totalHadir + $totalTerlambat + $totalIzin + $totalSakit + $totalAlpha;
        
        // Persentase kehadiran: hadir + terlambat = tetap dianggap masuk
        $persentaseKehadiran = $totalPertemuan > 0 
            ? round((($totalHadir + $totalTerlambat) / $totalPertemuan) * 100, 2) 
            : 0;
        
        // Riwayat absensi terbaru (5 terakhir)
        $riwayatAbsensi = DetailAbsensi::with('absensi.kelas')
            ->where('id_siswa', $idSiswa)
            ->latest()
            ->take(5)
            ->get();
        
        // Status absensi hari ini
        $absensiHariIni = DetailAbsensi::whereHas('absensi', function($q) {
            $q->whereDate('tanggal', now());
        })
        ->where('id_siswa', $idSiswa)
        ->first();

        // Jadwal Kelasku Mingguan
        $namaHariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][date('w')];
        $slots = \App\Models\JadwalSlot::orderBy('jam_ke')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jadwalMatrix = [];
        
        if ($siswa && $siswa->id_kelas && $slots->isNotEmpty()) {
            $jadwals = \App\Models\Jadwal::with(['slot', 'guru', 'mataPelajaran'])
                ->where('id_kelas', $siswa->id_kelas)
                ->get();
                
            foreach ($jadwals as $j) {
                $jadwalMatrix[ucfirst($j->hari)][$j->jadwal_slot_id] = $j;
            }
        }

        return view('siswa.dashboard', compact(
            'siswa',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpha',
            'totalTerlambat',
            'totalPertemuan',
            'persentaseKehadiran',
            'riwayatAbsensi',
            'absensiHariIni',
            'jadwalMatrix',
            'slots',
            'days',
            'namaHariIni'
        ));
    }
}
