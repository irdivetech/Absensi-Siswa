<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\SatisfactionRating;
use App\Services\ChatbotService;
use Illuminate\Support\Facades\Auth;

class HelpdeskController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('reporter_id', Auth::user()->id_user)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('siswa.helpdesk.index', compact('tickets'));
    }

    public function create()
    {
        return view('siswa.helpdesk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'reporter_id' => Auth::user()->id_user,
            'subject' => $request->subject,
            'category' => $request->category,
            'description' => $request->description,
            'priority' => 'medium', // Default priority
            'status' => 'open',
            'needs_admin' => false, // Default ditangani bot dulu
        ]);

        // ==========================================
        // AUTO-REPLY DARI CHATBOT (GREETING AWAL)
        // ==========================================
        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'responder_id' => ChatbotService::getBotUserId(),
            'message' => ChatbotService::getGreeting(),
            'is_auto_reply' => true,
        ]);
        // ==========================================

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tiket berhasil dibuat. Tim kami akan segera menindaklanjutinya.',
                'redirect_url' => route('siswa.helpdesk.show', $ticket->id)
            ]);
        }

        return redirect()->route('siswa.helpdesk.show', $ticket->id)
            ->with('success', 'Tiket berhasil dibuat. Tim kami akan segera menindaklanjutinya.');
    }

    public function show($id)
    {
        $ticket = Ticket::where('reporter_id', Auth::user()->id_user)
            ->with('responses.responder', 'rating')
            ->findOrFail($id);

        $allTickets = Ticket::where('reporter_id', Auth::user()->id_user)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.helpdesk.show', compact('ticket', 'allTickets'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::where('reporter_id', Auth::user()->id_user)->findOrFail($id);

        if ($ticket->status === 'closed') {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Tiket sudah ditutup.'], 403);
            return redirect()->back()->with('error', 'Tiket sudah ditutup, tidak dapat membalas pesan.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $response = TicketResponse::create([
            'ticket_id' => $ticket->id,
            'responder_id' => Auth::user()->id_user,
            'message' => $request->message,
        ]);

        $botReplyText = null;

        // JIKA TIKET MASIH DITANGANI BOT (Belum Eskalasi)
        if (!$ticket->needs_admin && $ticket->status === 'open') {
            $botReplyText = ChatbotService::handleMessage($ticket, $request->message);
            
            if ($botReplyText) {
                // Simpan balasan Bot ke database
                TicketResponse::create([
                    'ticket_id' => $ticket->id,
                    'responder_id' => ChatbotService::getBotUserId(),
                    'message' => $botReplyText,
                    'is_auto_reply' => true,
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $response->id,
                    'message' => $response->message,
                    'created_at' => $response->created_at->format('d M Y, H:i'),
                    'responder_id' => $response->responder_id,
                    'responder_name' => 'Anda',
                ],
                'bot_reply' => $botReplyText ? [
                    'message' => nl2br(e($botReplyText)),
                    'responder_name' => 'Virtual Assistant',
                    'created_at' => now()->format('d M Y, H:i')
                ] : null
            ]);
        }

        return redirect()->back()->with('success', 'Pesan terkirim.');
    }

    public function rate(Request $request, $id)
    {
        $ticket = Ticket::where('reporter_id', Auth::user()->id_user)->findOrFail($id);

        if ($ticket->status !== 'closed') {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Tiket belum ditutup.'], 403);
            return redirect()->back()->with('error', 'Tiket belum ditutup.');
        }

        if ($ticket->rating) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Anda sudah memberikan penilaian.'], 403);
            return redirect()->back()->with('error', 'Anda sudah memberikan penilaian.');
        }

        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        SatisfactionRating::create([
            'ticket_id' => $ticket->id,
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Terima kasih atas penilaian Anda!']);
        }

        return redirect()->back()->with('success', 'Terima kasih atas penilaian Anda!');
    }

    public function checkDuplication(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }

        // Cari kemiripan pada subject atau deskripsi menggunakan LIKE
        $similarTickets = Ticket::where('reporter_id', Auth::user()->id_user)
            ->where(function($q) use ($query) {
                $q->where('subject', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->where('status', '!=', 'closed') // Hanya tampilkan yang masih aktif
            ->select('id', 'subject', 'status', 'created_at')
            ->take(3)
            ->get();

        return response()->json($similarTickets);
    }

    public function fetchMessages(Request $request, $id)
    {
        $ticket = Ticket::where('reporter_id', Auth::user()->id_user)->findOrFail($id);
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
                    'responder_name' => $msg->responder_id == Auth::user()->id_user ? 'Anda' : ($msg->is_auto_reply ? 'Virtual Assistant 🤖' : ($msg->responder->name ?? 'Admin / Operator')),
                    'is_auto_reply' => $msg->is_auto_reply,
                ];
            });

        return response()->json([
            'success' => true,
            'status' => $ticket->status,
            'messages' => $newMessages
        ]);
    }
}
