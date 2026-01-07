@extends('layouts.template')

@section('content')
<div class="container my-4">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
        </ol>
    </nav>
    <h3 class="fw-bold mb-4">Notifikasi</h3>

    <div id="notification-list">
        @forelse ($recipients as $recipient)
            <div id="notif-item-{{ $recipient->notif_recip_id }}"
                 class="notification-item mb-3 p-3 border rounded {{ $recipient->is_read ? 'bg-light' : 'bg-white border-primary' }}"
                 style="cursor: pointer; position: relative; transition: all 0.3s ease;"
                 onclick="window.location.href='{{ route('notifications.detail', $recipient->notification_id) }}'">

                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1 fw-semibold text-dark">{{ $recipient->notification->title }}</h5>
                        <small class="text-muted">
                            {{ $recipient->notification->created_at->diffForHumans() }}
                        </small>
                    </div>

                    <div class="d-flex align-items-center">
                        @if (!$recipient->is_read)
                            <span class="badge bg-primary me-2">Baru</span>
                        @else
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger border-0 z-10"
                                    onclick="deleteNotification(event, {{ $recipient->notif_recip_id }})"
                                    title="Hapus Notifikasi">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div id="empty-state" class="alert alert-info">
                <i class="bi bi-info-circle"></i> Tidak ada notifikasi.
            </div>
        @endforelse
    </div>

    <div id="empty-state-template" class="alert alert-info d-none">
        <i class="bi bi-info-circle"></i> Tidak ada notifikasi.
    </div>
</div>

<script>
    function deleteNotification(event, id) {
        event.stopPropagation();

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                const element = document.getElementById(`notif-item-${id}`);

                element.style.opacity = '0';
                element.style.transform = 'translateX(20px)';

                setTimeout(() => {
                    element.remove();
                    checkEmptyState();
                }, 300);
            } else {
                alert('Gagal menghapus notifikasi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem.');
        });
    }

    function checkEmptyState() {
        const list = document.getElementById('notification-list');
        // If no notification items are left, show the empty state
        if (list.querySelectorAll('.notification-item').length === 0) {
            document.getElementById('empty-state-template').classList.remove('d-none');
            list.appendChild(document.getElementById('empty-state-template'));
        }
    }
</script>
@endsection
