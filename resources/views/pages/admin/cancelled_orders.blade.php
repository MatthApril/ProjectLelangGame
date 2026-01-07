@extends('layouts.templateadmin')

@section('title', 'Pesanan yang Dibatalkan')

@section('content')
    <div class="container my-3 text-dark">
        <h5 class="fw-semibold text-dark">Pesanan yang Dibatalkan</h5>
        <hr>

        @if (session('success'))
            <div>{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div>{{ session('error') }}</div>
        @endif

        <div>
            <h6 class="fw-bold">Statistik Pesanan Dibatalkan</h6>
            <div class="table-responsive">
                <table border="1" class="table table-bordered">
                    <tr>
                        <td><strong>Total Dibatalkan:</strong></td>
                        <td>{{ $totalCancelled }}</td>
                    </tr>
                    <tr>
                        <td><strong>Sudah Refund:</strong></td>
                        <td>{{ $totalRefunded }}</td>
                    </tr>
                    <tr>
                        <td><strong>Belum Refund:</strong></td>
                        <td>{{ $totalNotRefunded }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <form method="GET" action="{{ route('admin.cancelled_orders.index') }}">
            <label><strong>Filter Status Refund:</strong></label>
            <select name="refund_status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="refunded" {{ request('refund_status') === 'refunded' ? 'selected' : '' }}>Sudah Refund
                </option>
                <option value="not_refunded" {{ request('refund_status') === 'not_refunded' ? 'selected' : '' }}>Belum
                    Refund</option>
            </select>
        </form>

        <hr>

        @if ($cancelledOrders->count() > 0)
            <table border="1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Buyer</th>
                        <th>Produk</th>
                        <th>Toko</th>
                        <th>Subtotal</th>
                        <th>Alasan Cancel</th>
                        <th>Status Refund</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cancelledOrders as $item)
                        <tr>
                            <td>{{ ($cancelledOrders->currentPage() - 1) * $cancelledOrders->perPage() + $loop->iteration }}
                            </td>
                            <td>#{{ $item->order_id }}</td>
                            <td>{{ $item->order->account->username }}</td>
                            <td>
                                @if ($item->product->product_img)
                                    <img src="{{ asset('storage/products/' . $item->product->product_img) }}" width="50"
                                        alt="">
                                @endif
                                {{ $item->product->product_name }}
                            </td>
                            <td>{{ $item->shop->shop_name }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td>{{ $item->getCancelReason() }}</td>
                            <td>
                                @if ($item->is_refunded)
                                    <span style="color: green; font-weight: bold;">✓ SUDAH REFUND</span>
                                @else
                                    <span style="color: red; font-weight: bold;">✗ BELUM REFUND</span>
                                @endif
                            </td>
                            <td>{{ $item->paid_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.cancelled_orders.show', $item->order_item_id) }}">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div>
                {{ $cancelledOrders->appends(request()->query())->links() }}
            </div>
        @else
            <div>
                <p>
                    @if (request('refund_status'))
                        Tidak ada pesanan dengan filter "{{ request('refund_status') }}"
                    @else
                        Tidak ada pesanan yang dibatalkan
                    @endif
                </p>
            </div>
        @endif
    </div>
@endsection
