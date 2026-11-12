<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuruJadwalController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $namaGuru = auth()->user()->name;
        $guruProfile = \App\Models\Guru::where('id_user', $userId)->first();
        
        $slots = \App\Models\JadwalSlot::orderBy('jam_ke')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jadwalMatrix = [];
        $namaHariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][date('w')];

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

        return view('guru.jadwal.index', compact(
            'namaGuru',
            'jadwalMatrix',
            'slots',
            'days',
            'namaHariIni'
        ));
    }
}
