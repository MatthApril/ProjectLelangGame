@forelse($comments as $comment)
    <div class="card mb-3">
        <div class="d-flex justify-content-between align-items-center p-3 pb-0">
            <h5 class="fw-semibold">{{ $comment->user->username }}</h5>
            <h5 class="fw-semibold"><i class="bi bi-star-fill text-warning"></i> {{ number_format($comment->rating ?? 0, 1) }} / 5.0</h5>
        </div>
        <p class="text-secondary p-3 pt-0 pb-0 m-0">{{ $comment->product->product_name }}</p>
        <p class="text-secondary p-3 pt-0 pb-0 m-0">{{ $comment->created_at->format('d M Y H:i') }}</p>
        <hr>
        {{-- <h4 class="fw-semibold">{{ $comment->user->username }}</h4>
        <hr> --}}
        <div class="card-body">
            <p>{{ $comment->comment ?? '-' }}</p>
        </div>
    </div>
@empty
    <div class="card p-3">
        <p class="m-0 text-center text-secondary">Belum Ada Ulasan</p>
    </div>
@endforelse
{{-- <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Produk</th>
            <th>Rating</th>
            <th>Komentar</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($comments as $comment)
        <tr>
            <td>{{ ($comments->currentPage() - 1) * $comments->perPage() + $loop->iteration }}</td>
            <td>{{ $comment->user->username }}</td>
            <td>{{ $comment->product->product_name }}</td>
            <td>
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $comment->rating)
                        ⭐
                    @else
                        ☆
                    @endif
                @endfor
                ({{ $comment->rating }}/5)
            </td>
            <td>{{ $comment->comment ?? '-' }}</td>
            <td>{{ $comment->created_at->format('d M Y H:i') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Belum Ada Ulasan</td>
        </tr>
        @endforelse
    </tbody>
</table> --}}