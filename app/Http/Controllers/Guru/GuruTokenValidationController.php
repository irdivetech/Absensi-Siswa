<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Services\PointService;
use Illuminate\Http\Request;

class GuruTokenValidationController extends Controller
{
    /**
     * Halaman validasi token siswa (input kode)
     */
    public function index()
    {
        return view('guru.token_validation.index');
    }

    /**
     * Proses validasi kode token
     */
    public function validateToken(Request $request)
    {
        $request->validate([
            'redemption_code' => 'required|string|max:10',
            'usage_note'      => 'nullable|string|max:500',
        ]);

        try {
            $guru = auth()->user()->guru;
            if (!$guru) {
                return redirect()->back()->with('error', 'Akun Anda tidak terhubung dengan data guru.');
            }

            $service = new PointService();
            $result = $service->validateTokenByGuru(
                strtoupper(trim($request->redemption_code)),
                $guru->id_guru,
                $request->usage_note ?? ''
            );

            $token = $result['token'];
            $msg = "Token \"{$token->item->item_name}\" milik {$token->siswa->nama_siswa} berhasil divalidasi! {$result['effect_message']}";

            return redirect()->back()->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
