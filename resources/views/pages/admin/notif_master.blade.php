@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h1 class="fw-bold">Template Notifikasi</h1>

    <hr>

    <div class="d-flex justify-content-between mb-3">
        <div class="">
            <form action="{{ route('admin.notifications.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari template..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary btn-hover">Cari</button>
            </form>
        </div>
        <div>
            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary btn-hover" role="button">+ Tambah</a>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Template ID</th>
                <th>Kode Tag</th>
                <th>Judul</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $template)
            <tr>
                <td class="text-center align-middle">{{ $template->notif_temp_id }}</td>
                <td>{{ $template->code_tag }}</td>
                <td>{{ $template->title }}</td>
                <td>{{ $template->subject }}</td>
                <td style="white-space: pre-wrap;">{{ $template->body }}</td>
                <td>{{ ucfirst($template->trigger_type) }}</td>
                <td>{{ ucfirst($template->category) }}</td>
                <td>
                    <a href="{{ route('admin.notifications.edit', $template->notif_temp_id) }}" class="btn btn-sm btn-warning btn-hover">Edit</a>
                    @if ($template->trigger_type === 'broadcast')
                        <button type="button" class="btn btn-sm btn-danger btn-hover"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                            data-id="{{ $template->notif_temp_id }}"
                            data-name="{{ $template->code_tag }}">
                            Hapus
                        </button>
                        <button type="button" class="btn btn-sm btn-success btn-hover mb-1"
                            data-bs-toggle="modal"
                            data-bs-target="#broadcastModal"
                            data-id="{{ $template->notif_temp_id }}"
                            data-name="{{ $template->code_tag }}">
                            Broadcast
                        </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

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
</script>
@endsection
