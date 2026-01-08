@extends('layouts.template')

@section('title', 'Pusat Bantuan | LelangGame')

@section('content')
<div class="container my-4">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.services') }}">Layanan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pusat Bantuan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pusat Bantuan</h3>
            <p class="text-muted mb-0">Kelola tiket bantuan dan komunikasi dengan tim support kami</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketModal">
            <i class="bi bi-plus-circle"></i> Buat Laporan Baru
        </button>
    </div>

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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terjadi Kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Daftar Tiket Bantuan</h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-secondary">{{ $tickets->count() }} Tiket</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 120px;">ID Tiket</th>
                                <th>Subjek</th>
                                <th class="text-center" style="width: 130px;">Status</th>
                                <th style="width: 180px;">Terakhir Diperbarui</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                @php
                                    $unreadCount = $ticket->messages->where('receiver_id', Auth::id())->where('is_read', false)->count();
                                @endphp
                                <tr class="{{ $unreadCount > 0 ? 'table-primary' : '' }}">
                                    <td class="ps-4">
                                        <span class="fw-semibold text-primary">#LP-{{ str_pad($ticket->ticket_id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="fw-medium">{{ $ticket->subject }}</span>
                                                @if($unreadCount > 0)
                                                    <span class="badge bg-danger rounded-pill ms-2">{{ $unreadCount }} baru</span>
                                                @endif
                                            </div>
                                        </div>
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
                                        <small class="text-muted">
                                            <i class="bi bi-clock-history me-1"></i>
                                            {{ $ticket->updated_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('support.show', $ticket->ticket_id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
                    </div>
                    <h5 class="text-muted">Belum Ada Tiket Bantuan</h5>
                    <p class="text-muted mb-4">Anda belum pernah membuat laporan atau pertanyaan kepada tim support.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                        <i class="bi bi-plus-circle me-1"></i> Buat Laporan Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-4">
                    <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Waktu Respons</h6>
                    <small class="text-muted">Biasanya dalam 1x24 jam kerja</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-4">
                    <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Privasi Terjamin</h6>
                    <small class="text-muted">Data Anda aman bersama kami</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-4">
                    <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">Tim Profesional</h6>
                    <small class="text-muted">Siap membantu kendala Anda</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Ticket Modal --}}
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createTicketModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Buat Laporan Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Tips:</strong> Jelaskan masalah Anda dengan detail agar tim kami dapat membantu dengan cepat dan tepat.
                </div>

                <form action="{{ route('support.store') }}" method="POST" id="ticketForm">
                    @csrf

                    {{-- Category Selection --}}
                    <div class="mb-4">
                        <label for="category" class="form-label fw-semibold">
                            <i class="bi bi-folder me-1"></i>Kategori Masalah <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg @error('category') is-invalid @enderror"
                                id="category" name="category" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($subjectCategories as $category => $subSubjects)
                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                    @switch($category)
                                        @case('Lelang')
                                            {{ $category }}
                                            @break
                                        @case('Pembayaran')
                                            {{ $category }}
                                            @break
                                        @case('Transaksi')
                                            {{ $category }}
                                            @break
                                        @case('Akun')
                                            {{ $category }}
                                            @break
                                        @case('Toko')
                                            {{ $category }}
                                            @break
                                        @case('Lainnya')
                                            {{ $category }}
                                            @break
                                        @default
                                            {{ $category }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Sub-Subject Selection --}}
                    <div class="mb-4">
                        <label for="sub_subject" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i>Jenis Masalah <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('sub_subject') is-invalid @enderror"
                                id="sub_subject" name="sub_subject" required disabled>
                            <option value="">-- Pilih Kategori Terlebih Dahulu --</option>
                        </select>
                        @error('sub_subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pilih jenis masalah yang paling sesuai dengan kendala Anda.</div>
                    </div>

                    {{-- Message Textarea --}}
                    <div class="mb-4">
                        <label for="message" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1"></i>Detail Masalah <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('message') is-invalid @enderror"
                                  id="message" name="message" rows="5"
                                  placeholder="Jelaskan masalah Anda secara detail...&#10;&#10;Contoh:&#10;- Apa yang terjadi?&#10;- Kapan kejadiannya?&#10;- ID Transaksi/Lelang (jika ada)"
                                  required minlength="10" maxlength="2000">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Minimal 10 karakter</small>
                            <small class="text-muted"><span id="charCount">0</span>/2000 karakter</small>
                        </div>
                    </div>

                    {{-- Helpful Tips --}}
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body py-3">
                            <h6 class="card-title mb-2"><i class="bi bi-lightbulb text-warning me-1"></i>Informasi yang Membantu:</h6>
                            <ul class="mb-0 small text-muted">
                                <li>Sertakan <strong>ID Transaksi / ID Lelang</strong> jika terkait dengan transaksi tertentu</li>
                                <li>Jelaskan <strong>kronologi kejadian</strong> secara singkat dan jelas</li>
                                <li>Sebutkan <strong>langkah yang sudah Anda coba</strong> untuk menyelesaikan masalah</li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Batal
                </button>
                <button type="submit" form="ticketForm" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-send me-1"></i>Kirim Laporan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const subSubjectSelect = document.getElementById('sub_subject');
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');

    // Sub-subject options based on category
    const subSubjects = @json($subjectCategories);

    // Handle category change
    categorySelect.addEventListener('change', function() {
        const selectedCategory = this.value;

        // Clear and reset sub-subject dropdown
        subSubjectSelect.innerHTML = '<option value="">-- Pilih Jenis Masalah --</option>';

        if (selectedCategory && subSubjects[selectedCategory]) {
            subSubjectSelect.disabled = false;

            subSubjects[selectedCategory].forEach(function(subSubject) {
                const option = document.createElement('option');
                option.value = subSubject;
                option.textContent = subSubject;

                // Restore old value if exists
                if ('{{ old('sub_subject') }}' === subSubject) {
                    option.selected = true;
                }

                subSubjectSelect.appendChild(option);
            });
        } else {
            subSubjectSelect.disabled = true;
            subSubjectSelect.innerHTML = '<option value="">-- Pilih Kategori Terlebih Dahulu --</option>';
        }
    });

    // Trigger change event if old category value exists
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }

    // Character counter for message
    messageTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;

        if (length > 2000) {
            charCount.classList.add('text-danger');
        } else if (length >= 1800) {
            charCount.classList.remove('text-danger');
            charCount.classList.add('text-warning');
        } else {
            charCount.classList.remove('text-danger', 'text-warning');
        }
    });

    // Initialize character count
    charCount.textContent = messageTextarea.value.length;

    // Form validation before submit
    document.getElementById('ticketForm').addEventListener('submit', function(e) {
        if (!categorySelect.value || !subSubjectSelect.value || messageTextarea.value.length < 10) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang diperlukan.');
            return false;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
    });

    // Show modal if there are validation errors
    @if($errors->any())
        var createTicketModal = new bootstrap.Modal(document.getElementById('createTicketModal'));
        createTicketModal.show();
    @endif
});
</script>
@endsection
