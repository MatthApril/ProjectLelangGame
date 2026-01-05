@extends('layouts.templatepolosan')

@section('title', 'Ganti Password | LelangGame')

@section('content')
    <div class="container">
        <a href="{{ route('profile') }}">
            <button type="submit" class="btn btn-outline-danger rounded-5 px-3 my-3">
                <i class="bi bi-caret-left-fill"></i> Kembali Ke Profile
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
                        <form action="{{ route('change-pwd') }}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="forgot_password">
                            <label>Password Baru : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i
                                        class="bi bi-envelope-at-fill"></i></span>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Password Baru" autocomplete="off" required>
                            </div>
                            <label>Konfirmasi Password Baru : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i
                                        class="bi bi-envelope-at-fill"></i></span>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                    placeholder="Konfirmasi Password Baru" autocomplete="off" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-5 my-3">Ganti Password Baru</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#refresh-captcha').click(function() {
                $.ajax({
                    type: 'GET',
                    url: '/reload-captcha',
                    success: function(data) {
                        $(".captcha img").attr('src', data.captcha);
                    }
                });
            });
        });
    </script>
    </body>

    </html>
@endsection
