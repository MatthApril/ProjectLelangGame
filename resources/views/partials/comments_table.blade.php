<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Produk</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Tanggal</th>
                <th>Aksi</th>
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
                <td>
                    <button onclick="deleteComment({{ $comment->comment_id }})" class="btn btn-danger btn-sm">
                        Hapus
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada komentar</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>