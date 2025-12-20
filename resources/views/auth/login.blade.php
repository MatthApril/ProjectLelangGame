@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('doLogin') }}" method="post">
            @csrf
            Email: <input type="email" name="email" id="email"> <br>
            Password: <input type="password" name="password" id="password"> <br>
            <button type="submit">Login</button>
        </form>

        <a href="{{ route('register') }}">Belum punya akun?</a>
    </div>
@endsection
