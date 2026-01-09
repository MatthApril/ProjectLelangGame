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

                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 100px; height: 100px;">
                            <i class="bi bi-envelope-check-fill text-success" style="font-size: 3rem;"></i>
                        </div>

                        <h3 class="fw-semibold">Masukkan Kode OTP</h3>

                        <p class="mx-auto text-secondary" style="max-width: 40rem">
                            Kami telah mengirimkan kode verifikasi 6 digit ke email Anda
                        </p>

                        <div class="alert alert-light border mb-3">
                            <i class="bi bi-envelope-at-fill text-success me-2"></i>
                            <strong>{{ session('forgot_pwd_email') }}</strong>
                        </div>

                        <form action="{{ route('forgot-pwd.verify.uid', $unique_id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="register">
                            <div class="mb-3">
                                <input type="text"
                                    class="form-control form-control-lg text-center fw-bold fs-3 letter-spacing-wide"
                                    name="otp" id="otp" placeholder="000000" maxlength="6" pattern="[0-9]{6}"
                                    autofocus required style="letter-spacing: 0.5rem;">
                                <small class="text-muted mt-2 d-block">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Masukkan 6 digit kode OTP
                                </small>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success rounded-pill py-2">
                                    <i class="bi bi-check-circle"></i>
                                    Verifikasi
                                </button>
                            </div>
                        </form>

                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted">
                                Tidak menerima kode? Silakan cek folder spam atau kirim ulang kode.
                            </small>
                            <form action="{{ route('forgot-pwd.resend') }}" method="post" class="mt-3">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type }}">
                                <button type="submit" class="btn btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    Kirim Ulang Kode
                                </button>
                            </form>
                            {{-- <div class="mt-3">
                                <small class="text-muted" id="timer-text">
                                    Kode akan kadaluarsa dalam <span id="countdown" class="fw-bold text-success">300</span> detik
                                </small>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        let countdown = 300;
        const countdownElement = document.getElementById('countdown');
        const timerText = document.getElementById('timer-text');
        const resendButton = document.getElementById('resend-btn');

        if (resendButton) {
            resendButton.disabled = true;
        }

        const timer = setInterval(function() {
            countdown--;

            if (countdownElement) {
                countdownElement.textContent = countdown;
            }

            if (countdown <= 0) {
                clearInterval(timer);

                if (timerText) {
                    timerText.classList.add('d-none');
                }

                if (resendButton) {
                    resendButton.disabled = false;
                }
            }
        }, 1000);

        document.getElementById('otp').addEventListener('input', function(e) {
            if (this.value.length === 6) {

            }
        });
    </script> --}}
@endsection
