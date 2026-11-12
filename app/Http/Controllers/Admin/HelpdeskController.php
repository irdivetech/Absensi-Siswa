<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HelpdeskController extends Controller
{
    public function dashboard()
    {
        // 1. Rata-rata response time (dalam menit)
        $avgResponseTime = Ticket::whereNotNull('first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_time')
            ->value('avg_time');

        // 2. Rata-rata resolution time (dalam menit)
        $avgResolutionTime = Ticket::whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) as avg_time')
            ->value('avg_time');

        // 3. Statistik status tiket
        $ticketStats = Ticket::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();

        // 4. Distribusi kategori
        $categoryStats = Ticket::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        // 5. Rata-rata Rating
        $avgRating = \App\Models\SatisfactionRating::avg('score');

        return view('admin.helpdesk.dashboard', compact(
            'avgResponseTime', 'avgResolutionTime', 'ticketStats', 'categoryStats', 'avgRating'
        ));
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $category = $request->input('category');

        $tickets = Ticket::with('reporter')
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($category, function($query) use ($category) {
                return $query->where('category', $category);
            })
            ->orderBy('needs_admin', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.helpdesk.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with('responses.responder', 'reporter', 'rating')->findOrFail($id);
        
        // Auto Reply Suggestions berdasarkan Kategori
        $suggestions = $this->getAutoReplySuggestions($ticket->category);

        $allTickets = Ticket::with('reporter')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.helpdesk.show', compact('ticket', 'suggestions', 'allTickets'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'message' => 'required|string',
        ]);

        // SLA Tracking: Update first_response_at jika masih null
        if (is_null($ticket->first_response_at)) {
            $ticket->first_response_at = now();
            // Otomatis assign admin ini
            $ticket->operator_id = Auth::user()->id_user; 
            
            // Ubah status ke in_progress jika masih open
            if ($ticket->status === 'open') {
                $ticket->status = 'in_progress';
            }
        }
        
        // Hilangkan flag needs_admin karena Admin sudah mengambil alih
        $ticket->needs_admin = false;
        $ticket->save();

        $response = TicketResponse::create([
            'ticket_id' => $ticket->id,
            'responder_id' => Auth::user()->id_user,
            'message' => $request->message,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $response->id,
                    'message' => $response->message,
                    'created_at' => $response->created_at->format('d M Y, H:i'),
                    'responder_id' => $response->responder_id,
                    'responder_name' => 'Anda (Admin)',
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Balasan terkirim.');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $ticket->status = $request->status;

        if ($request->status === 'closed' && is_null($ticket->resolved_at)) {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status tiket berhasil diubah menjadi ' . $ticket->status . '.'
            ]);
        }

        return redirect()->back()->with('success', 'Status tiket berhasil diubah.');
    }

    private function getAutoReplySuggestions($category)
    {
        $suggestions = [
            'QR Error' => [
                'Silakan coba turunkan kecerahan layar HP Anda lalu scan ulang.',
                'Pastikan QR Code yang Anda scan belum expired (reload halaman QR).',
            ],
            'Face Recognition Error' => [
                'Pastikan pencahayaan di sekitar Anda cukup dan tidak membelakangi cahaya (backlight).',
                'Coba lepas kacamata atau masker Anda terlebih dahulu.',
            ],
            'Location Error' => [
                'Sistem mendeteksi Anda berada di luar radius sekolah. Pastikan GPS/Location service menyala dan berikan izin pada browser.',
                'Coba buka Google Maps terlebih dahulu untuk me-refresh titik lokasi GPS Anda, lalu coba absen kembali.',
            ],
            'Lupa Absen' => [
                'Kami telah mencatat laporan Anda. Silakan hubungi wali kelas Anda untuk konfirmasi manual.',
                'Aduan lupa absen diterima. Status kehadiran Anda akan disesuaikan setelah diverifikasi.',
            ]
        ];

        return $suggestions[$category] ?? [
            'Laporan Anda sudah kami terima dan sedang dalam pengecekan.',
            'Mohon menunggu sebentar, tim kami sedang memeriksa kendala ini.'
        ];
    }

    public function fetchMessages(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $lastId = $request->query('last_id', 0);

        $newMessages = TicketResponse::with('responder')
            ->where('ticket_id', $ticket->id)
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('d M Y, H:i'),
                    'responder_id' => $msg->responder_id,
                    'responder_name' => $msg->responder_id == Auth::user()->id_user ? 'Anda (Admin)' : ($msg->responder->name ?? 'Siswa'),
                ];
            });

        return response()->json([
            'success' => true,
            'status' => $ticket->status,
            'messages' => $newMessages
        ]);
    }
}
