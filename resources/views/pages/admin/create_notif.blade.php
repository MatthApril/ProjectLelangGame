@extends('layouts.templateadmin')

@section('content')
<div class="container my-3 text-dark">
    <h5 class="fw-semibold text-dark">Buat Template Notifikasi Baru</h5>

    <hr>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $template ? route('admin.notifications.update', $template->notif_temp_id) : route('admin.notifications.store') }}" method="POST">
        @csrf
        @if($template)
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="code_tag" class="form-label">Kode Tag</label>
            <input type="text" class="form-control" id="code_tag" name="code_tag" value="{{ old('code_tag', $template->code_tag ?? '') }}" {{ $template ? 'disabled' : '' }} required>
        </div>
        <div class="mb-3">
            <label for="subject" class="form-label">Judul</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $template->title ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="subject" class="form-label">Subjek</label>
            <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $template->subject ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Pesan</label>
            <textarea class="form-control" id="body" name="body" rows="5" required>{{ old('body', $template->body ?? '') }}</textarea>
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
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

@endsection
