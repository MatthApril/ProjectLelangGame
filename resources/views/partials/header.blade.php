<div class="container-fluid bg-dark d-flex py-3">

    @auth
        <h1 class="text-white"> Welcome {{ Auth::user()->username }}</h1>
        <div class="mt-2 ms-auto d-flex gap-3">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
            <a href="{{ route('profile') }}">
                <button class="btn btn-success">
                    Profile
                </button>
            </a>
        </div>
    @else
        <a href="{{ route('login') }}">
            <button class="btn btn-primary" type="submit">
                Login
            </button>
        </a>
    @endauth

</div>
