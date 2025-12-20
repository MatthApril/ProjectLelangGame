@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        @csrf
        <form action="{{ route('doLogin') }}" method="post">
            Username: <input type="text" name="username" id="username"> <br>
            Password: <input type="password" name="password" id="password"> <br>
            <button type="submit">Login</button>
        </form>
    </div>
@endsection
