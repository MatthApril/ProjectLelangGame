@extends('layouts.template')

@section('title', 'Detail Tiket #LP-' . str_pad($ticket->ticket_id, 4, '0', STR_PAD_LEFT) . ' | LelangGame')

@section('content')
<div class="container my-4">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.services') }}">Layanan</a></li>
            <li class="breadcrumb-item"><a href="{{ route('support.index') }}">Pusat Bantuan</a></li>
            <li class="breadcrumb-item active" aria-current="page">#LP-{{ str_pad($ticket->ticket_id, 4, '0', STR_PAD_LEFT) }}</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Ticket Header Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">
                                <span class="text-primary fw-bold">#LP-{{ str_pad($ticket->ticket_id, 4, '0', STR_PAD_LEFT) }}</span>
                            </h5>
                            <h6 class="mb-0 text-muted">{{ $ticket->subject }}</h6>
                        </div>
                        <div id="ticketStatusBadge">
                            @switch($ticket->status)
                                @case('open')
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-clock"></i> Menunggu Respons
                                    </span>
                                    @break
                                @case('answered')
                                    <span class="badge bg-info fs-6">
                                        <i class="bi bi-reply"></i> Sudah Dijawab
                                    </span>
                                    @break
                                @case('closed')
                                    <span class="badge bg-secondary fs-6">
                                        <i class="bi bi-check-circle"></i> Ditutup
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            {{-- Conversation --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Percakapan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="conversation-container" style="max-height: 500px; overflow-y: auto;" id="conversationContainer">
                        @foreach($messages as $message)
                            @php
                                $isUser = $message->sender_id == Auth::id();
                                $isAdmin = $message->sender_id == 1;
                            @endphp
                            <div class="message-item p-3 {{ !$loop->last ? 'border-bottom' : '' }} {{ $isAdmin ? 'bg-light' : '' }}" data-message-id="{{ $message->message_id }}">
                                <div class="d-flex {{ $isUser ? 'flex-row-reverse' : '' }}">
                                    <div class="flex-shrink-0 {{ $isUser ? 'ms-3' : 'me-3' }}">
                                        @if($isAdmin)
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-headset"></i>
                                            </div>
                                        @else
                                            <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 {{ $isUser ? 'text-end' : '' }}">
                                        <div class="d-flex align-items-center {{ $isUser ? 'justify-content-end' : '' }} mb-1">
                                            <strong class="{{ $isAdmin ? 'text-primary' : '' }}">
                                                {{ $isAdmin ? 'Tim Support LelangGame' : 'Anda' }}
                                            </strong>
                                        </div>
                                        <div class="message-content d-flex flex-column gap-2 {{ $isUser ? 'align-items-end' : 'align-items-start' }}">
                                            <div class="message-bubble d-inline-block p-3 rounded-3 {{ $isUser ? 'bg-primary text-white' : 'bg-white border' }}"
                                                 style="max-width: 85%; text-align: left;">
                                                {!! nl2br(e($message->content)) !!}
                                            </div>
                                            <small class="text-muted {{ $isUser ? 'me-2' : 'ms-2' }}">
                                                {{ $message->created_at->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Reply Form --}}
            @if($ticket->status != 'closed')
                <div class="card shadow-sm" id="replyCard">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="bi bi-reply me-2"></i>Balas Pesan</h6>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('support.reply', $ticket->ticket_id) }}" method="POST" id="replyForm">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          name="message" rows="4"
                                          placeholder="Tulis balasan Anda di sini..."
                                          required maxlength="2000" id="replyMessage">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="text-end mt-1">
                                    <small class="text-muted"><span id="replyCharCount">0</span>/2000</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Tim support akan merespons dalam 1x24 jam kerja
                                </small>
                                <button type="submit" class="btn btn-primary" id="replyBtn">
                                    <i class="bi bi-send me-1"></i>Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary" id="replyCard">
                    <i class="bi bi-lock me-2"></i>
                    <strong>Tiket Ditutup.</strong> Tiket ini sudah ditutup dan tidak dapat dibalas.
                    Jika Anda memiliki pertanyaan lain, silakan <a href="{{ route('support.index') }}">buat tiket baru</a>.
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Ticket Info --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Tiket</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">ID Tiket</small>
                        <strong class="text-primary">#LP-{{ str_pad($ticket->ticket_id, 4, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Kategori</small>
                        <strong>{{ explode(' - ', $ticket->subject)[0] ?? $ticket->subject }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Jenis Masalah</small>
                        <strong>{{ explode(' - ', $ticket->subject)[1] ?? '-' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Dibuat Pada</small>
                        <strong>{{ $ticket->created_at->format('d M Y, H:i') }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Terakhir Diperbarui</small>
                        <strong id="lastUpdated">{{ $ticket->updated_at->diffForHumans() }}</strong>
                    </div>
                    <div id="sidebarStatusBadge">
                        <small class="text-muted d-block">Status</small>
                        @switch($ticket->status)
                            @case('open')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock"></i> Menunggu Respons
                                </span>
                                @break
                            @case('answered')
                                <span class="badge bg-info">
                                    <i class="bi bi-reply"></i> Sudah Dijawab
                                </span>
                                @break
                            @case('closed')
                                <span class="badge bg-secondary">
                                    <i class="bi bi-check-circle"></i> Ditutup
                                </span>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('support.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                        </a>
                        @if($ticket->status != 'closed')
                            <button type="button" class="btn btn-outline-danger" id="closeTicketBtn" data-bs-toggle="modal" data-bs-target="#closeTicketModal">
                                <i class="bi bi-x-circle me-1"></i>Tutup Tiket
                            </button>
                        @endif
                        <a href="{{ route('support.index') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Buat Tiket Baru
                        </a>
                    </div>
                </div>
            </div>

            {{-- Help --}}
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="fw-semibold"><i class="bi bi-question-circle text-primary me-1"></i>Butuh Bantuan?</h6>
                    <p class="small text-muted mb-2">
                        Jika masalah Anda belum terselesaikan, pastikan untuk memberikan detail yang lengkap agar tim kami dapat membantu dengan lebih baik.
                    </p>
                    <a href="{{ route('user.services') }}" class="small text-primary text-decoration-none">
                        <i class="bi bi-book me-1"></i>Lihat FAQ & Panduan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Close Ticket Modal --}}
@if($ticket->status != 'closed')
<div class="modal fade" id="closeTicketModal" tabindex="-1" aria-labelledby="closeTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeTicketModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>Tutup Tiket?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menutup tiket ini?</p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Setelah ditutup, Anda tidak dapat membalas di tiket ini. Jika masalah belum selesai, Anda dapat membuat tiket baru.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('support.close', $ticket->ticket_id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>Ya, Tutup Tiket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketId = {{ $ticket->ticket_id }};
    const myId = {{ Auth::id() }};
    const conversationContainer = document.getElementById('conversationContainer');
    const replyMessage = document.getElementById('replyMessage');
    const replyCharCount = document.getElementById('replyCharCount');
    const replyBtn = document.getElementById('replyBtn');
    const replyForm = document.getElementById('replyForm');
    const statusBadge = document.getElementById('ticketStatusBadge');
    const sidebarStatusBadge = document.getElementById('sidebarStatusBadge');
    const lastUpdated = document.getElementById('lastUpdated');
    let currentStatus = "{{ $ticket->status }}";

    function scrollToBottom() {
        if (conversationContainer) {
            conversationContainer.scrollTop = conversationContainer.scrollHeight;
        }
    }
    scrollToBottom();

    if (replyMessage && replyCharCount) {
        replyMessage.addEventListener('input', function() {
            const length = this.value.length;
            replyCharCount.textContent = length;

            if (length > 2000) {
                replyCharCount.classList.add('text-danger');
            } else {
                replyCharCount.classList.remove('text-danger');
            }
        });

        replyCharCount.textContent = replyMessage.value.length;
    }

    if (replyForm && replyBtn) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const message = replyMessage.value.trim();
            if (!message) return;

            replyBtn.disabled = true;
            replyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';

            fetch('{{ route("support.reply", $ticket->ticket_id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    appendMessage({
                        message_id: data.message.message_id,
                        sender_id: myId,
                        content: data.message.content,
                        is_admin: false,
                        created_at: data.message.created_at
                    });

                    replyMessage.value = '';
                    replyCharCount.textContent = '0';

                    updateStatus('open');
                    if (lastUpdated) {
                        lastUpdated.textContent = 'Baru saja';
                    }
                } else {
                    alert(data.message || 'Gagal mengirim pesan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim pesan');
            })
            .finally(() => {
                replyBtn.disabled = false;
                replyBtn.innerHTML = '<i class="bi bi-send me-1"></i>Kirim Balasan';
            });
        });
    }

    function appendMessage(data) {
        const isAdmin = data.is_admin;
        const isUser = data.sender_id === myId;

        const existingMessage = document.querySelector(`[data-message-id="${data.message_id}"]`);
        if (existingMessage) {
            return;
        }

        const messageHtml = `
            <div class="message-item p-3 border-bottom ${isAdmin ? 'bg-light' : ''} new-message" data-message-id="${data.message_id}">
                <div class="d-flex ${isUser ? 'flex-row-reverse' : ''}">
                    <div class="flex-shrink-0 ${isUser ? 'ms-3' : 'me-3'}">
                        ${isAdmin
                            ? `<div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-headset"></i>
                               </div>`
                            : `<div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-person"></i>
                               </div>`
                        }
                    </div>
                    <div class="flex-grow-1 ${isUser ? 'text-end' : ''}">
                        <div class="d-flex align-items-center ${isUser ? 'justify-content-end' : ''} mb-1">
                            <strong class="${isAdmin ? 'text-primary' : ''}">
                                ${isAdmin ? 'Tim Support LelangGame' : 'Anda'}
                            </strong>
                        </div>
                        <div class="message-content d-flex flex-column gap-2 ${isUser ? 'align-items-end' : ''}">
                            <div class="message-bubble d-inline-block p-3 rounded-3 ${isUser ? 'bg-primary text-white' : 'bg-white border'}" style="max-width: 85%; text-align: left;">
                                ${data.content.replace(/\n/g, '<br>')}
                            </div>
                            <small class="text-muted ${isUser ? 'me-2' : 'ms-2'}">
                                ${data.created_at}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const lastMessage = conversationContainer.querySelector('.message-item:last-child');
        if (lastMessage) {
            lastMessage.classList.add('border-bottom');
        }

        conversationContainer.insertAdjacentHTML('beforeend', messageHtml);
        scrollToBottom();
    }

    function updateStatus(status) {
        currentStatus = status;
        let headerBadgeHtml = '';
        let sidebarBadgeHtml = '';

        switch(status) {
            case 'open':
                headerBadgeHtml = '<span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock"></i> Menunggu Respons</span>';
                sidebarBadgeHtml = '<small class="text-muted d-block">Status</small><span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Menunggu Respons</span>';
                break;
            case 'answered':
                headerBadgeHtml = '<span class="badge bg-info fs-6"><i class="bi bi-reply"></i> Sudah Dijawab</span>';
                sidebarBadgeHtml = '<small class="text-muted d-block">Status</small><span class="badge bg-info"><i class="bi bi-reply"></i> Sudah Dijawab</span>';
                break;
            case 'closed':
                headerBadgeHtml = '<span class="badge bg-secondary fs-6"><i class="bi bi-check-circle"></i> Ditutup</span>';
                sidebarBadgeHtml = '<small class="text-muted d-block">Status</small><span class="badge bg-secondary"><i class="bi bi-check-circle"></i> Ditutup</span>';
                hideReplyForm();
                break;
        }

        if (statusBadge) statusBadge.innerHTML = headerBadgeHtml;
        if (sidebarStatusBadge) sidebarStatusBadge.innerHTML = sidebarBadgeHtml;
    }

    function hideReplyForm() {
        const replyCard = document.getElementById('replyCard');
        const closeTicketBtn = document.getElementById('closeTicketBtn');

        if (replyCard) {
            replyCard.innerHTML = `
                <div class="alert alert-secondary mb-0">
                    <i class="bi bi-lock me-2"></i>
                    <strong>Tiket Ditutup.</strong> Tiket ini sudah ditutup dan tidak dapat dibalas.
                    Jika Anda memiliki pertanyaan lain, silakan <a href="{{ route('support.index') }}">buat tiket baru</a>.
                </div>
            `;
            replyCard.className = '';
        }

        if (closeTicketBtn) {
            closeTicketBtn.style.display = 'none';
        }
    }

    function initWebSocket() {
        if (typeof window.Echo === 'undefined') {
            console.log('Echo not loaded yet, using polling fallback');
            return false;
        }

        console.log('Subscribing to support.ticket.' + ticketId);

        try {
            window.Echo.private('support.ticket.' + ticketId)
                .listen('.message.sent', function(data) {
                    console.log('New message received via WebSocket:', data);

                    if (data.sender_id === myId) {
                        return;
                    }

                    appendMessage(data);

                    if (data.is_admin) {
                        updateStatus('answered');
                    }

                    if (lastUpdated) {
                        lastUpdated.textContent = 'Baru saja';
                    }
                });
            return true;
        } catch (e) {
            console.error('WebSocket error:', e);
            return false;
        }
    }

    let lastMessageId = {{ $messages->last() ? $messages->last()->message_id : 0 }};
    let pollingInterval = null;

    function startPolling() {
        if (pollingInterval) return;
        pollingInterval = setInterval(fetchNewMessages, 5000);
        console.log('Polling started');
    }

    function fetchNewMessages() {
        fetch(`{{ route('support.messages', $ticket->ticket_id) }}?after=${lastMessageId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    if (msg.sender_id !== myId) {
                        appendMessage({
                            message_id: msg.message_id,
                            sender_id: msg.sender_id,
                            content: msg.content,
                            is_admin: msg.is_admin,
                            created_at: msg.created_at
                        });
                    }
                    lastMessageId = Math.max(lastMessageId, msg.message_id);
                });

                if (data.status) {
                    updateStatus(data.status);
                }

                if (lastUpdated) {
                    lastUpdated.textContent = 'Baru saja';
                }
            }
        })
        .catch(error => {
            console.error('Polling error:', error);
        });
    }

    const waitForEcho = setInterval(() => {
        if (typeof window.Echo !== 'undefined') {
            clearInterval(waitForEcho);
            if (initWebSocket()) {
                console.log('WebSocket connected successfully');
            } else {
                startPolling();
            }
        }
    }, 100);

    setTimeout(() => {
        if (typeof window.Echo === 'undefined') {
            clearInterval(waitForEcho);
            console.log('Echo not available, starting polling fallback');
            startPolling();
        }
    }, 3000);
});
</script>
@endsection
