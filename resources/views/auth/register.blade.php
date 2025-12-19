@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('doRegister') }}" method="post">
            @csrf
            Username: <input type="text" name="username" id="username"> <br>
            Password: <input type="password" name="password" id="password"> <br>
            Email: <input type="email" name="email" id="email"> <br>
            Role:
            <select name="role" id="role">
                <option value="user">Pembeli</option>
                <option value="seller">Penjual</option>
            </select>
            <br>
            <button type="submit">Register</button>
        </form>
    </div>
@endsection
