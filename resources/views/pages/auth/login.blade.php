@extends('layouts.templatepolosan')

@section('title', 'Masuk | LelangGame')

@section('content')
    <div class="container-fluid">
        <a href="{{ route('user.home') }}">
            <button type="submit" class="btn btn-outline-danger rounded-5 px-3 my-3">
                <i class="bi bi-caret-left-fill"></i> Kembali Ke Beranda
            </button>
        </a>
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ asset('images/Logo/LogoWarna-RemoveBg.png') }}" alt="LelangGame Logo"
                                width="100">
                            <h1 class="card-title text-center mb-4 fw-bold">LelangGame</h1>
                        </div>
                        <form action="{{ route('do-login') }}" method="post">
                            @csrf
                            <label>Alamat Email : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i
                                        class="bi bi-envelope-at-fill"></i></span>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Email" autocomplete="off" required>
                            </div>
                            <label>Password : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Password" autocomplete="off" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="remember"
                                        id="remember">
                                    <label class="form-check-label" for="remember">
                                        Ingat Saya
                                    </label>
                                </div>
                                <a href="#" class="text-decoration-none text-secondary">Lupa Password?</a>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-5 my-3">Masuk</button>
                            </div>
                            <hr>
                            <div class="text-center my-3">Belum Punya Akun? <a href="{{ route('register') }}">Daftar Sekarang</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection