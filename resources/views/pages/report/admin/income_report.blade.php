@extends('layouts.templateadmin')

@section('title', 'Laporan Pendapatan Platform')

@section('content')
    <div class="container my-3 text-dark">
        <div class="no-print">
            <h5 class="fw-semibold">Laporan Pendapatan Platform</h5>
            <hr>
        </div>

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
                    <div class="float-end">
                        <a href="{{ route('admin.income-report.index') }}" class="btn btn-outline-secondary mt-3">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-file-text"></i> Generate Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($orders))
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

            <div class="print-only mb-3 d-none">
                <h4 class="fw-bold mb-1">Laporan Pendapatan Platform</h4>
                <p class="mb-0">
                    Periode : {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                    s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                </p>
                <p class="mb-0">Dicetak pada : {{ now()->format('d/m/Y H:i') }}</p>
                <hr>
            </div>

        <div class="row mb-4 no-print">
            <div class="col-md-3 mt-2">
                <div class="card card-left-success">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Transaksi Selesai</h6>
                        <h3 class="fw-bold text-success">{{ $totalTransactions }}</h3>
                        <small class="text-muted">Order completed only</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="card card-left-warning">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Nilai Transaksi</h6>
                        <h3 class="fw-bold text-warning">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <small class="text-muted">Dari transaksi selesai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="card card-left-info">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Pendapatan Admin Fee</h6>
                        <h3 class="fw-bold text-info">Rp{{ number_format($totalAdminFee, 0, ',', '.') }}</h3>
                        <small class="text-muted">Fee platform</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="card card-left-primary">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Pendapatan Seller</h6>
                        <h3 class="fw-bold text-primary">Rp{{ number_format($totalRevenue - $totalAdminFee, 0, ',', '.') }}</h3>
                        <small class="text-muted">Setelah dikurangi fee</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead>
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 10%;">Tanggal</th>
                        <th style="width: 12%;">Order ID</th>
                        <th style="width: 12%;">Pembeli</th>
                        <th style="width: 8%;">Jumlah Item</th>
                        <th style="width: 13%;">Total Transaksi</th>
                        <th style="width: 13%;">Admin Fee</th>
                        <th style="width: 13%;">Pendapatan Seller</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                        @php
                            $netIncome = $order->total_prices - $order->admin_fee;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->order_date }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->account->username }}</td>
                            <td>{{ $order->orderItems->count() }}</td>
                            <td>Rp {{ number_format($order->total_prices, 0, ',', '.') }}</td>
                            <td class="text-info fw-semibold">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($netIncome, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak Ada Transaksi Selesai Untuk Periode Yang Dipilih</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="5" class="text-end">Total :</th>
                        <th class="text-center">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                        <th class="text-center text-info">Rp {{ number_format($totalAdminFee, 0, ',', '.') }}</th>
                        <th class="text-center">Rp {{ number_format($totalRevenue - $totalAdminFee, 0, ',', '.') }}</th>
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