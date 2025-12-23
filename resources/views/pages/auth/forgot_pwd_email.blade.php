<div>
    <form action="{{ route('forgot-pwd.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="forgot_password">
        Email:
        <input type="email" name="email" id="email" value="{{ old('email') }}"> <br>
        @error('email')
            {{ $message }}
        @enderror
        <br>
        <button type="submit">
            Berikutnya
        </button>
    </form>
</div>
