<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\FlexibilityItem;
use App\Models\UserToken;
use App\Models\DetailAbsensi;
use App\Services\PointService;
use Illuminate\Http\Request;

class SiswaWalletController extends Controller
{
    /**
     * Dashboard dompet (3 Tab: Mutasi, Marketplace, My Inventory)
     */
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        $pointBalance = $siswa->getPointBalance();
        $levelInfo = $siswa->getIntegrityLevel();
        
        $mutations = $siswa->pointLedgers()->with(['absensi', 'guru'])->latest()->get();
        
        // Marketplace items
        $items = FlexibilityItem::active()->get()->map(function($item) use ($siswa) {
            $item->available_for_me = $item->isAvailableForSiswa($siswa->id_siswa);
            return $item;
        });

        // Inventory Tokens
        $inventory = $siswa->userTokens()->with(['item', 'absensiUsed', 'validatedByGuru'])->latest()->get();

        // Cari sesi absensi yg masih aktif dan siswa status-nya "alpha", untuk token BEBAS_ALPHA
        $activeAlphaSessions = DetailAbsensi::with('absensi')
            ->where('id_siswa', $siswa->id_siswa)
            ->where('status', 'alpha')
            ->whereHas('absensi', function($q) {
                $q->where('status', 'aktif');
            })->get();

        // Cek apakah ada token WFH / Izin yang divalidasi hari ini
        $activeDailyToken = UserToken::with('item')
            ->where('id_siswa', $siswa->id_siswa)
            ->where('status', 'USED')
            ->whereDate('validated_at', now()->toDateString())
            ->whereHas('item', function($q) {
                $q->whereIn('item_type', ['WFH', 'IZIN_MENDADAK']);
            })->first();

        return view('siswa.wallet.index', compact('pointBalance', 'levelInfo', 'mutations', 'items', 'inventory', 'activeAlphaSessions', 'activeDailyToken'));
    }

    /**
     * Beli token dari marketplace
     */
    public function buyItem(Request $request, $id_item)
    {
        try {
            $item = FlexibilityItem::findOrFail($id_item);
            (new PointService())->spend(auth()->user()->siswa->id_siswa, $item);

            return redirect()->back()->with('success', "Berhasil menukar poin dengan token {$item->item_name}!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Gunakan token — alur berbeda per tipe:
     * - BEBAS_ALPHA → langsung efek (alur lama)
     * - Tipe lain → generate kode, status PENDING, tunggu validasi guru
     */
    public function useToken(Request $request, $id_token)
    {
        try {
            $siswa = auth()->user()->siswa;
            $token = UserToken::with('item')
                ->where('id_siswa', $siswa->id_siswa)
                ->findOrFail($id_token);
            
            $service = new PointService();

            if ($token->item->item_type === 'BEBAS_ALPHA') {
                // Alur lama: langsung efek
                $request->validate([
                    'id_absensi' => 'nullable|exists:absensi,id_absensi',
                ]);

                if (!$request->id_absensi) {
                    return redirect()->back()->with('error', 'Silakan pilih sesi absensi untuk menggunakan token Bebas Alpha.');
                }
                $service->useBebasAlphaToken($id_token, $siswa->id_siswa, $request->id_absensi);
                $msg = "Token Bebas Alpha berhasil digunakan! Status kamu di sesi ini telah diubah menjadi Hadir (Izin Token).";
            } else {
                // Alur baru: generate kode → PENDING → tunggu validasi guru
                $activatedToken = $service->activateToken($id_token, $siswa->id_siswa);
                $msg = "Token berhasil diaktifkan! Kode Anda: {$activatedToken->redemption_code}. Berikan kode ini ke guru untuk divalidasi.";
            }

            return redirect()->back()->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
