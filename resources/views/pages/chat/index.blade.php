@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4">Daftar Chat</h3>

    @forelse ($users as $user)
        <a href="{{ route('chat.open', $user->user_id) }}" class="text-decoration-none">
            <div class="card mb-2">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-person-circle me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h6 class="mb-0">{{ $user->username }}</h6>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Belum Ada Percakapan
        </div>
    @endforelse
</div>
@endsection