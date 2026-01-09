@extends('layouts.templateadmin')

@section('title', 'Laporan Top Seller')

@section('content')
    <div class="container my-3 text-dark">
        <div class="no-print">
            <h5 class="fw-semibold">Laporan Top Seller Dengan Pendapatan Tertinggi</h5>
            <hr>
        </div>

        <div class="card mb-4 no-print">
            <div class="card-body">
                <form action="{{ route('admin.top-seller-report.generate') }}" method="GET">
                    <div class="row">
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
                        <div class="col-md-4">
                            <label class="form-label">Top Seller *</label>
                            <select name="top_limit" class="form-select" required>
                                <option value="">Pilih Top Seller</option>
                                <option value="10" {{ request('top_limit') == '10' ? 'selected' : '' }}>Top 10</option>
                                <option value="15" {{ request('top_limit') == '15' ? 'selected' : '' }}>Top 15</option>
                                <option value="20" {{ request('top_limit') == '20' ? 'selected' : '' }}>Top 20</option>
                            </select>
                        </div>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('admin.top-seller-report.index') }}" class="btn btn-outline-secondary mt-3">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-file-text"></i> Generate Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($topSellers))
            <div class="mb-2 d-flex gap-2 no-print flex-wrap">
                <button onclick="window.print()" class="btn btn-secondary text-nowrap">
                    <i class="bi bi-printer"></i> Cetak
                </button>

                <a href="{{ route('admin.top-seller-report.pdf', request()->all()) }}" class="btn btn-danger text-nowrap">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>

                <a href="{{ route('admin.top-seller-report.excel', request()->all()) }}" class="btn btn-success text-nowrap">
                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                </a>
            </div>

            <div class="print-only mb-3 d-none">
                <h4 class="fw-bold mb-1">Laporan Top {{ $topLimit }} Seller Dengan Pendapatan Tertinggi</h4>
                <p class="mb-0">
                    Periode : {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                    s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                </p>
                <p class="mb-0">Dicetak pada : {{ now()->format('d/m/Y H:i') }}</p>
                <hr>
            </div>

            <div class="row mb-4 no-print">
                <div class="col-md-4 mt-2">
                    <div class="card card-left-primary">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Top Seller</h6>
                            <h3 class="fw-bold text-primary">{{ $topLimit }}</h3>
                            <small class="text-muted">Seller terbaik</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="card card-left-success">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Transaksi</h6>
                            <h3 class="fw-bold text-success">{{ $totalTransactions }}</h3>
                            <small class="text-muted">Transaksi completed</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="card card-left-warning">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Pendapatan</h6>
                            <h3 class="fw-bold text-warning">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <small class="text-muted">Dari semua seller</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Rank</th>
                            <th style="width: 20%;">Nama Toko</th>
                            <th style="width: 15%;">Pemilik</th>
                            <th style="width: 10%;">Total Transaksi</th>
                            <th style="width: 17%;">Total Pendapatan</th>
                            <th style="width: 17%;">Admin Fee</th>
                            <th style="width: 16%;">Pendapatan Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSellers as $index => $shop)
                            <tr>
                                <td>
                                    #{{ $index + 1 }}
                                </td>
                                <td>{{ $shop->shop_name }}</td>
                                <td>{{ $shop->owner->username }}</td>
                                <td>{{ $shop->total_transactions }}</td>
                                <td>Rp {{ number_format($shop->total_revenue, 0, ',', '.') }}</td>
                                <td class="text-info fw-semibold">Rp {{ number_format($shop->admin_fee, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($shop->net_income, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak Ada Data Seller Untuk Periode Yang Dipilih</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Total :</th>
                            <th class="text-center">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                            <th class="text-center text-info">Rp {{ number_format($totalAdminFee, 0, ',', '.') }}</th>
                            <th class="text-center">Rp {{ number_format($totalRevenue - $totalAdminFee, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="alert alert-primary">
                <i class="bi bi-info-circle"></i> Silakan pilih periode dan top seller terlebih dahulu untuk melihat data laporan.
            </div>
        @endif
    </div>
@endsection