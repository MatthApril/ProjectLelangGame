@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('doRegister') }}" method="post">
            @csrf
            OTP: <input type="numeric" name="otp" id="otp"> <br>
            <br>
            <button type="submit">Verify</button>
        </form>
    </div>
@endsection
