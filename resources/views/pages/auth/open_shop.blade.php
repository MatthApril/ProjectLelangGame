
@extends('layouts.template')

@section('content')
<div class="container">
    <h1>{{ $shop ? 'Edit Toko' : 'Buka Toko Baru' }}</h1>

    @if(session('error'))
        <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ $shop ? route('do-update-shop') : route('do-open-shop') }}" method="post" enctype="multipart/form-data">
        @csrf
        @if($shop)
            @method('PUT')
        @endif

        <div style="margin-bottom: 20px;">
            <label for="shop_name">Nama Toko *</label>
            <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name', $shop->shop_name ?? '') }}" required style="width: 100%; padding: 8px;">
            @error('shop_name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        @if($shop && $shop->shop_img)
        <div style="margin-bottom: 20px;">
            <label>Gambar Toko Saat Ini:</label>
            <br>
            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="Shop Image" width="300">
        </div>
        @endif

        <div style="margin-bottom: 20px;">
            <label for="shop_img">{{ $shop ? 'Upload Gambar Baru (Optional)' : 'Gambar Toko *' }}</label>
            <input type="file" name="shop_img" id="shop_img" accept="image/*" {{ $shop ? '' : 'required' }}>
            <p style="font-size: 12px; color: #666;">Format: JPG, PNG, JPEG. Max: 2MB</p>
            @error('shop_img')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <div style="margin-bottom: 20px;">
            <label for="open_hour">Jam Buka *</label>
            <input type="time" name="open_hour" id="open_hour" value="{{ old('open_hour', $shop->open_hour ?? '') }}" required style="padding: 8px;">
            @error('open_hour')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <div style="margin-bottom: 20px;">
            <label for="close_hour">Jam Tutup *</label>
            <input type="time" name="close_hour" id="close_hour" value="{{ old('close_hour', $shop->close_hour ?? '') }}" required style="padding: 8px;">
            @error('close_hour')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <div style="margin-top: 20px;">
            <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">
                {{ $shop ? 'Update Toko' : 'Buka Toko' }}
            </button>
            <a href="{{ route('profile') }}" style="padding: 10px 20px; display: inline-block; background: #6c757d; color: white; text-decoration: none; margin-left: 10px;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
