@extends('layouts.templateadmin')

@section('content')
    <div class="my-3">
        <h5 class="fw-semibold text-dark">Manajemen User</h5>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>

    </div>
@endsection
