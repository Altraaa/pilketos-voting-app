<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OSIS SKENSA')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Prevent horizontal scroll */
        html, body {
            overflow-x: hidden !important;
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }
        
        /* Global box-sizing */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        
        /* Body styling */
        body {
            background-color: #0b1b38;
            color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            padding-top: 60px; /* Space untuk fixed header */
        }
        
        /* Container fix */
        .container, .container-fluid {
            max-width: 100%;
            overflow-x: hidden;
            padding-left: 15px;
            padding-right: 15px;
        }
        
        /* Row fix */
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        /* Main content */
        main {
            min-height: calc(100vh - 60px); /* Minus header height */
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
        }
        
        /* Image responsiveness */
        img {
            max-width: 100%;
            height: auto;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body style="opacity: 0; transition: opacity 0.6s ease;" onload="document.body.style.opacity='1'">
    {{-- Header --}}
    @include('components.header')

    @include('components.toast')

    {{-- Konten Halaman --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')
    
    <!-- Bootstrap JS Bundle (termasuk Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1e293b',
                color: '#fff',
                customClass: {
                    popup: 'rounded-3 shadow-lg'
                },
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1e293b',
                color: '#fff',
                customClass: {
                    popup: 'rounded-3 shadow-lg'
                },
            });
        @endif
    });
</script>
</html>