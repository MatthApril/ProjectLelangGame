@extends('layouts.template')

@section('content')
<div class="container my-4">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Chat</li>
        </ol>
    </nav>
    <h3 class="fw-bold mb-4">Daftar Chat</h3>

    <div class="card shadow-sm" style="min-height: 30rem;">
        <div class="card-body p-0">
            <div class="card-header bg-white">
                <form action="{{ route('chat.index') }}" class="input-group" method="GET" id="search-form">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                    <input type="text" class="form-control" placeholder="Cari pengguna..." name="search" id="search-input" value="{{ request('search') }}">
                </form>
            </div>
            <div class="list-group list-group-flush" id="chat-list">
                @forelse ($users as $user)
                    <a href="{{ route('chat.open', $user->user_id) }}"
                       class="list-group-item list-group-item-action chat-item" data-user-id="{{ $user->user_id }}">
                        <div class="d-flex align-items-center py-2">
                            <div class="position-relative me-3">
                                <i class="bi bi-person-circle user-icon" style="font-size: 2.5rem; color: {{ $user->unread_count > 0 ? '#0d6efd' : '#6c757d' }};"></i>
                                @if($user->unread_count > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary unread-badge">
                                        {{ $user->unread_count > 99 ? '99+' : $user->unread_count }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 username {{ $user->unread_count > 0 ? 'fw-bold text-primary' : '' }}">{{ $user->username }}</h6>
                                    @if($user->last_message)
                                        <small class="text-muted message-time">{{ $user->last_message->created_at->diffForHumans() }}</small>
                                    @endif
                                </div>
                                @if($user->last_message)
                                    <p class="mb-0 text-muted small text-truncate last-message {{ $user->unread_count > 0 ? 'fw-semibold' : '' }}" style="max-width: 100%;">
                                        @if($user->last_message->sender_id == Auth::user()->user_id)
                                            <span class="text-secondary">Anda: </span>
                                        @endif
                                        {{ Str::limit($user->last_message->content, 50) }}
                                    </p>
                                @else
                                    <small class="text-muted">{{ $user->email }}</small>
                                @endif
                            </div>

                        </div>
                    </a>
                @empty
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 25rem;">
                        @if($notFound)
                            <div class="text-center py-5" id="not-found-message">
                                <i class="bi bi-exclamation-circle text-muted" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-3">Pengguna tidak ditemukan</p>
                                <p class="text-muted small">Coba gunakan kata kunci lain</p>
                            </div>
                        @else
                            <div class="text-center py-5" id="empty-message">
                                <i class="bi bi-chat-square-text text-muted" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-3">Belum ada percakapan</p>
                                <p class="text-muted small">Mulai chat dengan mengunjungi halaman produk atau toko</p>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const myId = {{ Auth::user()->user_id }};
    const chatList = document.getElementById('chat-list');
    const searchInput = document.getElementById('search-input');

    // Function to create chat item HTML
    function createChatItemHTML(user) {
        const hasUnread = user.unread_count > 0;
        const unreadBadge = hasUnread
            ? `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary unread-badge">
                ${user.unread_count > 99 ? '99+' : user.unread_count}
               </span>`
            : '';

        const lastMessageHTML = user.last_message_content
            ? `<p class="mb-0 text-muted small text-truncate last-message ${hasUnread ? 'fw-semibold' : ''}" style="max-width: 100%;">
                ${user.last_message_is_mine ? '<span class="text-secondary">Anda: </span>' : ''}
                ${truncateString(user.last_message_content, 50)}
               </p>`
            : `<small class="text-muted">${user.email}</small>`;

        const timeHTML = user.last_message_time
            ? `<small class="text-muted message-time">${user.last_message_time}</small>`
            : '';

        return `
            <a href="/chat/${user.user_id}"
               class="list-group-item list-group-item-action chat-item new-message" data-user-id="${user.user_id}">
                <div class="d-flex align-items-center py-2">
                    <div class="position-relative me-3">
                        <i class="bi bi-person-circle user-icon" style="font-size: 2.5rem; color: ${hasUnread ? '#0d6efd' : '#6c757d'};"></i>
                        ${unreadBadge}
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 username ${hasUnread ? 'fw-bold text-primary' : ''}">${user.username}</h6>
                            ${timeHTML}
                        </div>
                        ${lastMessageHTML}
                    </div>
                </div>
            </a>
        `;
    }

    function truncateString(str, maxLength) {
        if (!str) return '';
        if (str.length <= maxLength) return str;
        return str.substring(0, maxLength) + '...';
    }

    // Function to refresh chat list
    function refreshChatList() {
        const search = searchInput ? searchInput.value : '';

        fetch(`/chat/api/list?search=${encodeURIComponent(search)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.users.length === 0) {
                if (search) {
                    chatList.innerHTML = `
                        <div class="d-flex justify-content-center align-items-center" style="min-height: 25rem;">
                            <div class="text-center py-5" id="not-found-message">
                                <i class="bi bi-exclamation-circle text-muted" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-3">Pengguna tidak ditemukan</p>
                                <p class="text-muted small">Coba gunakan kata kunci lain</p>
                            </div>
                        </div>
                    `;
                } else {
                    chatList.innerHTML = `
                        <div class="d-flex justify-content-center align-items-center" style="min-height: 25rem;">
                            <div class="text-center py-5" id="empty-message">
                                <i class="bi bi-chat-square-text text-muted" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-3">Belum ada percakapan</p>
                                <p class="text-muted small">Mulai chat dengan mengunjungi halaman produk atau toko</p>
                            </div>
                        </div>
                    `;
                }
                return;
            }

            // Update chat list
            let newHTML = '';
            data.users.forEach(user => {
                newHTML += createChatItemHTML(user);
            });
            chatList.innerHTML = newHTML;

            setTimeout(() => {
                document.querySelectorAll('.chat-item.new-message').forEach(el => {
                    el.classList.remove('new-message');
                });
            }, 1000);
        })
        .catch(error => {
            console.error('Error fetching chat list:', error);
        });
    }

    const waitForEcho = setInterval(() => {
        if (window.Echo) {
            clearInterval(waitForEcho);

            window.Echo.private(`user.${myId}`)
                .listen('.MessageSent', (e) => {
                    console.log('New message received on chat list:', e);
                    refreshChatList();
                });

            console.log('Echo connected for chat list updates');
        }
    }, 100);

    // Also poll every 3 seconds as fallback
    setInterval(refreshChatList, 3000);

    // Live search with debounce
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                refreshChatList();
            }, 300);
        });
    }
});
</script>
@endsection
