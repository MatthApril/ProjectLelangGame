@extends('layouts.templatepolosan')

@section('title', 'Verifikasi | LelangGame')

@section('content')
    @if (session('login_failed'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('login_failed') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 80vh">
        <form action="{{ route('verify.store') }}" method="post">
            @csrf
            <input type="hidden" name="type" value="register">
            <button type="submit" class="btn btn-outline-primary rounded-5 px-5 py-3 fs-5">
                <i class="bi bi-envelope-at-fill"></i> Kirimkan Kode Verifikasi Ke Email Saya
            </button>
        </form>
    </div>
@endsection
