<a href="{{ route('user.home') }}">
    <button type="submit" class="btn btn-danger">
        Back to Home
    </button>
</a>

<div class="container-fluid">
    <form action="{{ route('do-login') }}" method="post">
        @csrf
        Email: <input type="email" name="email" id="email" value="{{ old('email') }}"> <br>
        @error('email')
            {{ $message }}
        @enderror
        <br>
        Password: <input type="password" name="password" id="password" value="{{ old('password') }}"> <br>
        @error('password')
            {{ $message }}
        @enderror
        <br>
        Remember Me <input type="checkbox" name="remember" id="remember"> <br>
        <button type="submit">Login</button>
    </form>

    <a href="{{ route('register') }}">Belum punya akun?</a>
    <br>
    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif
</div>
