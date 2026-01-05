@extends('layouts.templateadmin')

@section('content')
    <div class="container my-3 text-dark">
        <h5 class="fw-semibold text-dark">User</h5>

        @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
        @endif
        
        @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
        @endif

        <hr>

        <div class="table-responsive">
            <table border="1" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Nama Toko</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->shop ? $user->shop->shop_name : 'N/A' }}</td>
                            <td>{{ $user->created_at->format('d-m-Y') }}</td>
                            <td>
                                @if ($user->username != 'lelanggameofficial')
                                    @if ($user->deleted_at)
                                        <form action="{{ route('admin.users.unban') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->user_id }}">
                                            <button type="submit" class="btn btn-sm btn-success">Unban</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.ban') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->user_id }}">
                                            <button type="submit" class="btn btn-sm btn-danger">Ban</button>
                                        </form>
                                    @endif
                                @else
                                    <span>N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Tidak ada user ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
