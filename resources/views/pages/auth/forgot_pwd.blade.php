<div>

    <form action="{{ route('forgot-pwd.update') }}" method="post">
        @csrf
        New Password:
        <input type="password" name="password" id="password"> <br>
        @error('password')
            {{ $message }}
        @enderror
        <br>
        Confirm New Password:
        <input type="password" name="confirm_password" id="confirm_password">
        @error('confirm_password')
            {{ $message }}
        @enderror
        <br>
        <button type="submit">
            Change Password
        </button>
    </form>

</div>
