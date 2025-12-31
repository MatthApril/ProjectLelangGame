@extends('layouts.templateadmin')

@section('content')
<div class="container my-3">
    <h5 class="fw-semibold text-dark">Manajemen Komentar</h5>

    <div id="alert-container"></div>

    {{-- <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div> --}}

    <hr>

    <form id="filter-form" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <label><strong>Filter Berdasarkan Rating:</strong></label>
                <div class="d-flex flex-wrap gap-2" role="group" aria-label="Filter Rating">
                    <input type="radio" class="btn-check" name="rating" id="rating-all" value="" {{ !request('rating') ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-all">Semua</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-5" value="5" {{ request('rating') == '5' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-5">⭐⭐⭐⭐⭐</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-4" value="4" {{ request('rating') == '4' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-4">⭐⭐⭐⭐</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-3" value="3" {{ request('rating') == '3' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-3">⭐⭐⭐</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-2" value="2" {{ request('rating') == '2' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-2">⭐⭐</label>

                    <input type="radio" class="btn-check" name="rating" id="rating-1" value="1" {{ request('rating') == '1' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="rating-1">⭐</label>
                </div>
            </div>
        </div>
    </form>

    <div id="comments-container">
        @include('partials.comments_table', ['comments' => $comments])
    </div>

    <div id="pagination-container" class="mt-3">
        {{ $comments->links() }}
    </div>
</div>

<script>
document.querySelectorAll('input[name="rating"]').forEach(radio => {
    radio.addEventListener('change', function() {
        filterComments();
    });
});

function filterComments(page = 1) {
    const rating = document.querySelector('input[name="rating"]:checked').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    const params = new URLSearchParams({
        rating: rating,
        page: page
    });
    
    fetch(`{{ route('admin.comments.index') }}?${params}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('comments-container').innerHTML = data.html;
            document.getElementById('pagination-container').innerHTML = data.pagination;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat memfilter komentar');
    });
}

function deleteComment(commentId) {
    if (!confirm('Yakin ingin menghapus komentar ini? Komentar yang dihapus tidak bisa dikembalikan!')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`{{ url('/admin/comments') }}/${commentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            filterComments();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat menghapus komentar');
    });
}

function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}
</script>
@endsection