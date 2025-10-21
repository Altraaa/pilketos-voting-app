<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard Admin')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #0b1120;
      color: #e2e8f0;
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }

    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #111827, #0f172a);
      padding: 1.5rem 1rem;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      transition: all 0.3s ease;
    }

    .sidebar .logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    margin-bottom: 2rem;
    text-align: center;
    }

    .sidebar .logo-img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 0 8px rgba(56, 189, 248, 0.3);
    }

    .sidebar .logo-text {
    font-weight: 700;
    font-size: 1.25rem;
    color: #38bdf8;
    letter-spacing: 0.5px;
    }

    @media (max-width: 992px) {
    .sidebar .logo {
        justify-content: flex-start;
        margin-left: 0.5rem;
    }

    .sidebar .logo-img {
        width: 30px;
        height: 30px;
    }

    .sidebar .logo-text {
        font-size: 1.1rem;
    }
    }


    .sidebar a {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      margin-bottom: 0.5rem;
      border-radius: 8px;
      color: #94a3b8;
      text-decoration: none;
      transition: 0.2s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1e293b;
      color: #fff;
    }

    .main-content {
      margin-left: 250px;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background-color: #0f172a;
      transition: margin-left 0.3s ease;
    }

    header.admin-header {
      height: 60px;
      background-color: #1e293b;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem;
      border-bottom: 1px solid #334155;
    }

    header.admin-header .btn-logout {
      color: #f87171;
      font-weight: 500;
      border: none;
      background: none;
    }

    .admin-container {
      padding: 2rem;
      flex: 1;
      background-color: #0f172a;
    }

    @media (max-width: 992px) {
      .sidebar {
        position: fixed;
        left: -260px;
      }

      .sidebar.active {
        left: 0;
      }

      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>

<body>
  {{-- Sidebar --}}
  @include('components.admin.sidebar')

  <div class="main-content">
    {{-- Header --}}
    @include('components.admin.header')

    {{-- Toast --}}
    @include('components.toast')

    {{-- Konten --}}
    <main class="admin-container">
      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Toggle sidebar di mobile
    document.addEventListener('DOMContentLoaded', () => {
      const toggleBtn = document.querySelector('#sidebarToggle');
      const sidebar = document.querySelector('.sidebar');
      toggleBtn?.addEventListener('click', () => sidebar.classList.toggle('active'));
    });

    // Reuse toast
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
        customClass: { popup: 'rounded-3 shadow-lg animate__animated animate__fadeInRight' },
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
        customClass: { popup: 'rounded-3 shadow-lg animate__animated animate__fadeInRight' },
      });
    @endif
  </script>
</body>
</html>
