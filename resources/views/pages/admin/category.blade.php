{{-- filepath: c:\kuliah\semester 3\BWP\project\ProjectLelangGame\resources\views\pages\admin\category.blade.php --}}
@extends('layouts.templateadmin')

@section('content')
    <div class="my-3">
        <h5 class="fw-semibold text-dark">Manajemen Kategori</h5>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        {{-- <a href="{{ route('admin.dashboard') }}" class="text-decoration-none link-footer">Kembali ke Dashboard</a> --}}

        <hr>

        <h6 class="fw-bold">{{ $editCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h6>

        <form action="{{ route('admin.categories.destroy', $category->category_id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding: 5px; border: 1px solid gray; border-radius: 5px;">Hapus</button>
        </form>
        </td>
        </tr>
        @empty
            <tr>
                <td colspan="3" style="border: 1px solid gray; padding: 5px;">Belum ada kategori.</td>
            </tr>
            @endforelse
            </tbody>
            </table>
        </div>
    @endsection
