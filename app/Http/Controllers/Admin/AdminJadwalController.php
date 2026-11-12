<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalSlot;
use App\Models\Kelas;
use App\Services\JadwalGeneratorService;
use Illuminate\Http\Request;

class AdminJadwalController extends Controller
{
    /**
     * Tampilkan halaman kelola jadwal pelajaran berdasarkan kelas
     */
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $selectedKelasId = $request->id_kelas ?? ($kelas->first() ? $kelas->first()->id_kelas : null);
        
        $slots = JadwalSlot::orderBy('jam_ke')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        
        // Ambil jadwal kelas yang dipilih dan susun format: $jadwalMatrix[$hari][$slotId] = objek Jadwal
        $jadwalMatrix = [];
        if ($selectedKelasId) {
            $jadwals = Jadwal::with(['guru.user', 'mataPelajaran', 'slot'])
                ->where('id_kelas', $selectedKelasId)
                ->get();
                
            foreach ($jadwals as $j) {
                $jadwalMatrix[ucfirst($j->hari)][$j->jadwal_slot_id] = $j;
            }
        }
        
        // Data guru untuk dropdown edit manual
        $gurus = Guru::with('mataPelajaran')->whereNotNull('id_mapel')->get();

        return view('admin.jadwal.index', compact('kelas', 'selectedKelasId', 'slots', 'days', 'jadwalMatrix', 'gurus'));
    }

    /**
     * Jalankan generator jadwal acak
     */
    public function generate(Request $request)
    {
        try {
            $service = new JadwalGeneratorService();
            $count = $service->generate();
            
            return redirect()->route('admin.jadwal.index')
                ->with('success', "Berhasil me-generate {$count} slot jadwal baru untuk seluruh kelas secara acak!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Gagal generate jadwal: " . $e->getMessage());
        }
    }

    /**
     * Update/Tumpuk manual (Edit satu slot)
     */
    public function updateSlot(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jadwal_slot_id' => 'required|exists:jadwal_slots,id',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'nullable|exists:guru,id_guru', // Nullable jika ingin dikosongkan
        ]);

        try {
            // Jika dikosongkan
            if (!$request->id_guru) {
                Jadwal::where('hari', $request->hari)
                      ->where('jadwal_slot_id', $request->jadwal_slot_id)
                      ->where('id_kelas', $request->id_kelas)
                      ->delete();
                return redirect()->back()->with('success', 'Jadwal di slot tersebut berhasil dikosongkan.');
            }

            $guru = Guru::findOrFail($request->id_guru);

            // Validasi apakah guru ini sudah mengajar di kelas lain pada slot dan hari yang SAMA
            $clash = Jadwal::where('hari', $request->hari)
                           ->where('jadwal_slot_id', $request->jadwal_slot_id)
                           ->where('id_guru', $request->id_guru)
                           ->where('id_kelas', '!=', $request->id_kelas)
                           ->first();

            if ($clash) {
                $namaKelasSelesi = Kelas::find($clash->id_kelas)->nama_kelas ?? 'Lain';
                return redirect()->back()->with('error', "Gagal! Guru {$guru->nama} sudah dijadwalkan mengajar di kelas {$namaKelasSelesi} pada jam tersebut.");
            }

            // Update atau Create (Upsert)
            Jadwal::updateOrCreate(
                [
                    'hari' => $request->hari,
                    'jadwal_slot_id' => $request->jadwal_slot_id,
                    'id_kelas' => $request->id_kelas,
                ],
                [
                    'id_guru' => $guru->id_guru,
                    'id_mapel' => $guru->id_mapel,
                ]
            );

            return redirect()->back()->with('success', 'Jadwal berhasil diperbarui secara manual.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
