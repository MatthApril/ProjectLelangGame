<div>
    <a href="{{ route('user.home') }}">
        <button class="btn btn-danger">
            Back to home
        </button>
    </a>

    <form action="" method="post">
        @csrf
        Username:
        <input type="text" name="username" id="username" value="{{ $user->username }}">
        <br>
        <button type="submit">Save</button>
    </form>

    {{-- @if ($user->role == 'seller')
        <form action="" method="post">
            @csrf
            Nama Toko:
            <input type="text" name="shop_name" id="shop_name" value="{{ $user-> }}">
            <br>
            <button type="submit">Save</button>
        </form>
    @endif --}}

    <form action="{{ route('verify.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="change_email">
        <button type="submit">
            Change Your Email
        </button>
    </form>

    <br>
    <br>

    <form action="{{ route('verify.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="reset_password">
        <button type="submit">
            Change Your Password
        </button>
    </form>

</div>
