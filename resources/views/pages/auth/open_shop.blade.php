<div>
    <h1>Open Shop</h1>

    <form action="{{ route('do-open-shop') }}" method="post">
        @csrf
        Nama Toko: <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name') }}">
        <br>
        @error('shop_name')
            {{ $message }}
        @enderror
        <br>
        <button type="submit">Open Shop</button>
    </form>
</div>
