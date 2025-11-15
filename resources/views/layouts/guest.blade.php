<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%);
            color: #fff;
            overflow-x: hidden;
        }

        .logo-wrapper img {
            max-width: 90px;
            filter: brightness(0) invert(1);
            opacity: 0.9;
            transition: transform 0.3s ease;
        }
        .logo-wrapper img:hover {
            transform: scale(1.05);
            opacity: 1;
        }

        /* Glass effect card */
        .auth-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            color: #fff;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.45);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .auth-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(106, 17, 203, 0.45);
        }

        /* Shared button + input styles */
        .btn-premium {
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            border: none;
            color: #fff;
            border-radius: 999px;
            padding: 8px 18px;
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(255,65,108,0.12);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(255,65,108,0.18);
        }

        .custom-input {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            color: #fff;
            padding: 10px 14px;
        }
        .custom-input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        .custom-input:focus {
            background: rgba(255,255,255,0.12);
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.25rem rgba(106,17,203,0.25);
            color: #fff;
        }

        a {
            color: #a9b4ff;
            text-decoration: none;
        }
        a:hover {
            color: #fff;
            text-decoration: underline;
        }

        footer {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">


        <!-- Page content (single glass card) -->
        <div class="w-100" style="max-width: 420px;">
            <div class="auth-card p-4">
                @yield('content')
            </div>
        </div>

        <footer class="text-center mt-3">
            © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
