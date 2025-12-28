<div>
    <a href="{{ route('user.home') }}">
        <button class="btn btn-danger">
            Kembali ke Home
        </button>
    </a>

    <br>
    <h1>Profile Page</h1>
    <form action="{{ route('change-username') }}" method="post">
        @csrf
        Username:
        <input type="text" name="username" id="username" value="{{ $user->username }}">
        <button type="submit">Simpan</button>
        @error('username')
            {{ $message }}
        @enderror
    </form>
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

    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit">Logout</button>
    </form>

    @if (session('success'))
        {{ session('success') }}
    @endif

    @if (session('error'))
        {{ session('error') }}
    @endif

</div>
