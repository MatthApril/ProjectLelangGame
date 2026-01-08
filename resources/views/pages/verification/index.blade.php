@extends('layouts.templatepolosan')

@section('title', 'Kirim Kode Verifikasi | LelangGame')

@section('content')
    <div class="container my-5">
        <div class="row d-flex justify-content-center">
            <div class="col-sm-12 col-md-8 col-xl-6">
                <div class="card shadow-lg">
                    <div class="card-body text-center">

                        @if (session('login_failed'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ session('login_failed') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 100px; height: 100px;">
                            <i class="bi bi-envelope-check-fill text-primary" style="font-size: 3rem;"></i>
                        </div>

                        <h3 class="fw-semibold">Verifikasi Email Anda</h3>

                        <p class="mx-auto text-secondary" style="max-width: 40rem">
                            Untuk melanjutkan, silakan verifikasi alamat email Anda.
                            Kami akan mengirimkan kode verifikasi ke email dibawah ini.
                        </p>

                        <div class="alert alert-light border mb-4">
                            <i class="bi bi-envelope-at-fill text-primary me-2"></i>
                            <strong>{{ Auth::user()->email }}</strong>
                        </div>

                        <form action="{{ route('verify.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="register">

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-pill py-2">
                                    <i class="bi bi-send-fill me-2"></i>
                                    Kirim Kode Verifikasi
                                </button>
                            </div>
                        </form>

                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Kode verifikasi akan dikirim ke email Anda dalam beberapa detik.
                                Jika tidak menerima email, silakan periksa folder spam.
                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
