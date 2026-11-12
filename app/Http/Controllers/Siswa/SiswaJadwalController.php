<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiswaJadwalController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        $slots = \App\Models\JadwalSlot::orderBy('jam_ke')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jadwalMatrix = [];
        $namaHariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][date('w')];
        
        if ($siswa && $siswa->id_kelas && $slots->isNotEmpty()) {
            $jadwals = \App\Models\Jadwal::with(['slot', 'guru', 'mataPelajaran'])
                ->where('id_kelas', $siswa->id_kelas)
                ->get();
                
            foreach ($jadwals as $j) {
                $jadwalMatrix[ucfirst($j->hari)][$j->jadwal_slot_id] = $j;
            }
        }

        return view('siswa.jadwal.index', compact(
            'siswa',
            'jadwalMatrix',
            'slots',
            'days',
            'namaHariIni'
        ));
    }
}
