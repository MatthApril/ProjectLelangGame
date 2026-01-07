@extends('layouts.templateadmin')

@section('title', 'Laporan Pendapatan Platform')

@section('content')
    <div class="container">
        <div class="no-print">
            <h2 class="fw-semibold">Laporan Pendapatan Platform</h2>
            <hr>
        </div>

        {{-- Filter Form --}}
        <div class="card mb-4 no-print">
            <div class="card-body">
                <form action="{{ route('admin.income-report.generate') }}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai *</label>
                            <input type="date" name="start_date" class="form-control" 
                                value="{{ request('start_date') ?? now()->startOfMonth()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir *</label>
                            <input type="date" name="end_date" class="form-control" 
                                value="{{ request('end_date') ?? now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mt-3 float-end">
                        <a href="{{ route('admin.income-report.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-file-text"></i> Generate Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($orderItems))
            {{-- Download buttons --}}
            <div class="mb-2 d-flex gap-2 no-print flex-wrap">
                <button onclick="window.print()" class="btn btn-secondary text-nowrap">
                    <i class="bi bi-printer"></i> Cetak
                </button>

                <a href="{{ route('admin.income-report.pdf', request()->all()) }}" class="btn btn-danger text-nowrap">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>

                <a href="{{ route('admin.income-report.excel', request()->all()) }}" class="btn btn-success text-nowrap">
                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                </a>
            </div>

            {{-- Print header --}}
            <div class="print-only mb-3 d-none">
                <h4 class="fw-bold mb-1">Laporan Pendapatan Platform</h4>
                <p class="mb-0">
                    Periode : {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                    s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                </p>
                <p class="mb-0">Dicetak pada : {{ now()->format('d/m/Y H:i') }}</p>
                <hr>
            </div>

            {{-- Statistik Pendapatan --}}
            <div class="row mb-4 no-print">
                <div class="col-md-3">
                    <div class="card card-left-primary">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Transaksi</h6>
                            <h3 class="fw-bold text-primary">{{ $totalTransactions }}</h3>
                            <small class="text-muted">Semua transaksi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-left-success">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Transaksi Selesai</h6>
                            <h3 class="fw-bold text-success">{{ $completedOrders }}</h3>
                            <small class="text-muted">Order completed</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-left-warning">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Nilai Transaksi</h6>
                            <h3 class="fw-bold text-warning">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <small class="text-muted">Transaksi selesai</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-left-info">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Pendapatan Admin Fee</h6>
                            <h3 class="fw-bold text-info">Rp{{ number_format($totalAdminFee, 0, ',', '.') }}</h3>
                            <small class="text-muted">Dari transaksi selesai</small>
                        </div>
                    </div>
                </div>
            </div>

        {{-- Tabel Transaksi --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead>
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 10%;">Tanggal</th>
                        <th style="width: 12%;">Order ID</th>
                        <th style="width: 15%;">Toko</th>
                        <th style="width: 12%;">Pembeli</th>
                        <th style="width: 20%;">Produk</th>
                        <th style="width: 5%;">Qty</th>
                        <th style="width: 13%;">Subtotal</th>
                        <th style="width: 10%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orderItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->paid_at ? $item->paid_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $item->order_id }}</td>
                            <td class="text-left">{{ $item->product->shop->shop_name ?? 'Toko Dihapus' }}</td>
                            <td>{{ $item->order->account->username }}</td>
                            <td class="text-left">{{ $item->product->product_name ?? 'Produk Dihapus' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td>
                                @if($item->status == 'paid')
                                    <span class="badge bg-info">Dibayar</span>
                                @elseif($item->status == 'shipped')
                                    <span class="badge bg-primary">Dikirim</span>
                                @elseif($item->status == 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($item->status == 'cancelled')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data transaksi untuk periode yang dipilih</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="7" class="text-end">Total Pendapatan (Selesai):</th>
                        <th colspan="2" class="text-center">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-end">Admin Fee Platform:</th>
                        <th colspan="2" class="text-center text-info">Rp {{ number_format($totalAdminFee, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
            <div class="alert alert-primary">
                <i class="bi bi-info-circle"></i> Silakan pilih periode terlebih dahulu dan generate laporan untuk melihat data pendapatan.
            </div>
        @endif
    </div>
@endsection