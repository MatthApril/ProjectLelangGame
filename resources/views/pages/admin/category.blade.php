@extends('layouts.templateadmin')

@section('content')
    <div class="container my-3 text-dark">
        <h5 class="fw-semibold text-dark">Kategori</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
            Tambah Kategori Baru
        </button>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <hr>

        {{-- <form
            action="{{ $editCategory ? route('admin.categories.update', $editCategory->category_id) : route('admin.categories.store') }}"
            method="POST">
            @csrf
            @if ($editCategory)
                @method('PUT')
            @endif

            @if (session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif --}}

            {{-- <a href="{{ route('admin.dashboard') }}" class="text-decoration-none link-footer">Kembali ke Dashboard</a> --}}

            @if ($editCategory)
                <h4 class="fw-bold">Edit Kategori</h4>

                <form
                    action="{{ $editCategory ? route('admin.categories.update', $editCategory->category_id) : route('admin.categories.store') }}"
                    method="POST">
                    @csrf
                    @if ($editCategory)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="category_name">Nama Kategori *</label><br>
                        <input type="text" id="category_name" name="category_name"
                            value="{{ old('category_name', $editCategory->category_name ?? '') }}" class="form-control"
                            required>
                        @error('category_name')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    <br>

                    <button type="submit"
                        class="btn btn-primary">{{ $editCategory ? 'Update Kategori' : 'Simpan Kategori' }}</button>

                    @if ($editCategory)
                        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none link-footer">Batal</a>
                    @endif
                </form>
            @else
                <h4 class="fw-bold">Daftar Kategori (Total: {{ $categories->count() }})</h4>

                <div class="table-responsive">
                    <table border="1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $category->category_name }}
                                        @if ($category->deleted_at)
                                            <span style="color: red; font-weight: bold;">(Dihapus)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($category->deleted_at)
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @else
                                            <span class="badge bg-success">Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($category->deleted_at)
                                            <form action="{{ route('admin.categories.restore') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $category->category_id }}">
                                                <button type="submit" onclick="return confirm('Aktifkan kembali kategori ini?')"
                                                    style="padding: 5px 10px; border: 1px solid green; background: green; color: white; border-radius: 5px; cursor: pointer;">
                                                    Aktifkan Kembali
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.categories.edit', $category->category_id) }}"
                                                class="btn btn-primary">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.categories.destroy', $category->category_id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menonaktifkan kategori ini? Produk terkait akan ikut dinonaktifkan.')"
                                                    style="padding: 5px 10px; border: 1px solid red; background: red; color: white; border-radius: 5px; cursor: pointer;">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="border: 1px solid gray; padding: 5px; text-align: center;">
                                        Belum ada kategori
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

    </div>
    <div class="modal fade" tabindex="-1" id="modalTambahKategori">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div>
                            <label for="category_name">Nama Kategori *</label><br>
                            <input type="text" id="category_name" name="category_name"
                                value="{{ old('category_name', $editCategory->category_name ?? '') }}" class="form-control"
                                required><br>
                            @error('category_name')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                            <label for="category_img">Gambar Kategori *</label><br>
                            <input type="file" id="category_img" name="category_img" accept="image/*" required>
                            <p>Format: JPG, PNG, JPEG. Max: 2MB</p>
                            @error('category_img')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
