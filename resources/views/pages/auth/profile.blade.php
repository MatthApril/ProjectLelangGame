@extends('layouts.template')

@section('title', 'Profile | LelangGame')

@section('content')
    <div class="container-fluid">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-md-2">
                <hr>
                <div class="d-flex align-items-center justify-content-center gap-2">
                    @if ($user->shop && $user->shop->shop_img)
                        <img src="{{ asset('storage/' . $user->shop->shop_img) }}" alt="Foto Toko" width="70" height="70" class="rounded-5">
                    @else
                    <div>
                        <i class="bi bi-person-circle fs-1"></i>
                    </div>
                    @endif
                    <div>
                        <div class="fw-bold">{{ Auth::user()->username }}</div>
                        <a href="{{ route('profile') }}" class="text-decoration-none text-secondary"><i
                                class="bi bi-pencil-square"></i> Edit Profile</a>
                    </div>
                </div>
                <hr>
                @if ($user->role == 'seller')
                    <div class="ms-3">
                        <a href="{{ route('seller.dashboard') }}" class="text-decoration-none text-secondary nav-link link-footer"><i class="bi bi-speedometer2"></i> Dashboard Seller</a>
                        <a href="{{ route('seller.products.index') }}" class="text-decoration-none text-secondary nav-link link-footer mt-2"><i class="bi bi-box-seam"></i> Kelola Produk</a>
                    </div>
                    <hr>
                @endif
            </div>
            <div class="col-md-10 mt-3">
                {{-- @error('username')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @enderror
                @error('email')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @enderror --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="fw-bold">Profile Saya</h4>
                        <p class="text-secondary">Kelola Dan Amankan Akun Anda</p>
                    </div>
                    <div class="card-body">
                        @if ($user->role == 'seller')
                            <form action="{{ route('do-update-shop') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <label>Gambar Toko Baru</label>
                                <input type="file" name="shop_img" id="shop_img" accept="image/*" class="form-control">
                                <p class="mb-3"><i>Format: JPG, PNG, JPEG. Max: 2MB</i></p>
                                {{-- <div class="d-grid">
                                    <button type="button" class="btn btn-outline-secondary rounded-5 my-2"
                                        data-bs-toggle="modal" data-bs-target="#modalEditFotoToko">
                                        <i class="bi bi-image-fill"></i> Ganti Foto Profile Toko
                                    </button>
                                </div> --}}
                                {{-- <input type="hidden" name="shop_img" id="shop_img" value="{{ $user->shop?->shop_img }}" class="form-control mb-3"> --}}

                                <label>Nama Toko</label>
                                <input type="text" name="shop_name" id="shop_name" value="{{ $user->shop?->shop_name }}"
                                    class="form-control mb-3" placeholder="Nama Toko Baru" autocomplete="off" required>

                                <label>Jam Buka</label>
                                <input type="time" name="open_hour" id="open_hour" value="{{ $user->shop?->open_hour }}"
                                    class="form-control mb-3" required>

                                <label>Jam Tutup</label>
                                <input type="time" name="close_hour" id="close_hour"
                                    value="{{ $user->shop?->close_hour }}" class="form-control" required>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-dark rounded-5 my-3"><i
                                            class="bi bi-floppy-fill"></i> Simpan Informasi Toko</button>
                                </div>
                            </form>
                        @endif
                        <form action="{{ route('change-username') }}" method="post">
                            @csrf
                            <label>Username</label>
                            <input type="text" name="username" id="username" value="{{ $user->username }}"
                                class="form-control" placeholder="Username Baru" required>
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-dark my-3"><i class="bi bi-floppy-fill"></i>
                                    Simpan Username</button>
                            </div>
                        </form>
                        <form action="{{ route('verify.store') }}" method="post">
                            @csrf
                            <label>Alamat Email</label>
                            <input type="hidden" name="type" value="change_email">
                            <input type="email" name="email" id="email" value="{{ $user->email }}"
                                placeholder="Email Baru" class="form-control" required>
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-dark mt-3"><i
                                        class="bi bi-floppy-fill"></i> Simpan Email</button>
                            </div>
                        </form>
                        <form action="{{ route('verify.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="reset_password">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-success rounded-5 mt-3">
                                    <i class="bi bi-key-fill"></i> Kirimkan Link Ganti Password
                                </button>
                            </div>
                        </form>
                        @if ($user->role != 'seller')
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-primary rounded-5 mt-3"
                                    data-bs-toggle="modal" data-bs-target="#modalNamaToko">
                                    <i class="bi bi-bag-fill"></i> Mulai Berjualan Di LelangGame
                                </button>
                            </div>
                        @endif
                        <form action="{{ route('logout') }}" method="post">
                            <div class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger rounded-5 mt-3">
                                    <i class="bi bi-box-arrow-left"></i> Keluar Dari Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ModalNamaToko --}}
    <div class="modal fade" id="modalNamaToko" tabindex="-1" aria-labelledby="modalNamaTokoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4 fw-bold" id="modalNamaTokoLabel"><i class="bi bi-bag-fill"></i> Mulai
                        Berjualan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('do-open-shop') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        @error('shop_name')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @enderror
                        @error('shop_img')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @enderror
                        @error('open_hour')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @enderror
                        @error('close_hour')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @enderror
                        <label>Nama Toko</label>
                        <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name') }}"
                            class="form-control mb-3" placeholder="Nama Toko Baru" autocomplete="off" required>
                        <label>Gambar Toko</label>
                        <input type="file" name="shop_img" id="shop_img" accept="image/*" class="form-control">
                        <p class="mb-3"><i>Format: JPG, PNG, JPEG. Max: 2MB</i></p>
                        <label>Jam Buka</label>
                        <input type="time" name="open_hour" id="open_hour" value="{{ old('open_hour') }}"
                            class="form-control mb-3" required>
                        <label>Jam Tutup</label>
                        <input type="time" name="close_hour" id="close_hour" value="{{ old('close_hour') }}"
                            class="form-control mb-3" required>
                        <hr>
                        <button type="submit" class="btn btn-primary float-end mb-3">Kirim Nama Toko <i
                                class="bi bi-caret-right-fill"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ModalEditFotoToko --}}
    {{-- <div class="modal fade" id="modalEditFotoToko" tabindex="-1" aria-labelledby="modalEditFotoTokoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4 fw-bold" id="modalEditFotoTokoLabel"><i class="bi bi-image-fill"></i>
                        Edit Foto Toko</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('do-update-shop') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')

                        @error('shop_img')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @enderror
                        <label>Gambar Toko</label>
                        <input type="file" name="shop_img" id="shop_img" accept="image/*" class="form-control"
                            required>
                        <p class="mb-3"><i>Format: JPG, PNG, JPEG. Max: 2MB</i></p>
                        <hr>
                        <button type="submit" class="btn btn-primary float-end mb-3">Kirim Foto Toko Baru <i
                                class="bi bi-caret-right-fill"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    {{--  --}}
    {{-- <h1>Profile Page</h1>
    <br>

    @if ($user->role == 'seller')
        <form action="{{ route('change-shop-name') }}" method="post">
            @csrf
            Nama Toko:
            <input type="text" name="shop_name" id="shop_name" value="{{ $user->shop?->shop_name }}">
            <button type="submit">Save</button>
        </form>
        <br>
    @else
        <a href="{{ route('open-shop') }}">
            <button class="btn btn-primary">
                Buka Toko
            </button>
        </a>
        <br>
    @endif

    <form action="{{ route('verify.store') }}" method="post">
        @csrf
        Email:
        <input type="hidden" name="type" value="change_email">
        <input type="email" name="email" id="email" value="{{ $user->email }}"
            placeholder="Masukkan email baru">
        <button type="submit">
            Ganti Email Anda
        </button>
        @error('email')
            {{ $message }}
        @enderror
    </form>

    <h4>Ganti Password</h4>
    <form action="{{ route('verify.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="reset_password">
        <button type="submit">
            Ganti Password
        </button>
    </form>

    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>

    @if (session('success'))
        {{ session('success') }}
    @endif

    @if (session('error'))
        {{ session('error') }}
    @endif --}}
@endsection
