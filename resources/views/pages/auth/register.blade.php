<a href="{{ route('user.home') }}">
    <button type="submit" class="btn btn-danger">
        Back to Home
    </button>
</a>

<div class="container-fluid">
    <form action="{{ route('doRegister') }}" method="post">
        @csrf
        Username: <input type="text" name="username" id="username">
        <br>
        @error('username')
            {{ $message }}
        @enderror
        <br>
        Email: <input type="email" name="email" id="email">
        <br>
        @error('email')
            {{ $message }}
        @enderror
        <br>
        Role:
        <select name="role" id="role">
            <option value="user">Pembeli</option>
            <option value="seller">Penjual</option>
        </select>
        <br>
        @error('role')
            {{ $message }}
        @enderror
        <br>
        Password: <input type="password" name="password" id="password"> <br>
        @error('password')
            {{ $message }}
        @enderror
        <br>
        Konfirmasi Password: <input type="password" name="confirm_password" id="confirm_password"> <br>
        @error('confirm_password')
            {{ $message }}
        @enderror
        <br>
        <button type="submit">Register</button>
    </form>

    <a href="{{ route('login') }}">Sudah punya akun?</a>
</div>
