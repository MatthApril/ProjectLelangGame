@extends('layouts.template')

@section('title', 'Ticket | LelangGame')

@section('content')
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
