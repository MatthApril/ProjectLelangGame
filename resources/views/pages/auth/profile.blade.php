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

    <a href="">
        <button>
            Change Your Email
        </button>
    </a>

    <br>
    <br>

    <a href="">
        <button>
            Change Your Password
        </button>
    </a>

</div>
