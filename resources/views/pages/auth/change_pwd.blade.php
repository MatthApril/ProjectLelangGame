<div>

    <form action="" method="post">
        @csrf
        <input type="password" name="password" id="password"> <br>
        @error('password')
            {{ $message }}
        @enderror
        <br>
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
