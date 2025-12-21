<div class="container-fluid bg-dark">

    @auth
        <h1 class="text-white"> Welcome {{ Auth::user()->username }}</h1>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}">
            <button class="btn btn-primary" type="submit">
                Login
            </button>
        </a>
    @endauth

</div>
