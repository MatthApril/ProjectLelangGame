@extends('layouts.templateadmin')

@section('content')
<div class="container my-3 text-dark">
    <h5 class="fw-semibold text-dark">Template Notifikasi</h5>

    <hr>

    <div class="d-flex justify-content-between mb-3">
        <div class="">
            <form action="{{ route('admin.notifications.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari template..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary btn-hover">Cari</button>
            </form>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTemplateNotifikasiBaru">
                +Tambah
            </button><br>
        </div>
    </div>

    <h6 class="fw-bold">Daftar Template Notifikasi (Total: {{ $templates->total() }})</h6>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($templates as $template)
            <div class="col">
                <div class="card h-100 card-custom p-3">
                    <div class="card-body p-2 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if($template->category === 'system')
                                <span class="badge-category bg-warning text-black">
                            @elseif($template->category === 'promo')
                                <span class="badge-category bg-danger text-white">
                            @else
                                <span class="badge-category bg-success text-white">
                            @endif
                                {{ $template->category }}</span>
                            <div class="status-available">
                                <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                            </div>
                        </div>

                        <div class="icon-box bg-primary text-white">
                            <i class="bi bi-bell fs-4"></i>
                        </div>

                        <h5 class="card-title-custom">{{ $template->subject }}</h5>
                        <p class="card-text-muted">{{ $template->body }}</p>
                        
                        <div class="d-flex align-items-center mb-4 text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-tag me-2"></i> {{ $template->code_tag }}
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-generate mb-1 bg-warning text-black"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditTemplateNotifikasi"
                                data-id="{{ $template->notif_temp_id }}"
                                data-code="{{ $template->code_tag }}"
                                data-title="{{ $template->title }}"
                                data-subject="{{ $template->subject }}"
                                data-body="{{ $template->body }}"
                                data-trigger="{{ $template->trigger_type }}"
                                data-category="{{ $template->category }}">
                                Edit Template
                            </button>
                            
                            @if ($template->trigger_type === 'broadcast')
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-generate flex-grow-1 bg-danger text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-id="{{ $template->notif_temp_id }}"
                                    data-name="{{ $template->code_tag }}">
                                    Hapus
                                </button>
                                <button type="button" class="btn btn-generate flex-grow-1 bg-success text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#broadcastModal"
                                    data-id="{{ $template->notif_temp_id }}"
                                    data-name="{{ $template->code_tag }}">
                                    Broadcast
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($templates as $template)
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $template->subject }}</h5>
                        <p class="card-text">{{ $template->body }}</p>
                        <p class="card-text">{{ ucfirst($template->trigger_type) }}</p>
                        <p class="card-text">{{ ucfirst($template->category) }}</p>
                        <button type="button" class="btn btn-sm btn-warning btn-hover edit-btn"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEditTemplateNotifikasi"
                            data-id="{{ $template->notif_temp_id }}"
                            data-code="{{ $template->code_tag }}"
                            data-title="{{ $template->title }}"
                            data-subject="{{ $template->subject }}"
                            data-body="{{ $template->body }}"
                            data-trigger="{{ $template->trigger_type }}"
                            data-category="{{ $template->category }}">
                            Edit
                        </button>
                        @if ($template->trigger_type === 'broadcast')
                            <button type="button" class="btn btn-sm btn-danger btn-hover"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-id="{{ $template->notif_temp_id }}"
                                data-name="{{ $template->code_tag }}">
                                Hapus
                            </button>
                            <button type="button" class="btn btn-sm btn-success btn-hover"
                                data-bs-toggle="modal"
                                data-bs-target="#broadcastModal"
                                data-id="{{ $template->notif_temp_id }}"
                                data-name="{{ $template->code_tag }}">
                                Broadcast
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}

    <div class="d-flex justify-content-between align-items-center">
        <p class="text-muted">
            Menampilkan {{ $templates->firstItem() ?? 0 }} - {{ $templates->lastItem() ?? 0 }} dari {{ $templates->total() }} template
        </p>
        <div>
            {{ $templates->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Template Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus template <strong id="deleteTemplateName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="broadcastModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-megaphone"></i> Broadcast Notifikasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Form Action will be set via JS --}}
            <form id="broadcastForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        Template: <strong id="broadcastTemplateName"></strong>
                    </div>

                    <div class="mb-3">
                        <label for="targetAudience" class="form-label fw-bold">Target Audience</label>
                        <select class="form-select" name="target_audience" id="targetAudience" required>
                            <option value="" selected disabled>-- Pilih Target --</option>
                            {{-- Values must match your NotificationService switch cases --}}
                            <option value="both">Semua User (Pembeli & Penjual)</option>
                            <option value="buyer">Hanya Pembeli (Buyers)</option>
                            <option value="seller">Hanya Penjual (Sellers)</option>
                        </select>
                        <div class="form-text text-muted">
                            Sistem akan mengirim notifikasi ke semua user yang cocok dengan kriteria ini.
                        </div>
                    </div>

                    {{-- Safety Check --}}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmBroadcast" required>
                        <label class="form-check-label" for="confirmBroadcast">
                            Saya yakin ingin mengirim pesan ini.
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send"></i> Kirim Broadcast
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" id="modalTambahTemplateNotifikasiBaru">
  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-semibold text-dark">Buat Template Notifikasi Baru</h5>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="code_tag" class="form-label">Kode Tag</label>
                        <input type="text" class="form-control" id="code_tag" name="code_tag" value="{{ old('code_tag') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Pesan</label>
                        <textarea class="form-control" id="body" name="body" rows="5" required>{{ old('body') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="trigger_type" class="form-label">Tipe Trigger</label>
                        <select class="form-select" id="trigger_type" name="trigger_type" required>
                            <option value="broadcast" {{ (old('trigger_type', $template->trigger_type ?? '') == 'broadcast') ? 'selected' : '' }}>Broadcast</option>
                            <option value="transactional" {{ (old('trigger_type', $template->trigger_type ?? '') == 'transactional') ? 'selected' : '' }}>Transactional</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="system" {{ (old('category', $template->category ?? '') == 'system') ? 'selected' : '' }}>System</option>
                            <option value="promo" {{ (old('category', $template->category ?? '') == 'promo') ? 'selected' : '' }}>Promo</option>
                            <option value="order" {{ (old('category', $template->category ?? '') == 'order') ? 'selected' : '' }}>Order</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalEditTemplateNotifikasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-semibold">Edit Template Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Tag</label>
                        <input type="text" class="form-control bg-light" id="edit_code_tag" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" name="title" id="edit_title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subjek</label>
                        <input type="text" class="form-control" name="subject" id="edit_subject" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-control" name="body" id="edit_body" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Trigger</label>
                        <select class="form-select" name="trigger_type" id="edit_trigger" required>
                            <option value="broadcast">Broadcast</option>
                            <option value="transactional">Transactional</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="category" id="edit_category" required>
                            <option value="system">System</option>
                            <option value="promo">Promo</option>
                            <option value="order">Order</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');

        document.getElementById('deleteTemplateName').textContent = name;
        document.getElementById('deleteForm').action = '/admin/templates/' + id;
    });

    document.getElementById('broadcastModal').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');      // e.g. 5
        var name = button.getAttribute('data-name');  // e.g. promo_new_year

        document.getElementById('broadcastTemplateName').textContent = name;

        var url = "{{ route('admin.notifications.broadcast', ':id') }}";
        url = url.replace(':id', id);

        document.getElementById('broadcastForm').action = url;
    });

    document.getElementById('modalEditTemplateNotifikasi').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        
        var id = button.getAttribute('data-id');
        var code = button.getAttribute('data-code');
        var title = button.getAttribute('data-title');
        var subject = button.getAttribute('data-subject');
        var body = button.getAttribute('data-body');
        var trigger = button.getAttribute('data-trigger');
        var category = button.getAttribute('data-category');

        document.getElementById('edit_code_tag').value = code;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_subject').value = subject;
        document.getElementById('edit_body').value = body;
        document.getElementById('edit_trigger').value = trigger;
        document.getElementById('edit_category').value = category;

        document.getElementById('editForm').action = '/admin/templates/' + id;
    });
</script>
@endsection
