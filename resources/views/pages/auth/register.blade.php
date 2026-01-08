@extends('layouts.templatepolosan')

@section('title', 'Daftar | LelangGame')

@section('content')
    <div class="container mb-sm-5">
        <a href="{{ route('user.home') }}">
            <button type="submit" class="btn btn-outline-danger rounded-5 px-3 my-3">
                <i class="bi bi-caret-left-fill"></i> Kembali Ke Beranda
            </button>
        </a>
        <div class="row d-flex justify-content-center">
            <div class="col-sm-12 col-md-8 col-xl-6">
                {{-- Session Error Messages --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Session Success Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $error }} <br>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ asset('images/Logo/LogoWarna-RemoveBg.png') }}" alt="LelangGame Logo"
                                width="100" class="img-fluid">
                            <h1 class="card-title text-center mb-4 fw-bold">LelangGame</h1>
                        </div>
                        <form action="{{ route('do-register') }}" method="post">
                            @csrf
                            <label>Username : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control" name="username" id="username"
                                    placeholder="Username" autocomplete="off" value="{{ old('username') }}" required>
                            </div>
                            <label>Alamat Email : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i
                                        class="bi bi-envelope-at-fill"></i></span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Alamat Email"
                                    autocomplete="off" value="{{ old('email') }}" required>
                            </div>
                            <label>Nomor Rekening : </label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-bank2"></i></span>
                                <input type="number" class="form-control" name="bank_account_number"
                                    id="bank_account_number" placeholder="Nomor Rekening" autocomplete="off"
                                    value="{{ old('bank_account_number') }}" required>
                                </div>
                            <p class="m-0 mb-3"><i>Wajib Nomor Rekening <strong>Bank BCA</strong> Untuk Pencairan Dana</i></p>
                            <label>Password : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Password" autocomplete="off" required>
                            </div>
                            <label>Konfirmasi Password : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                    placeholder="Konfirmasi Password" autocomplete="off" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Setuju Dengan <a href="" data-bs-toggle="modal" data-bs-target="#termsConditions">Syarat & Ketentuan</a> Yang Berlaku
                                </label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-5 my-3">Daftar</button>
                            </div>
                            <hr>
                        </form>
                        <div class="text-center my-3">Sudah Punya Akun? <a href="{{ route('login') }}">Masuk
                                Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Terms Conditions Modal -->
    <div class="modal fade" id="termsConditions" tabindex="-1" aria-labelledby="termsConditionsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5 fw-bold" id="termsConditionsLabel">Syarat & Ketentuan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0" style="height: 500px;">
                <iframe 
                    src="{{ asset('SyaratDanKetentuanLelangGame.pdf') }}#toolbar=0&navpanes=0&scrollbar=1"
                    width="100%"
                    height="100%"
                    style="border: none;">
                </iframe>
        </div>
        </div>
    </div>
    </div>
@endsection
