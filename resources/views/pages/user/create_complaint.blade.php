@extends('layouts.template')

@section('title', 'Ajukan Komplain')

@section('content')
<div>
    <h2>Ajukan Komplain</h2>
    <hr>

    @if(session('error'))
        <div>
            {{ session('error') }}
        </div>
    @endif

    <div>
        <h4>Detail Produk</h4>
        <table>
            <tr>
                <td>
                    @if($orderItem->product->product_img)
                        <img src="{{ asset('storage/' . $orderItem->product->product_img) }}" alt="{{ $orderItem->product->product_name }}" width="100" height="100">
                    @endif
                </td>
                <td>
                    <p><strong>Nama Produk:</strong> {{ $orderItem->product->product_name }}</p>
                    <p><strong>Toko:</strong> {{ $orderItem->shop->shop_name }}</p>
                    <p><strong>Jumlah:</strong> {{ $orderItem->quantity }}</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <h4>Form Komplain</h4>
        <form action="{{ route('user.complaints.store', $orderItem->order_item_id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div>
                <label><strong>Deskripsi Masalah *</strong></label>
                <textarea name="description" rows="6" required maxlength="1000" placeholder="Jelaskan masalah yang Anda alami dengan detail (minimal 20 karakter)">{{ old('description') }}</textarea>
                @error('description')
                    <small>{{ $message }}</small>
                @enderror
                <small>Minimal 20 karakter, maksimal 1000 karakter</small>
            </div>

            <div>
                <label><strong>Bukti Foto *</strong></label>
                <input type="file" name="proof_img" accept="image/jpeg,image/png,image/jpg" required>
                @error('proof_img')
                    <small>{{ $message }}</small>
                @enderror
                <small>Format: JPG, PNG, JPEG | Maksimal 2MB</small>
            </div>

            <div>
                <strong>⚠️ Perhatian:</strong>
                <ul>
                    <li>Komplain hanya bisa diajukan <strong>1 kali</strong> per produk</li>
                    <li>Pastikan deskripsi dan bukti <strong>jelas dan valid</strong></li>
                    <li>Seller akan merespons paling lambat dalam <strong>3x24 jam</strong></li>
                    <li>Admin akan memutuskan hasil akhir setelah meninjau bukti dari kedua pihak</li>
                </ul>
            </div>

            <button type="submit" onclick="return confirm('Pastikan data yang Anda masukkan sudah benar. Ajukan komplain?')">
                Ajukan Komplain
            </button>
            <a href="{{ route('user.orders.detail', $orderItem->order_id) }}">
                Batal
            </a>
        </form>
    </div>
</div>
@endsection