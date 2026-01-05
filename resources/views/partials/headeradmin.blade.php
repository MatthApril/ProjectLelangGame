<nav class="bg-darkblue text-white sticky-top">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-3 py-2">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none">
                    <img src="{{ asset('images/Logo/LogoWarna-RemoveBg.png') }}" alt="LelangGame Logo" width="50">
                    <h5 class="fw-semibold">LelangGame</h5>
                </a>
            </div>
            @auth
                <div class="col-md-5">

                </div>
                <div class="col-md-4 py-2">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <div class="dropdown dropstart">
                            <button class="btn btn-outline-light text-decoration-none text-wrap dropdown-toggle"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->username }}
                            </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person-fill"></i>
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <li>
                                        <button type="submit" class="dropdown-item text-danger"
                                            href="{{ route('logout') }}"><i class="bi bi-box-arrow-left"></i>
                                            Logout
                                        </button>
                                    </li>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-3 py-2">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-light text-decoration-none fw-semibold">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>
{{-- Navbar 2 --}}
{{-- <nav class="bg-navyblue text-white py-2">
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-white link-footer">Manage Kategori</a>
            <a href="{{ route('admin.games.index') }}" class="text-decoration-none text-white link-footer">Manage Game</a>
            <a href="{{ route('admin.games.create') }}" class="text-decoration-none text-white link-footer">Tambah Game Baru</a>
        </div>
    </div>
</nav> --}}
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
