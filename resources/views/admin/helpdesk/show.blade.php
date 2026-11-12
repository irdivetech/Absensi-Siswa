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
        justify-content: space-between;
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
        display: flex;
        flex-direction: column;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .chat-sidebar {
            display: none;
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
    
    /* Suggestion Chips */
    .suggestion-chips {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 10px;
        margin-bottom: 5px;
    }
    .suggestion-chips::-webkit-scrollbar {
        height: 4px;
    }
    .suggestion-chips::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }
    .chip {
        background: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
        border-radius: 16px;
        padding: 5px 12px;
        font-size: 0.8rem;
        white-space: nowrap;
        margin-right: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .chip:hover {
        background: #1976d2;
        color: #fff;
    }
</style>

<div class="chat-layout">
    <!-- Sidebar Kiri: List Tiket -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bx bx-arrow-back"></i></a>
                <h5 class="m-0 fw-bold">Antrean Tiket</h5>
            </div>
        </div>
        <div class="ticket-list">
            @foreach($allTickets as $t)
            <a href="{{ route('admin.helpdesk.show', $t->id) }}" class="ticket-item {{ $t->id == $ticket->id ? 'active' : '' }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="text-truncate" style="max-width: 150px;">{{ $t->reporter ? $t->reporter->name : 'Siswa' }}</strong>
                    <small class="text-muted">{{ $t->created_at->format('d M') }}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-truncate text-muted" style="max-width: 150px;">{{ $t->subject }}</small>
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
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.helpdesk.index') }}" class="btn-back-mobile"><i class="bx bx-arrow-back"></i></a>
                <div>
                    <h6 class="m-0 fw-bold">{{ $ticket->reporter ? $ticket->reporter->name : 'Siswa' }} <span class="badge bg-label-secondary ms-2 text-xs">{{ $ticket->category }}</span></h6>
                    <small class="text-muted">{{ $ticket->subject }}</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <form id="status-form" action="{{ route('admin.helpdesk.update_status', $ticket->id) }}" method="POST" class="m-0">
                    @csrf
                    <select name="status" id="status-select" class="form-select form-select-sm" style="width: auto; font-weight: bold; {{ $ticket->status == 'closed' ? 'background-color: #d4edda;' : '' }}">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="chat-messages" id="chat-box">
            <!-- Pesan Awal (Deskripsi Tiket) -->
            <div class="chat-bubble-wrapper chat-left">
                <div class="chat-bubble">
                    <span class="chat-sender">{{ $ticket->reporter ? $ticket->reporter->name : 'Siswa' }} (Pelapor)</span>
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
                            <span class="chat-sender">{{ $response->responder->name ?? 'Siswa' }}</span>
                        @endif
                        <div>{{ $response->message }}</div>
                        <span class="chat-time">{{ $response->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Informasi Rating Jika Ada -->
        <div id="rating-container" class="{{ $ticket->rating ? '' : 'd-none' }} bg-white p-2 border-top text-center text-warning">
            <small class="fw-bold"><i class="bx bxs-star"></i> Penilaian Siswa: <span id="rating-score">{{ $ticket->rating ? $ticket->rating->score : '' }}</span>/5 - "<span id="rating-feedback">{{ $ticket->rating ? $ticket->rating->feedback : '' }}</span>"</small>
        </div>

        <!-- Chat Input Footer -->
        <div class="chat-footer" id="chat-footer-form" style="{{ $ticket->status === 'closed' ? 'display:none;' : '' }}">
            <!-- Auto Reply Suggestions -->
            <div class="suggestion-chips">
                @foreach($suggestions as $suggest)
                    <div class="chip auto-reply-btn" data-text="{{ $suggest }}">
                        {{ Str::limit($suggest, 30) }}
                    </div>
                @endforeach
            </div>

            <form id="chat-form" action="{{ route('admin.helpdesk.reply', $ticket->id) }}" method="POST" class="m-0">
                @csrf
                <div class="chat-input-group">
                    <input type="text" id="chat-input" name="message" placeholder="Ketik balasan..." required autocomplete="off">
                    <button type="submit" id="btn-send"><i class="bx bx-send"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let lastMessageId = {{ $lastMsgId }};
    const fetchUrl = "{{ route('admin.helpdesk.fetch', $ticket->id) }}";
    
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const btnSend = document.getElementById('btn-send');
    const statusForm = document.getElementById('status-form');
    const statusSelect = document.getElementById('status-select');
    const chatFooter = document.getElementById('chat-footer-form');
    
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

    // Auto Reply Click
    document.querySelectorAll('.auto-reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            chatInput.value = this.getAttribute('data-text');
            chatInput.focus();
        });
    });

    // Toast Notification Simple
    function showToast(message, type = 'success') {
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        const toastHTML = `
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
            <div class="toast align-items-center text-white ${bgClass} border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', toastHTML);
        setTimeout(() => {
            const toastEl = document.querySelector('.toast.show');
            if(toastEl) toastEl.remove();
        }, 3000);
    }

    // AJAX Form Submit
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = chatInput.value.trim();
            if(!message) return;

            btnSend.disabled = true;
            btnSend.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';

            fetch(chatForm.action, {
                method: 'POST',
                body: new FormData(chatForm),
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
                } else {
                    showToast(data.message || 'Gagal mengirim pesan', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="bx bx-send"></i>';
                showToast('Kesalahan jaringan', 'error');
            });
        });
    }

    // AJAX Status Update
    if (statusSelect && statusForm) {
        statusSelect.addEventListener('change', function() {
            fetch(statusForm.action, {
                method: 'POST',
                body: new FormData(statusForm),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showToast(data.message, 'success');
                    if(statusSelect.value === 'closed') {
                        statusSelect.style.backgroundColor = '#d4edda';
                        chatFooter.style.display = 'none';
                    } else {
                        statusSelect.style.backgroundColor = '';
                        chatFooter.style.display = 'flex';
                    }
                }
            })
            .catch(err => console.error(err));
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
                // Check if status changed remotely
                if(data.status !== statusSelect.value) {
                    statusSelect.value = data.status;
                    if(data.status === 'closed') {
                        statusSelect.style.backgroundColor = '#d4edda';
                        chatFooter.style.display = 'none';
                    } else {
                        statusSelect.style.backgroundColor = '';
                        chatFooter.style.display = 'flex';
                    }
                }

                if(data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        const isMine = msg.responder_name === 'Anda (Admin)';
                        appendMessage(msg, isMine);
                        lastMessageId = msg.id;
                    });
                }
            }
        })
        .catch(err => console.error('Polling error:', err));
    }, 3000);
</script>
@endsection
