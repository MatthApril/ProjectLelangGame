@extends('layouts.templateadmin')

@section('content')
    <div class="container my-3 text-dark">
        <h5 class="fw-semibold text-dark">Pencairan Saldo</h5>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <hr>
    </div>

    <div class="container mb-5">
        @if ($withdraws->count() == 0)
            <p>Tidak ada permintaan pencairan saldo.</p>
        @else
            @foreach ($withdraws as $withdraw)
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text"><strong>Toko:</strong> {{ $withdraw->shop->shop_name }}</p>
                        <p class="card-text"><strong>Jumlah:</strong> Rp {{ number_format($withdraw->amount, 0, ',', '.') }}
                        </p>
                        <p class="card-text"><strong>Status:</strong>
                            @if ($withdraw->status === 'waiting')
                                <span class="badge bg-warning text-dark">Waiting</span>
                            @elseif ($withdraw->status === 'done')
                                <span class="badge bg-success">Completed</span>
                            @elseif ($withdraw->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </p>
                        <p class="card-text"><small class="text-muted">Tanggal Permintaan:
                                {{ $withdraw->created_at->format('d M Y H:i') }}</small></p>
                    </div>
                    <div class="card-footer">
                        @if ($withdraw->status == 'waiting')
                            <form action="{{ route('admin.withdraws.process', $withdraw->withdraw_id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">
                                    Reject
                                </button>
                            </form>
                            <form action="{{ route('admin.withdraws.process', $withdraw->withdraw_id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">
                                    Approve
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <div class="d-flex justify-content-center">
            {{ $withdraws->links() }}
        </div>
    </div>
@endsection
