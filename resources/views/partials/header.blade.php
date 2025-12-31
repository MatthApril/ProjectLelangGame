<nav class="bg-darkblue text-white">
    <div class="container-fluid">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-3 py-2">
                <a href="{{ route('user.home') }}" class="d-flex align-items-center text-white text-decoration-none">
                    <img src="{{ asset('images/Logo/LogoWarna-RemoveBg.png') }}" alt="LelangGame Logo" width="50">
                    <h5 class="fw-semibold">LelangGame</h5>
                </a>
            </div>
            @auth
                <div class="col-md-5">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            {{-- <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span> --}}
                            <input type="search" class="form-control" name="search" placeholder="Coba Cari Semua Produk" aria-label="Search"
                                autocomplete="off" value="{{ request('search') }}" required>
                                <button type="submit" class="btn btn-light"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 py-3">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <a href="#" class="text-decoration-none text-white"><i class="bi bi-envelope"
                                style="font-size: 1.5rem;"></i></a>
                        <a href="#" class="text-decoration-none text-white"><i class="bi bi-bell"
                                style="font-size: 1.5rem;"></i></a>
                        <a href="{{ route('user.cart') }}" class="text-decoration-none text-white"><i class="bi bi-cart3"
                                style="font-size: 1.5rem;"></i></a>
                        <span class="fs-5"> | </span>
                        <div class="dropdown dropstart">
                            <button class="btn btn-outline-light text-decoration-none text-wrap dropdown-toggle"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->username }}
                            </button>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person-fill"></i>
                                        Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <li><button type="submit" class="dropdown-item text-danger"
                                            href="{{ route('logout') }}"><i class="bi bi-box-arrow-left"></i>
                                            Logout</button></li>
                                </form>
                            </ul>
                        </div>
                        {{-- <a href="{{ route('profile') }}" class="btn btn-outline-light text-decoration-none text-wrap"><i class="bi bi-person-circle"></i> {{ Auth::user()->username }}</a> --}}
                    </div>
                </div>
            @else
                <div class="col-md-6">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            {{-- <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span> --}}
                            <input type="search" class="form-control" name="search" placeholder="Coba Cari Produk" aria-label="Search"
                                autocomplete="off" value="{{ request('search') }}" autofocus required>
                                <button type="submit" class="btn btn-light"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 py-3">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <a href="{{ route('login') }}" class="text-decoration-none text-white"><i class="bi bi-cart3"
                                style="font-size: 1.5rem;"></i></a>
                        <span class="fs-5"> | </span>
                        <a href="{{ route('login') }}" class="btn btn-outline-light text-decoration-none fw-semibold">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>
<nav class="bg-navyblue text-white py-2">
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="dropdown">
                <a href="#" class="text-decoration-none text-white dropdown-toggle" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-grid"></i> Berdasarkan Kategori
                </a>
                <ul class="dropdown-menu">
                    {{-- @foreach ($categories as $category)
                        <li><a class="dropdown-item" href="#">{{ $category->category_name }}</a></li>
                    @endforeach --}}
                </ul>
            </div>
            <a href="{{ route('games.index') }}" class="text-decoration-none text-white"><i class="bi bi-controller"></i> Lihat Semua Game</a>
            <a href="{{ route('products.index') }}" class="text-decoration-none text-white"><i class="bi bi-box-seam"></i> Lihat Semua Produk</a>
            {{-- <a href="#" class="text-decoration-none text-white"><i class="bi bi-steam"></i> Steam Gift Cards</a>
            <a href="#" class="text-decoration-none text-white"><i class="bi bi-gem"></i> Diamond MLBB</a>
            <a href="#" class="text-decoration-none text-white"><i class="bi bi-person-fill"></i> Akun Roblox</a> --}}
        </div>
    </div>
</nav>

{{-- <div class="container-fluid py-2 bg-darkblue">
    @auth
        <h1 class="text-white"> Welcome {{ Auth::user()->username }}</h1>
        <a href="{{ route('profile') }}">
            <button class="btn btn-success" type="submit">
                Profile
            </button>
        </a>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}">
            <button class="btn btn-primary" type="submit">
                Login
            </button>
        </a>
    @endauth
</div> --}}
