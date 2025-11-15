<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Employee Attendance Portal') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, #1e1f29 0%, #2d1b69 50%, #6a11cb 100%);
            overflow-x: hidden;
            color: #fff;
            position: relative;
        }

        /* Background Image */
        .hero-bg {
            background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
            filter: brightness(0.75);
            position: absolute;
            inset: 0;
            z-index: -2;
        }

        .overlay {
            background: linear-gradient(180deg, rgba(30,31,41,0.8) 0%, rgba(45,27,105,0.9) 50%, rgba(106,17,203,0.85) 100%);
            position: absolute;
            inset: 0;
            z-index: -1;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .navbar-brand {
            color: #fff !important;
            font-weight: 600;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }

        .btn-premium {
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            border: none;
            color: #fff;
            border-radius: 999px;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 18px rgba(255,65,108,0.3);
        }

        /* Hero Section */
        .hero {
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: left;
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            padding: 50px;
            border-radius: 20px;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .hero h1 {
            font-weight: 700;
            font-size: 2.6rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 2rem;
        }

        /* Footer */
        footer {
            background: rgba(255,255,255,0.05);
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
            text-align: center;
            padding: 1rem 0;
            font-size: 0.9rem;
        }

        footer a {
            color: #ff4b2b;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .hero-card {
                padding: 2rem;
            }
            .hero h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="hero-bg"></div>
    <div class="overlay"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <i class="bi bi-clock-history fs-4 text-warning"></i>
                <span>Attendance Portal</span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-premium px-4 py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center text-md-start">
        <div class="container">
            <div class="hero-card mx-auto">
                <h1>Smart Attendance <br> Made Simple</h1>
                <p>Manage, compare, and track employee attendance with ease. A sleek, modern system designed for Admins, HR, and Employees.</p>
                <a href="{{ route('register') }}" class="btn btn-premium px-5 py-2">
                    <i class="bi bi-person-plus me-2"></i> Get Started
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        © {{ date('Y') }} Employee Attendance Portal | Built with ❤️ by MP2IT.<br>
        <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
