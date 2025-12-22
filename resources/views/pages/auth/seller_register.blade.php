<a href="{{ route('user.home') }}">
    <button type="submit" class="btn btn-danger">
        Back to Home
    </button>
</a>

<h1>Seller</h1>
<p>Buat akun user <a href="{{ route('user-register') }}">klik ini</a></p>

<div class="container-fluid">
    <form action="{{ route('doSellerRegister') }}" method="post">
        @csrf
        Username: <input type="text" name="username" id="username" value="{{ old('username') }}">
        <br>
        @error('username')
            {{ $message }}
        @enderror
        <br>
        Nama Toko: <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name') }}">
        <br>
        @error('shop_name')
            {{ $message }}
        @enderror
        <br>
        Email: <input type="email" name="email" id="email" value="{{ old('email') }}">
        <br>
        @error('email')
            {{ $message }}
        @enderror
        <br>
        @error('role')
            {{ $message }}
        @enderror
        <br>
        Password: <input type="password" name="password" id="password" value="{{ old('password') }}"> <br>
        @error('password')
            {{ $message }}
        @enderror
        <br>
        Konfirmasi Password: <input type="password" name="confirm_password" id="confirm_password"
            value="{{ old('confirm_password') }}"> <br>
        @error('confirm_password')
            {{ $message }}
        @enderror
        <br>
        <button type="submit">Register</button>
    </form>

    <a href="{{ route('login') }}">Sudah punya akun?</a>
</div>
