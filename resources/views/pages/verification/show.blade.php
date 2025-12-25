@extends('layouts.templatepolosan')

@section('title', 'Verifikasi | LelangGame')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 80vh">
        <div class="row">
            <form action="{{ route('verify.uid', $unique_id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="col-md-12 d-flex gap-2 text-nowrap">
                    <input type="number" class="form-control" name="otp" id="otp" placeholder="OTP!" required>
                    <button type="submit" class="btn btn-success">
                        Kirim <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        
            <form action="{{ route('verify.store') }}" method="post">
                @csrf
                <div class="col-md-12 mt-3 d-grid">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="btn btn-outline-primary rounded-5 px-5">
                        <i class="bi bi-key-fill"></i> Resend OTP
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
