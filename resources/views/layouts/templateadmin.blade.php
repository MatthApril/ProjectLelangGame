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

<body>
    @include('partials.headeradmin')
    {{-- navbar --}}
    <div class="d-flex">
        <div class="bg-navyblue text-white py-3 me-3" style="width: 220px; min-height: 100vh;">
            <div class="container-fluid">
                <h6 class="fw-bold">Menu Manajemen</h6>
                
                <a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-white link-navbar {{ Route::is('admin.categories.*') ? 'menu-active' : '' }}">Manage Kategori</a>
                
                <br>

                <a href="{{ route('admin.games.index') }}" class="text-decoration-none text-white link-navbar {{ Route::is('admin.games.index') ? 'menu-active' : '' }}">Manage Game</a>

                <br><br>

                <h6 class="fw-bold">Lainnya</h6>
                <a href="{{ route('admin.games.create') }}" class="text-decoration-none text-white link-navbar {{ Route::is('admin.games.create') ? 'menu-active' : '' }}">Tambah Game Baru</a>
            </div>
        </div>
        <div class="content" style="flex-grow: 1; padding-right: 20px;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(!Route::is('admin.dashboard'))
                        <li class="breadcrumb-item mt-3"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none link-footer">Dashboard</a></li>
                    @endif
                    @if(Route::is('admin.categories.*'))
                        <li class="breadcrumb-item active mt-3">Kategori</li>
                    @elseif(Route::is('admin.games.*'))
                        <li class="breadcrumb-item active mt-3">Game</li>
                    @endif
                    
                    @if(Route::is('*.create'))
                        <li class="breadcrumb-item active mt-3" aria-current="page">Tambah Game Baru</li>
                    @elseif(Route::is('*.edit'))
                        <li class="breadcrumb-item active mt-3" aria-current="page">Edit</li>
                    @endif
                </ol>
            </nav>
            @yield('content')
        </div>
    </div>
    {{-- navbar 2 cuma pake breadcrumbs --}}
    {{-- <div class="content" style="flex-grow: 1; padding-right: 20px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if(!Route::is('admin.dashboard'))
                    <li class="breadcrumb-item mt-3"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none link-footer">Dashboard</a></li>
                @endif
                @if(Route::is('admin.categories.*'))
                    <li class="breadcrumb-item active mt-3">Kategori</li>
                @elseif(Route::is('admin.games.*'))
                    <li class="breadcrumb-item active mt-3">Game</li>
                @endif
                
                @if(Route::is('*.create'))
                    <li class="breadcrumb-item active mt-3" aria-current="page">Tambah Game Baru</li>
                @elseif(Route::is('*.edit'))
                    <li class="breadcrumb-item active mt-3" aria-current="page">Edit</li>
                @endif
            </ol>
        </nav>
        @yield('content')
    </div> --}}
    @include('partials.footeradmin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
    </script>
</body>

</html>
