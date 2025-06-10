<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        /* CSS dari halaman login Anda telah dipindahkan ke sini */
        body {
            background: radial-gradient(circle, #d4ffb2, #c1f0a6, #a0db76, #95cc3e);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .login-card {
            background-color: #f5fce8;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }

        .login-card img {
            width: 100px;
            margin-bottom: 20px;
        }

        .login-card h4 {
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px;
        }

        .btn-green {
            background-color: #0a7502;
            color: white;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
        }

        .btn-green:hover {
            background-color: #065b00;
            color: white;
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .forgot-password {
            font-size: 0.9rem;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>