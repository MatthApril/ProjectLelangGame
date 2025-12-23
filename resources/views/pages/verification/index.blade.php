<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    @if (session('login_failed'))
        <p>{{ session('login_failed') }}</p>
    @endif

    <form action="{{ route('verify.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="register">
        <button type="submit">
            Send OTP to your email
        </button>
    </form>

</body>

</html>
