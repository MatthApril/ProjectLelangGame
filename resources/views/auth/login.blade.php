@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('doLogin') }}" method="post">
            @csrf
            Email: <input type="email" name="email" id="email"> <br>
            Password: <input type="password" name="password" id="password"> <br>
            Remember Me <input type="checkbox" name="remember" id="remember"> <br>
            <button type="submit">Login</button>
        </form>

        <a href="{{ route('register') }}">Belum punya akun?</a>
        <br>
        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif
    </div>
@endsection
