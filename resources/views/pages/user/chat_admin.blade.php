@extends('layouts.template')

@section('title', 'Ticket | LelangGame')

@section('content')
<a href="#" id="btnScrollTop" class="btn btn-primary position-fixed rounded-5 btn-lg fw-bold bottom-0 end-0 mb-5 me-3 fs-3" style="z-index: 9999; display: none;"><i class="bi bi-arrow-up"></i></a>
<div class="container my-4">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item>"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item>"><a href="{{ route('user.services') }}">Layanan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Hubungi Kita</li>
        </ol>
    </nav>
    <h3 class="fw-bold mb-4">Hubungi Kita</h3>
    

</div>
@endsection
