@extends('layouts.app')

@section('content')
<style>
    /* Telegram-style Layout */
    .chat-layout {
        height: calc(100vh - 120px);
        display: flex;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    /* Left Sidebar (Ticket List) */
    .chat-sidebar {
        width: 350px;
        border-right: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
        background: #f8f9fa;
    }
    .chat-sidebar-header {
        padding: 20px;
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ticket-list {
        flex-grow: 1;
        overflow-y: auto;
    }
    .ticket-item {
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    .ticket-item:hover {
        background: #f0f2f5;
    }
    .ticket-item.active {
        background: #e3f2fd;
    }
    
    /* Main Chat Room */
    .chat-room {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background-color: #e4efe7; /* Soft telegram background */
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .chat-header {
        padding: 15px 20px;
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        z-index: 10;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .chat-messages {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
    
    /* Chat Bubbles */
    .chat-bubble-wrapper {
        display: flex;
        width: 100%;
        margin-bottom: 15px;
    }
    .chat-bubble {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 18px;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        word-wrap: break-word;
        font-size: 0.95rem;
    }
    .chat-right {
        justify-content: flex-end;
    }
    .chat-right .chat-bubble {
        background-color: #e1f5fe; /* Light blue Telegram style */
        color: #000;
        border-bottom-right-radius: 4px;
    }
    .chat-left {
        justify-content: flex-start;
    }
    .chat-left .chat-bubble {
        background-color: #ffffff;
        color: #000;
        border-bottom-left-radius: 4px;
    }
    
    .chat-time {
        font-size: 0.7rem;
        color: #888;
        display: block;
        text-align: right;
        margin-top: 4px;
    }
    .chat-sender {
        font-weight: 600;
        font-size: 0.8rem;
        margin-bottom: 4px;
        display: block;
        color: #1976d2; /* Telegram blue */
    }
    
    /* Input Area */
    .chat-footer {
        padding: 15px 20px;
        background: #fff;
        border-top: 1px solid #e0e0e0;
    }
    .chat-input-group {
        display: flex;
        background: #f0f2f5;
        border-radius: 30px;
        padding: 5px 15px;
        align-items: center;
    }
    .chat-input-group input {
        border: none;
        background: transparent;
        flex-grow: 1;
        padding: 10px;
        outline: none;
        box-shadow: none !important;
    }
    .chat-input-group button {
        background: #1976d2;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .chat-input-group button:hover {
        transform: scale(1.05);
    }
    .chat-input-group button:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .chat-sidebar {
            display: none; /* Hide sidebar on mobile in show view */
        }
        .chat-layout {
            height: calc(100vh - 80px);
            border-radius: 0;
        }
        .container-xxl {
            padding: 0 !important;
        }
        .btn-back-mobile {
            display: block !important;
        }
    }
    .btn-back-mobile {
        display: none;
        margin-right: 15px;
        color: #555;
        font-size: 1.5rem;
    }
</style>

<div class="chat-layout">
    <!-- Sidebar Kiri: List Tiket -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('siswa.helpdesk.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bx bx-arrow-back"></i></a>
                <h5 class="m-0 fw-bold">Tiket Saya</h5>
            </div>
            <a href="{{ route('siswa.helpdesk.create') }}" class="btn btn-sm btn-primary rounded-pill"><i class="bx bx-plus"></i></a>
        </div>
        <div class="ticket-list">
            @foreach($allTickets as $t)
            <a href="{{ route('siswa.helpdesk.show', $t->id) }}" class="ticket-item {{ $t->id == $ticket->id ? 'active' : '' }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="text-truncate" style="max-width: 180px;">{{ $t->subject }}</strong>
                    <small class="text-muted">{{ $t->created_at->format('d M') }}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-truncate text-muted" style="max-width: 180px;">{{ $t->category }}</small>
                    @if($t->status == 'open')
                        <span class="badge bg-warning" style="font-size: 0.6rem;">Open</span>
                    @elseif($t->status == 'in_progress')
                        <span class="badge bg-primary" style="font-size: 0.6rem;">Proses</span>
                    @else
                        <span class="badge bg-success" style="font-size: 0.6rem;">Closed</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Area Kanan: Chat Room -->
    <div class="chat-room">
        <!-- Header Chat -->
        <div class="chat-header">
            <a href="{{ route('siswa.helpdesk.index') }}" class="btn-back-mobile"><i class="bx bx-arrow-back"></i></a>
            <div>
                <h6 class="m-0 fw-bold">{{ $ticket->subject }}</h6>
                <small class="text-muted" id="status-badge">
                    @if($ticket->status == 'open')
                        <span class="text-warning"><i class="bx bx-time-five"></i> Menunggu Respon</span>
                    @elseif($ticket->status == 'in_progress')
                        <span class="text-primary"><i class="bx bx-cog bx-spin"></i> Sedang Ditangani</span>
                    @else
                        <span class="text-success"><i class="bx bx-check-shield"></i> Tiket Selesai</span>
                    @endif
                </small>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="chat-messages" id="chat-box">
            <!-- Pesan Awal (Deskripsi Tiket) -->
            <div class="chat-bubble-wrapper chat-right">
                <div class="chat-bubble">
                    <span class="chat-sender">Anda (Pelapor)</span>
                    <div>{{ $ticket->description }}</div>
                    <span class="chat-time">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <!-- Balasan -->
            @php $lastMsgId = 0; @endphp
            @foreach($ticket->responses as $response)
                @php $lastMsgId = $response->id; @endphp
                <div class="chat-bubble-wrapper {{ $response->responder_id == Auth::user()->id_user ? 'chat-right' : 'chat-left' }}">
                    <div class="chat-bubble">
                        @if($response->responder_id != Auth::user()->id_user)
                            <span class="chat-sender">{{ $response->is_auto_reply ? 'Virtual Assistant 🤖' : ($response->responder->name ?? 'Admin / Operator') }}</span>
                        @endif
                        <div>{{ $response->message }}</div>
                        <span class="chat-time">{{ $response->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Form Rating jika tiket ditutup -->
        <div id="rating-container" class="{{ $ticket->status === 'closed' && !$ticket->rating ? '' : 'd-none' }} bg-white p-3 border-top">
            <div class="text-center">
                <h6 class="mb-2">Tiket Selesai. Bagaimana pelayanan kami?</h6>
                <form id="rating-form" action="{{ route('siswa.helpdesk.rate', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center gap-2 mb-2">
                        <select name="score" class="form-select form-select-sm w-auto" required>
                            <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                            <option value="4">⭐⭐⭐⭐ Puas</option>
                            <option value="3">⭐⭐⭐ Cukup</option>
                            <option value="2">⭐⭐ Kurang</option>
                            <option value="1">⭐ Kecewa</option>
                        </select>
                        <input type="text" name="feedback" class="form-control form-control-sm w-auto" placeholder="Kesan (opsional)">
                        <button type="submit" class="btn btn-sm btn-success">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Pesan Terimakasih Rating -->
        <div id="rating-success" class="{{ $ticket->rating ? '' : 'd-none' }} bg-white p-2 border-top text-center text-success">
            <small><i class="bx bx-check-circle"></i> Terima kasih atas penilaian Anda.</small>
        </div>

        <!-- Chat Input Footer -->
        <div class="chat-footer" id="chat-footer-form" style="{{ $ticket->status === 'closed' ? 'display:none;' : '' }}">
            <form id="chat-form" action="{{ route('siswa.helpdesk.reply', $ticket->id) }}" method="POST">
                @csrf
                <div class="chat-input-group">
                    <input type="text" id="chat-input" name="message" placeholder="Ketik pesan..." required autocomplete="off">
                    <button type="submit" id="btn-send"><i class="bx bx-send"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let lastMessageId = {{ $lastMsgId }};
    const ticketId = {{ $ticket->id }};
    const fetchUrl = "{{ route('siswa.helpdesk.fetch', $ticket->id) }}";
    
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const btnSend = document.getElementById('btn-send');
    const chatFooter = document.getElementById('chat-footer-form');
    const ratingContainer = document.getElementById('rating-container');
    const statusBadge = document.getElementById('status-badge');

    // Auto scroll to bottom
    function scrollToBottom() {
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }
    scrollToBottom();

    // Fungsi Render Bubble
    function appendMessage(msg, isRight) {
        const wrapperClass = isRight ? 'chat-right' : 'chat-left';
        const senderHtml = isRight ? '' : `<span class="chat-sender">${msg.responder_name}</span>`;
        
        const html = `
            <div class="chat-bubble-wrapper ${wrapperClass} fade-in">
                <div class="chat-bubble">
                    ${senderHtml}
                    <div>${msg.message}</div>
                    <span class="chat-time">${msg.created_at}</span>
                </div>
            </div>
        `;
        chatBox.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
    }

    // AJAX Form Submit
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = chatInput.value.trim();
            if(!message) return;

            btnSend.disabled = true;
            btnSend.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';

            const formData = new FormData(chatForm);

            fetch(chatForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="bx bx-send"></i>';

                if(data.success) {
                    chatInput.value = '';
                    appendMessage(data.data, true);
                    lastMessageId = data.data.id;

                    // Jika ada balasan otomatis dari bot, langsung append
                    if(data.bot_reply) {
                        setTimeout(() => {
                            appendMessage(data.bot_reply, false);
                        }, 500); // delay dikit biar kerasa "ngetik"
                    }
                } else {
                    alert(data.message || 'Gagal mengirim pesan.');
                }
            })
            .catch(err => {
                console.error(err);
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="bx bx-send"></i>';
            });
        });
    }

    // AJAX Polling Interval
    setInterval(() => {
        fetch(`${fetchUrl}?last_id=${lastMessageId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Check if ticket status changed to closed
                if(data.status === 'closed') {
                    chatFooter.style.display = 'none';
                    if(!document.getElementById('rating-success').classList.contains('d-none')) {
                        // already rated
                    } else {
                        ratingContainer.classList.remove('d-none');
                    }
                    statusBadge.innerHTML = '<span class="text-success"><i class="bx bx-check-shield"></i> Tiket Selesai</span>';
                }

                if(data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        // If it's from someone else, render left
                        const isMine = msg.responder_name === 'Anda';
                        appendMessage(msg, isMine);
                        lastMessageId = msg.id;
                    });
                }
            }
        })
        .catch(err => console.error('Polling error:', err));
    }, 3000); // Poll every 3 seconds

    // AJAX Rating Submit
    const ratingForm = document.getElementById('rating-form');
    if(ratingForm) {
        ratingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = ratingForm.querySelector('button');
            btn.disabled = true;
            btn.innerHTML = '...';

            fetch(ratingForm.action, {
                method: 'POST',
                body: new FormData(ratingForm),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    ratingContainer.classList.add('d-none');
                    document.getElementById('rating-success').classList.remove('d-none');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = 'Kirim';
            });
        });
    }
</script>
@endsection
