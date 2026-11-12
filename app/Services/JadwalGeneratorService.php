<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalSlot;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class JadwalGeneratorService
{
    /**
     * Generate Jadwal Acak untuk semua kelas.
     * Menggunakan algoritma pengacakan sederhana dengan dua aturan utama:
     * 1. No Clash: Satu guru tidak bisa mengajar dua kelas berbeda di waktu yang sama.
     * 2. Max Daily Load: Satu mata pelajaran tidak diajarkan lebih dari 3 jam per hari untuk satu kelas.
     */
    public function generate()
    {
        return DB::transaction(function () {
            // Hapus jadwal lama sepenuhnya
            Jadwal::query()->delete();

            // Ambil semua data master
            $kelas = Kelas::all();
            
            $gurus = Guru::with('mataPelajaran')
                ->whereNotNull('id_mapel')
                ->get();

            // Jika tidak ada sama sekali guru yang punya mata pelajaran, buatkan Dummy!
            if ($gurus->isEmpty()) {
                $dummyMapel = \App\Models\MataPelajaran::firstOrCreate(
                    ['nama_mapel' => 'Pelajaran Dummy (Auto-Generate)'],
                    ['kode_mapel' => 'DUMMY']
                );
                
                // Cari sembarang guru, atau buat guru baru
                $guru = Guru::first();
                if (!$guru) {
                    // Terpaksa harus ada minimal 1 user bertipe guru
                    $userGuru = \App\Models\User::whereHas('role', function($q) { $q->where('nama_role', 'guru'); })->first();
                    if ($userGuru) {
                        $guru = Guru::create([
                            'id_user' => $userGuru->id_user,
                            'nip' => '00000',
                            'nama' => $userGuru->name,
                            'jenis_kelamin' => 'L',
                            'id_mapel' => $dummyMapel->id_mapel
                        ]);
                    }
                } else {
                    $guru->update(['id_mapel' => $dummyMapel->id_mapel]);
                }
                
                $gurus = Guru::with('mataPelajaran')->whereNotNull('id_mapel')->get();
            }

            $slotsAll = JadwalSlot::all();
            
            // Auto-Seed Jika JadwalSlot masih kosong (Belum pernah di seed)
            if ($slotsAll->isEmpty()) {
                $defaultSlots = [
                    ['jam_ke' => 1, 'jam_mulai' => '07:00', 'jam_selesai' => '07:45', 'hari_berlaku' => 'semua', 'is_istirahat' => false],
                    ['jam_ke' => 2, 'jam_mulai' => '07:45', 'jam_selesai' => '08:30', 'hari_berlaku' => 'semua', 'is_istirahat' => false],
                    ['jam_ke' => 3, 'jam_mulai' => '08:30', 'jam_selesai' => '09:15', 'hari_berlaku' => 'semua', 'is_istirahat' => false],
                    ['jam_ke' => 4, 'jam_mulai' => '09:15', 'jam_selesai' => '10:00', 'hari_berlaku' => 'semua', 'is_istirahat' => false],
                    ['jam_ke' => 5, 'jam_mulai' => '10:00', 'jam_selesai' => '10:15', 'hari_berlaku' => 'semua', 'is_istirahat' => true],
                    ['jam_ke' => 6, 'jam_mulai' => '10:15', 'jam_selesai' => '11:00', 'hari_berlaku' => 'semua', 'is_istirahat' => false],
                    ['jam_ke' => 7, 'jam_mulai' => '11:00', 'jam_selesai' => '11:45', 'hari_berlaku' => 'senin-kamis', 'is_istirahat' => false],
                    ['jam_ke' => 8, 'jam_mulai' => '11:45', 'jam_selesai' => '12:30', 'hari_berlaku' => 'senin-kamis', 'is_istirahat' => false],
                    ['jam_ke' => 9, 'jam_mulai' => '12:30', 'jam_selesai' => '13:00', 'hari_berlaku' => 'senin-kamis', 'is_istirahat' => true],
                    ['jam_ke' => 10, 'jam_mulai' => '13:00', 'jam_selesai' => '13:45', 'hari_berlaku' => 'senin-kamis', 'is_istirahat' => false],
                    ['jam_ke' => 11, 'jam_mulai' => '13:45', 'jam_selesai' => '14:30', 'hari_berlaku' => 'senin-kamis', 'is_istirahat' => false],
                ];
                JadwalSlot::insert($defaultSlots);
                $slotsAll = JadwalSlot::all();
            }
            
            // Structure: $busyTeachers[$hari][$slotId] = array of $id_guru
            $busyTeachers = [];
            
            // Structure: $dailyLoad[$kelasId][$hari][$id_mapel] = integer (jumlah jam)
            $dailyLoad = [];

            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

            $generatedCount = 0;

            foreach ($kelas as $k) {
                foreach ($days as $hari) {
                    $dbType = ($hari === 'Jumat') ? 'jumat' : 'senin-kamis';
                    
                    // Filter slot untuk hari ini dan diurutkan berdasarkan jam_ke
                    $slots = $slotsAll->filter(function($slot) use ($dbType) {
                        return in_array($slot->hari_berlaku, [$dbType, 'semua']);
                    })->sortBy('jam_ke');

                    foreach ($slots as $slot) {
                        if ($slot->is_istirahat) {
                            continue; // Slot istirahat dilewati
                        }

                        // Filter guru
                        $availableGurus = $gurus->filter(function($g) use ($hari, $slot, &$busyTeachers, $k, &$dailyLoad) {
                            // 1. Cek Clash Guru
                            if (isset($busyTeachers[$hari][$slot->id]) && in_array($g->id_guru, $busyTeachers[$hari][$slot->id])) {
                                return false;
                            }
                            
                            // 2. Cek Overload Mapel di kelas yang sama pada hari yang sama (Maksimal 3 jam)
                            $currentLoad = isset($dailyLoad[$k->id_kelas][$hari][$g->id_mapel]) ? $dailyLoad[$k->id_kelas][$hari][$g->id_mapel] : 0;
                            if ($currentLoad >= 3) {
                                return false;
                            }

                            return true;
                        });

                        if ($availableGurus->count() > 0) {
                            $selectedGuru = $availableGurus->random();
                        } else {
                            // Jika guru sesuai filter habis/tabrakan, secara paksa ambil guru acak manapun (KAMI LOGGARKAN ATURAN UNTUK DEMO/DUMMY FULL JADWAL)
                            if ($gurus->count() > 0) {
                                $selectedGuru = $gurus->random();
                            }
                        }

                        if (isset($selectedGuru)) {
                            // Masukkan ke Database
                            Jadwal::create([
                                'hari'           => strtolower($hari),
                                'jadwal_slot_id' => $slot->id,
                                'id_guru'        => $selectedGuru->id_guru,
                                'id_kelas'       => $k->id_kelas,
                                'id_mapel'       => $selectedGuru->id_mapel
                            ]);

                            // Catat sebagai sibuk
                            $busyTeachers[$hari][$slot->id][] = $selectedGuru->id_guru;
                            
                            // Tambahkan load harian untuk mapel ini di kelas ini
                            if (!isset($dailyLoad[$k->id_kelas][$hari][$selectedGuru->id_mapel])) {
                                $dailyLoad[$k->id_kelas][$hari][$selectedGuru->id_mapel] = 0;
                            }
                            $dailyLoad[$k->id_kelas][$hari][$selectedGuru->id_mapel]++;

                            $generatedCount++;
                            unset($selectedGuru); // Bersihkan agar loop berikutnya butuh deteksi baru
                        }
                    }
                }
            }

            return $generatedCount;
        });
    }
}
