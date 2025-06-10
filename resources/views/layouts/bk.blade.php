<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary : #086805;
            --secondary: #B0CA2D;
            --background : #F2F5E0;
            --accent: #F1EB0F;
            --text-color: #222;
            --white: #fff;
        }        

        body {
            background-color: var(--white);
            font-family: 'Sora', sans-serif;
        }


        .sidebar {
            background-color:var(--white); 
            width: 280px; 
            flex-shrink: 0; 
        }

        .sidebar .nav-link {
            color: var(--text-color);
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            margin: 0.2rem 1rem;
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link i {
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color:var(--background);
            color: var(--primary);
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <nav class="sidebar border-end" id="sidebar-wrapper">
        <div class="sidebar-heading text-center py-4">
            <a href="/">
                <img src="{{ asset('storage/picture/logo.png') }}" alt="Logo SIAS" width="80" class="rounded-circle">
            </a>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('bk.dashboard') }}">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('bk.bolos.index') }}">
                    <i class="bi bi-person-x-fill"></i> Siswa Bolos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('bk.rekap.data') }}">
                    <i class="bi bi-file-earmark-text-fill"></i> Rekap Data
                </a>
            </li>
        </ul>
    </nav>
</body>
</html>
