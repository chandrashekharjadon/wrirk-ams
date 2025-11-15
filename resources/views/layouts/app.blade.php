<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My App')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            min-height: 100vh;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }

    </style>
</head>

<body> 
    <div class="d-flex">
        <!-- Sidebar -->
        @include('includes.sidebar')

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Navbar -->
            @include('includes.navbar')

            <!-- Page Content -->
             <div class="main-content" style="margin-left:  260px;padding: 20px;">
                <div class="p-4" style="background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%); color: #fff; min-height: 100vh; ">
                  @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>

    {{-- Allow page-specific scripts --}}
    @stack('scripts')
</body>
</html>
