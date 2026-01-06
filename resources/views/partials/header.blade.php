<nav class="bg-darkblue text-white">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-sm-12 col-md-12 col-lg-3 py-2">
                <a href="{{ route('user.home') }}" class="d-flex align-items-center text-white text-decoration-none">
                    <img src="{{ asset('images/Logo/LogoWarna-RemoveBg.png') }}" alt="LelangGame Logo" width="50"
                        class="img-fluid">
                    <h5 class="fw-semibold">LelangGame</h5>
                </a>
            </div>
            @auth
                <div class="col-sm-12 col-md-12 col-lg-5">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            <input type="search" class="form-control" name="search" placeholder="Coba Cari Semua Produk"
                                aria-label="Search" autocomplete="off" value="{{ request('search') }}" required>
                            <button type="submit" class="btn btn-light"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-4 py-3">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <a href="#" class="text-decoration-none text-white"><i class="bi bi-chat-left"
                                style="font-size: 1.5rem;"></i></a>
                        <a href="#" class="text-decoration-none text-white"><i class="bi bi-bell"
                                style="font-size: 1.5rem;"></i></a>
                        <a href="{{ route('user.cart') }}" class="text-decoration-none text-white"><i class="bi bi-cart3"
                                style="font-size: 1.5rem;"></i></a>
                        <span class="fs-5"> | </span>
                        <div class="dropdown dropstart">
                            <button class="btn btn-outline-light text-decoration-none text-wrap dropdown-toggle"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                {{ strlen(Auth::user()->username) > 5 ? substr(Auth::user()->username, 0, 5) . '...' : Auth::user()->username }}
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
                    </div>
                </div>
            @else
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            <input type="search" class="form-control" name="search" placeholder="Coba Cari Produk"
                                aria-label="Search" autocomplete="off" value="{{ request('search') }}" autofocus required>
                            <button type="submit" class="btn btn-light"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-3 py-3">
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
    <div class="container">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <a href="{{ route('user.home') }}" class="text-decoration-none text-white"><i class="bi bi-house"></i>
                Beranda</a>
            <a href="{{ route('games.index') }}" class="text-decoration-none text-white"><i
                    class="bi bi-controller"></i> Semua Game</a>
            <a href="{{ route('products.index') }}" class="text-decoration-none text-white"><i
                    class="bi bi-box-seam"></i> Semua Produk</a>
            <a href="{{ route('auctions.index') }}" class="text-decoration-none text-white"><i
                    class="bi bi-graph-up"></i> Semua Lelang</a>
        </div>
    </div>
</nav>
