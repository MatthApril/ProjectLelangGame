<table class="table table-bordered table-striped">
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
            <td colspan="6" class="text-center">Belum ada ulasan</td>
        </tr>
        @endforelse
    </tbody>
</table>
