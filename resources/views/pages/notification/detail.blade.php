@extends('layouts.template')

@section('content')
<div class="container my-4">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('notifications.index') }}">Notifikasi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Notifikasi</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header p-3">
            <h3 class="fw-bold">{{ $notification->title }}</h3>
            <small class="text-muted">{{ $notification->created_at->format('d M Y, H:i') }}</small>
        </div>
        <div class="card-body">
            <p style="white-space: pre-wrap;">{{ $notification->body }}</p>
        </div>
    </div>
</div>

@endsection
