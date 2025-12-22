<a href="{{ route('user.home') }}">
    <button type="submit" class="btn btn-danger">
        Back to Home
    </button>
</a>

<h1>Register</h1>

<div class="container-fluid">
    <form action="{{ route('do-register') }}" method="post">
        @csrf
        Username: <input type="text" name="username" id="username" value="{{ old('username') }}">
        <br>
        @error('username')
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
    <br>
    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif
</div>
