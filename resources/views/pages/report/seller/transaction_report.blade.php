@extends('layouts.template')

@section('title', 'Laporan Transaksi | LelangGame')

@section('content')
    <div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="no-print">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Transaksi</li>
            </ol>
        </nav>

        <div class="no-print">
            <h2 class="fw-semibold">Laporan Transaksi</h2>
            <hr>
        </div>

        <div class="print-only mb-3 d-none">
            <h4 class="fw-bold mb-1">Laporan Transaksi</h4>
            <p class="mb-0">
                Periode :
                {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }}
                s/d
                {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}
            </p>
            <p class="mb-0">
                Status :
                @if(!$request->status || $request->status == 'all')
                    Semua
                @elseif($request->status == 'paid')
                    Dibayar
                @elseif($request->status == 'shipped')
                    Dikirim
                @elseif($request->status == 'completed')
                    Selesai
                @elseif($request->status == 'cancelled')
                    Dibatalkan
                @endif
            </p>
            <p class="mb-0">
                Dicetak pada : {{ now()->format('d/m/Y H:i') }}
            </p>
            <hr>
        </div>

        <div class="card mb-4 no-print">
            <div class="card-body">
                <form action="{{ route('seller.transaction-report.generate') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ $request->start_date ?? now()->startOfMonth()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ $request->end_date ?? now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ ($request->status ?? 'all') == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="paid" {{ ($request->status ?? '') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                                <option value="shipped" {{ ($request->status ?? '') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="completed" {{ ($request->status ?? '') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ ($request->status ?? '') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('seller.transaction-report.index') }}" class="btn btn-outline-secondary mt-3">
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

                <a href="{{ route('seller.transaction-report.pdf', request()->all()) }}" class="btn btn-danger text-nowrap">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>

                <a href="{{ route('seller.transaction-report.excel', request()->all()) }}" class="btn btn-success text-nowrap">
                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                </a>
            </div>

            <div class="row mb-4 no-print">
                <div class="col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Total Transaksi</h6>
                            <h3 class="fw-bold">{{ $totalTransactions }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Total Pendapatan</h6>
                            <h3 class="fw-bold text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Dalam Proses</h6>
                            <h3 class="fw-bold text-warning">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Dibatalkan</h6>
                            <h3 class="fw-bold text-danger">{{ $totalCancelled }}</h3>
                        </div>
                    </div>
                </div>
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