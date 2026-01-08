@extends('layouts.templateadmin')

@section('title', 'Laporan Transaksi Seller')

@section('content')
    <div class="container">
        <div class="no-print">
            <h2 class="fw-semibold">Laporan Transaksi Seller</h2>
            <hr>
        </div>

        <div class="card mb-4 no-print">
            <div class="card-body">
                <form action="{{ route('admin.transaction-report-seller.generate') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Pilih Seller *</label>
                            <select name="seller_id" id="seller_id" class="form-select" required>
                                <option value="">Pilih Seller</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->user_id }}" 
                                        {{ (request('seller_id') == $seller->user_id) ? 'selected' : '' }}>
                                        {{ $seller->shop->shop_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Mulai *</label>
                            <input type="date" name="start_date" class="form-control" 
                                value="{{ request('start_date') ?? now()->startOfMonth()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Akhir *</label>
                            <input type="date" name="end_date" class="form-control" 
                                value="{{ request('end_date') ?? now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('admin.transaction-report-seller.index') }}" class="btn btn-outline-secondary mt-3">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-file-text"></i> Generate Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($orderItems))
            <div class="mb-2 d-flex gap-2 no-print flex-wrap">
                <button onclick="window.print()" class="btn btn-secondary text-nowrap">
                    <i class="bi bi-printer"></i> Cetak
                </button>

                <a href="{{ route('admin.transaction-report-seller.pdf', request()->all()) }}" class="btn btn-danger text-nowrap">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>

                <a href="{{ route('admin.transaction-report-seller.excel', request()->all()) }}" class="btn btn-success text-nowrap">
                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                </a>
            </div>

            <div class="print-only mb-3 d-none">
                <h4 class="fw-bold mb-1">Laporan Transaksi Seller</h4>
                <p class="mb-0">Seller : {{ $seller->shop->shop_name }}</p>
                <p class="mb-0">
                    Periode : {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                    s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                </p>
                <p class="mb-0">Dicetak pada : {{ now()->format('d/m/Y H:i') }}</p>
                <hr>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Order ID</th>
                                    <th>Pembeli</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orderItems as $index => $item)
                                    <tr>
                                        <td>{{ $item->paid_at ? $item->paid_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td>{{ $item->order_id }}</td>
                                        <td>{{ $item->order->account->username }}</td>
                                        <td>{{ $item->product->product_name ?? 'Produk Dihapus' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        <td>
                                            @if($item->status == 'paid')
                                                <span>Dibayar</span>
                                            @elseif($item->status == 'shipped')
                                                <span>Dikirim</span>
                                            @elseif($item->status == 'completed')
                                                <span>Selesai</span>
                                            @elseif($item->status == 'cancelled')
                                                <span>Dibatalkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak Ada Data Transaksi Untuk Periode Yang Dipilih</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
            </div>
        @else
            <div class="alert alert-primary">
                <i class="bi bi-info-circle"></i> Silakan pilih periode terlebih dahulu dan generate laporan untuk melihat data transaksi.
            </div>
        @endif
    </div>
@endsection