@extends('layouts.templateadmin')

@section('title', 'Tiket Bantuan | Admin LelangGame')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pusat Tiket Bantuan</h4>
            <p class="text-muted mb-0">Kelola dan respons tiket bantuan dari pengguna</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Status Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-clock text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $openCount }}</h3>
                        <small class="text-muted">Menunggu Respons</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-reply text-info" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $answeredCount }}</h3>
                        <small class="text-muted">Sudah Dijawab</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-secondary">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-check-circle text-secondary" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $closedCount }}</h3>
                        <small class="text-muted">Ditutup</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.support.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Tiket</label>
                    <input type="text" class="form-control" id="search" name="search"
                           placeholder="ID Tiket, Subjek, Username, Email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Menunggu Respons</option>
                        <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Sudah Dijawab</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tickets Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="bi bi-ticket-detailed me-2"></i>Daftar Tiket ({{ $tickets->total() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 100px;">ID Tiket</th>
                                <th>Pengguna</th>
                                <th>Subjek</th>
                                <th class="text-center" style="width: 130px;">Status</th>
                                <th style="width: 100px;">Pesan</th>
                                <th style="width: 150px;">Terakhir Update</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                @php
                                    $unreadCount = $ticket->messages->where('receiver_id', Auth::id())->where('is_read', false)->count();
                                @endphp
                                <tr class="{{ $unreadCount > 0 ? 'table-warning' : '' }}">
                                    <td class="ps-4">
                                        <span class="fw-semibold text-primary">#LP-{{ str_pad($ticket->ticket_id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 35px; height: 35px; font-size: 0.9rem;">
                                                {{ strtoupper(substr($ticket->user->username ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $ticket->user->username ?? 'Unknown' }}</strong>
                                                <br><small class="text-muted">{{ $ticket->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ Str::limit($ticket->subject, 40) }}</span>
                                        @if($unreadCount > 0)
                                            <span class="badge bg-danger rounded-pill ms-1">{{ $unreadCount }} baru</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @switch($ticket->status)
                                            @case('open')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock"></i> Menunggu
                                                </span>
                                                @break
                                            @case('answered')
                                                <span class="badge bg-info">
                                                    <i class="bi bi-reply"></i> Dijawab
                                                </span>
                                                @break
                                            @case('closed')
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-check-circle"></i> Ditutup
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $ticket->messages->count() }} pesan</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $ticket->updated_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.support.show', $ticket->ticket_id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="card-footer bg-white">
                    {{ $tickets->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">Tidak Ada Tiket</h5>
                    <p class="text-muted">Belum ada tiket bantuan yang masuk atau sesuai filter.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
