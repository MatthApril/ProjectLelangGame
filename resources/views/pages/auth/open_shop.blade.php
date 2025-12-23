<div>
    <h1>Open Shop</h1>

    <form action="{{ route('do-open-shop') }}" method="post">
        @csrf
        <div>
            <label for="shop_name">Nama Toko *</label>
            <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name') }}" required>
            @error('shop_name')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <br>

        <div>
            <label for="shop_img">Gambar Toko *</label>
            <input type="file" name="shop_img" id="shop_img" accept="image/*" required>
            <p>Format: JPG, PNG, JPEG. Max: 2MB</p>
            @error('shop_img')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <br>

        <div>
            <label for="open_hour">Jam Buka *</label>
            <input type="time" name="open_hour" id="open_hour" value="{{ old('open_hour') }}" required>
            @error('open_hour')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <br>

        <div>
            <label for="close_hour">Jam Tutup *</label>
            <input type="time" name="close_hour" id="close_hour" value="{{ old('close_hour') }}" required>
            @error('close_hour')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <br>

        <button type="submit">Open Shop</button>
        <a href="{{ route('profile') }}">Batal</a>
    </form>
</div>
