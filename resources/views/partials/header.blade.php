<div class="container-fluid bg-dark">
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>

    <a href="{{ route('login') }}">Login</a>
</div>
