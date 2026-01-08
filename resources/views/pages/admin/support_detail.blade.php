@extends('layouts.templateadmin')

@section('title', 'Detail Tiket #LP-' . str_pad($ticket->ticket_id, 4, '0', STR_PAD_LEFT) . ' | Admin LelangGame')

@section('content')
<div class="container-fluid py-4">
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
                        <div id="statusBadge">
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
                                $isAdmin = $message->sender_id == 1;
                                $isUser = $message->sender_id == $ticket->user_id;
                            @endphp
                            <div class="message-item p-3 {{ !$loop->last ? 'border-bottom' : '' }} {{ $isUser ? 'bg-light' : '' }}">
                                <div class="d-flex {{ $isAdmin ? 'flex-row-reverse' : '' }}">
                                    <div class="flex-shrink-0 {{ $isAdmin ? 'ms-3' : 'me-3' }}">
                                        @if($isAdmin)
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-headset"></i>
                                            </div>
                                        @else
                                            <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 45px; height: 45px;">
                                                {{ strtoupper(substr($ticket->user->username ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 {{ $isAdmin ? 'text-end' : '' }}">
                                        <div class="d-flex align-items-center {{ $isAdmin ? 'justify-content-end' : '' }} mb-1">
                                            <strong class="{{ $isAdmin ? 'text-primary' : '' }}">
                                                {{ $isAdmin ? 'Anda (Admin)' : ($ticket->user->username ?? 'Pengguna') }}
                                            </strong>
                                        </div>
                                        <div class="d-flex flex-column gap-2 {{ $isAdmin ? 'align-items-end' : 'align-items-start' }}">
                                            <div class="message-bubble d-inline-block p-3 rounded-3 {{ $isAdmin ? 'bg-primary text-white' : 'bg-white border' }}"
                                                 style="max-width: 85%; text-align: left;">
                                                {!! nl2br(e($message->content)) !!}
                                            </div>
                                            <small class="text-muted {{ $isAdmin ? 'me-2' : 'ms-2' }}">
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
                        <h6 class="mb-0"><i class="bi bi-reply me-2"></i>Balas Tiket</h6>
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

                        <form action="{{ route('admin.support.reply', $ticket->ticket_id) }}" method="POST" id="replyForm">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          name="message" rows="4"
                                          placeholder="Tulis balasan untuk pengguna..."
                                          required maxlength="2000" id="replyMessage">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="text-end mt-1">
                                    <small class="text-muted"><span id="replyCharCount">0</span>/2000</small>
                                </div>
                            </div>

                            {{-- Quick Responses --}}
                            <div class="mb-3">
                                <label class="form-label small text-muted">Template Balasan Cepat:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary quick-reply"
                                            data-message="Terima kasih telah menghubungi tim support LelangGame. Kami sedang memproses permintaan Anda dan akan segera memberikan update.">
                                        Sedang Diproses
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary quick-reply"
                                            data-message="Mohon maaf atas ketidaknyamanan yang dialami. Untuk membantu kami menyelesaikan masalah ini, mohon berikan informasi tambahan berikut:&#10;1. ID Transaksi/Lelang (jika ada)&#10;2. Screenshot bukti&#10;3. Detail kronologi kejadian">
                                        Minta Info Tambahan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary quick-reply"
                                            data-message="Masalah Anda telah berhasil kami selesaikan. Jika ada pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami kembali. Terima kasih telah menggunakan LelangGame!">
                                        Masalah Selesai
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
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
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- User Info --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="bi bi-person me-2"></i>Informasi Pengguna</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 50px; height: 50px; font-size: 1.2rem;">
                            {{ strtoupper(substr($ticket->user->username ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <strong>{{ $ticket->user->username ?? 'Unknown' }}</strong>
                            <br><small class="text-muted">{{ $ticket->user->email ?? '' }}</small>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted d-block">User ID</small>
                        <strong>#{{ $ticket->user->user_id ?? '-' }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Role</small>
                        <span class="badge bg-{{ $ticket->user->role == 'admin' ? 'danger' : ($ticket->user->role == 'seller' ? 'success' : 'primary') }}">
                            {{ ucfirst($ticket->user->role ?? 'user') }}
                        </span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Bergabung Sejak</small>
                        <strong>{{ $ticket->user->created_at ? $ticket->user->created_at->format('d M Y') : '-' }}</strong>
                    </div>
                </div>
            </div>

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
                    <div>
                        <small class="text-muted d-block">Status</small>
                        <span id="sidebarStatusBadge">
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
                        </span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                        </a>
                        @if($ticket->status != 'closed')
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#closeTicketModal" id="closeTicketBtn">
                                <i class="bi bi-x-circle me-1"></i>Tutup Tiket
                            </button>
                        @endif
                    </div>
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
                    Pengguna tidak akan dapat membalas setelah tiket ditutup.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.support.close', $ticket->ticket_id) }}" method="POST" class="d-inline">
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
    const userId = {{ $ticket->user_id }};
    const username = "{{ $ticket->user->username ?? 'Pengguna' }}";
    const userInitial = "{{ strtoupper(substr($ticket->user->username ?? 'U', 0, 1)) }}";
    const conversationContainer = document.getElementById('conversationContainer');
    const replyMessage = document.getElementById('replyMessage');
    const replyCharCount = document.getElementById('replyCharCount');
    const replyBtn = document.getElementById('replyBtn');
    const replyForm = document.getElementById('replyForm');
    const statusBadge = document.getElementById('statusBadge');
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

    document.querySelectorAll('.quick-reply').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const message = this.getAttribute('data-message').replace(/&#10;/g, '\n');
            if (replyMessage) {
                replyMessage.value = message;
                replyMessage.dispatchEvent(new Event('input'));
                replyMessage.focus();
            }
        });
    });

    if (replyForm && replyBtn) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const messageContent = replyMessage.value.trim();
            if (!messageContent) return;

            replyBtn.disabled = true;
            replyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: messageContent })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    appendMessage({
                        content: messageContent,
                        sender_id: myId,
                        created_at: new Date().toISOString()
                    });
                    replyMessage.value = '';
                    replyCharCount.textContent = '0';
                    updateStatus('answered');
                    scrollToBottom();
                } else {
                    alert(data.message || 'Gagal mengirim pesan');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan saat mengirim pesan');
            })
            .finally(() => {
                replyBtn.disabled = false;
                replyBtn.innerHTML = '<i class="bi bi-send me-1"></i>Kirim Balasan';
            });
        });
    }

    function appendMessage(message) {
        const isAdmin = message.sender_id == 1;
        const now = new Date(message.created_at);
        const formattedDate = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' +
                             now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        const lastMessage = conversationContainer.querySelector('.message-item:last-child');
        if (lastMessage && !lastMessage.classList.contains('border-bottom')) {
            lastMessage.classList.add('border-bottom');
        }

        const messageHTML = `
            <div class="message-item p-3 new-message ${!isAdmin ? 'bg-light' : ''}">
                <div class="d-flex ${isAdmin ? 'flex-row-reverse' : ''}">
                    <div class="flex-shrink-0 ${isAdmin ? 'ms-3' : 'me-3'}">
                        ${isAdmin ?
                            `<div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-headset"></i>
                            </div>` :
                            `<div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                ${userInitial}
                            </div>`
                        }
                    </div>
                    <div class="flex-grow-1 ${isAdmin ? 'text-end' : ''}">
                        <div class="d-flex align-items-center ${isAdmin ? 'justify-content-end' : ''} mb-1">
                            <strong class="${isAdmin ? 'text-primary' : ''}">
                                ${isAdmin ? 'Anda (Admin)' : username}
                            </strong>
                        </div>
                        <div class="d-flex flex-column gap-2 ${isAdmin ? 'align-items-end' : 'align-items-start'}">
                            <div class="message-bubble d-inline-block p-3 rounded-3 ${isAdmin ? 'bg-primary text-white' : 'bg-white border'}" style="max-width: 85%; text-align: left;">
                                ${message.content.replace(/\n/g, '<br>')}
                            </div>
                            <small class="text-muted ${isAdmin ? 'me-2' : 'ms-2'}">
                                ${formattedDate}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;

        conversationContainer.insertAdjacentHTML('beforeend', messageHTML);
        scrollToBottom();
    }

    function updateStatus(status) {
        currentStatus = status;
        let badgeHTML = '';
        let sidebarBadgeHTMLContent = '';

        switch(status) {
            case 'open':
                badgeHTML = `<span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock"></i> Menunggu Respons</span>`;
                sidebarBadgeHTMLContent = `<span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Menunggu Respons</span>`;
                break;
            case 'answered':
                badgeHTML = `<span class="badge bg-info fs-6"><i class="bi bi-reply"></i> Sudah Dijawab</span>`;
                sidebarBadgeHTMLContent = `<span class="badge bg-info"><i class="bi bi-reply"></i> Sudah Dijawab</span>`;
                break;
            case 'closed':
                badgeHTML = `<span class="badge bg-secondary fs-6"><i class="bi bi-check-circle"></i> Ditutup</span>`;
                sidebarBadgeHTMLContent = `<span class="badge bg-secondary"><i class="bi bi-check-circle"></i> Ditutup</span>`;
                hideReplyForm();
                break;
        }

        if (statusBadge) statusBadge.innerHTML = badgeHTML;
        if (sidebarStatusBadge) sidebarStatusBadge.innerHTML = sidebarBadgeHTMLContent;
        if (lastUpdated) lastUpdated.textContent = 'Baru saja';
    }

    function hideReplyForm() {
        const replyCard = document.getElementById('replyCard');
        const closeTicketBtn = document.getElementById('closeTicketBtn');

        if (replyCard) {
            replyCard.outerHTML = `
                <div class="alert alert-secondary" id="replyCard">
                    <i class="bi bi-lock me-2"></i>
                    <strong>Tiket Ditutup.</strong> Tiket ini sudah ditutup dan tidak dapat dibalas.
                </div>
            `;
        }

        if (closeTicketBtn) {
            closeTicketBtn.remove();
        }
    }

    function initWebSocket() {
        if (typeof window.Echo === 'undefined') {
            startPolling();
            return;
        }

        window.Echo.private(`support.ticket.${ticketId}`)
            .listen('.message.sent', (e) => {
                if (e.sender_id != myId) {
                    appendMessage(e);
                    if (e.sender_id == userId) {
                        updateStatus('open');
                    }
                }
            })
            .error((error) => {
                startPolling();
            });
    }

    let lastMessageId = {{ $messages->last() ? $messages->last()->message_id : 0 }};
    let pollingInterval = null;

    function startPolling() {
        if (pollingInterval) return;
        pollingInterval = setInterval(fetchNewMessages, 5000);
    }

    function fetchNewMessages() {
        if (currentStatus === 'closed') {
            clearInterval(pollingInterval);
            return;
        }

        fetch(`/admin/support/${ticketId}/messages?after=${lastMessageId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(message => {
                    if (message.sender_id != myId) {
                        appendMessage(message);
                    }
                    lastMessageId = Math.max(lastMessageId, message.message_id);
                });
            }
            if (data.status && data.status !== currentStatus) {
                updateStatus(data.status);
            }
        })
        .catch(error => {});
    }

    const waitForEcho = setInterval(() => {
        if (window.Echo) {
            clearInterval(waitForEcho);
            initWebSocket();
        }
    }, 100);

    setTimeout(() => {
        if (!window.Echo) {
            clearInterval(waitForEcho);
            startPolling();
        }
    }, 3000);
});
</script>
@endsection
