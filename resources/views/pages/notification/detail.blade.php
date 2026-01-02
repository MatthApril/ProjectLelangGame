@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4">{{ $notification->title }}</h3>
    <p style="white-space: pre-wrap;">{{ $notification->body }}</p>
    <small class="text-muted">{{ $notification->created_at->format('d M Y, H:i') }}</small>
</div>

@endsection
