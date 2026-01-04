<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LelangGame')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo/LogoWarna.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    @vite(['resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/palette.css') }}">
</head>

<body class="d-flex flex-column min-vh-100">
    @include('partials.headeradmin')
    {{-- navbar 1 --}}
    <nav class="navbar navbar-dark bg-navyblue d-md-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarAdmin"
                aria-controls="sidebarAdmin">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="d-flex">
        <div class="offcanvas-md offcanvas-start bg-navyblue text-white" tabindex="-1" id="sidebarAdmin"
            style="width: 182px; border: none;">
            <div class="offcanvas-header d-md-none">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    data-bs-target="#sidebarAdmin"></button>
            </div>
            <div class="offcanvas-body position-fixed flex-column py-4 px-4" style="min-height: 100vh;">
                <a href="{{ route('admin.categories.index') }}"
                    class="text-decoration-none text-white d-block mb-2 {{ Route::is('admin.categories.*') ? 'menu-active' : '' }}">Kategori</a>
                <a href="{{ route('admin.games.index') }}"
                    class="text-decoration-none text-white d-block mb-2 {{ Route::is('admin.games.*') ? 'menu-active' : '' }}">Game</a>
                <a href="{{ route('admin.comments.index') }}"
                    class="text-decoration-none text-white d-block mb-2 {{ Route::is('admin.comments.*') ? 'menu-active' : '' }}">Komentar</a>
                <a href="{{ route('admin.notifications.index') }}"
                    class="text-decoration-none text-white d-block {{ Route::is('admin.notifications.*') ? 'menu-active' : '' }}">
                    Notifikasi</a>
            </div>
        </div>
        <div class="content px-4 py-2" style="width: 100%;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if (!Route::is('admin.dashboard'))
                        <li class="breadcrumb-item mt-3"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none">Dashboard</a></li>
                    @endif
                    @if (Route::is('admin.categories.*'))
                        <li class="breadcrumb-item active mt-3">Kategori</li>
                    @elseif(Route::is('admin.games.*'))
                        <li class="breadcrumb-item active mt-3">Game</li>
                    @elseif(Route::is('admin.comments.*'))
                        <li class="breadcrumb-item active mt-3">Komentar</li>
                    @elseif(Route::is('admin.notifications.*'))
                        <li class="breadcrumb-item active mt-3">Notifikasi</li>
                    @elseif(Route::is('profile'))
                        <li class="breadcrumb-item active mt-3">Profile</li>
                    @endif

                    @if (Route::is('*.edit'))
                        <li class="breadcrumb-item active mt-3">Edit</li>
                    @endif
                </ol>
            </nav>
            <main class="flex-grow-1 d-flex flex-column" style="min-height: calc(110vh - 200px);">
                @yield('content')
            </main>
            @include('partials.footeradmin')
        </div>
    </div>
    {{-- navbar 2 cuma pake breadcrumbs --}}
    {{-- <div class="content" style="flex-grow: 1; padding-right: 20px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if (!Route::is('admin.dashboard'))
                    <li class="breadcrumb-item mt-3"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none link-footer">Dashboard</a></li>
                @endif
                @if (Route::is('admin.categories.*'))
                    <li class="breadcrumb-item active mt-3">Kategori</li>
                @elseif(Route::is('admin.games.*'))
                    <li class="breadcrumb-item active mt-3">Game</li>
                @endif

                @if (Route::is('*.create'))
                    <li class="breadcrumb-item active mt-3" aria-current="page">Tambah Game Baru</li>
                @elseif(Route::is('*.edit'))
                    <li class="breadcrumb-item active mt-3" aria-current="page">Edit</li>
                @endif
            </ol>
        </nav>
        @yield('content')
    </div> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
    </script>
</body>

</html>
