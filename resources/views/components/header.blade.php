<!-- Tambahkan Font Awesome jika belum ada -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<header class="text-white fixed-top shadow-sm" style="background-color: #061128; height: 60px;">
    <div class="container d-flex align-items-center justify-content-between py-2.5 px-3">

        <!-- Logo -->
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo OSIS" class="img-fluid" style="width: 35px; height: 35px;">
            <h1 class="h6 m-0 fw-normal">OSIS SKENSA</h1>
        </div>

        <!-- Tombol Hamburger (akan berubah jadi X) -->
        <button id="menu-toggle" class="btn text-white d-md-none fs-3 p-0">
            <i id="menu-icon" class="fa-solid fa-bars"></i>
        </button>

        <!-- Navigasi Desktop -->
        <nav class="d-none d-md-flex gap-4">
            <a href="/" class="text-white text-decoration-none">Beranda</a>
            <a href="/kandidat" class="text-white text-decoration-none">Kandidat</a>
            <a href="/hasil-vote" class="text-white text-decoration-none">Hasil Vote</a>
            <a href="/tentang-kami" class="text-white text-decoration-none">Tentang Kami</a>
        </nav>
    </div>
</header>

<!-- Sidebar Mobile -->
<div id="mobile-menu"
    class="position-fixed end-0 bg-light shadow"
    style="top: 60px; height: calc(100vh - 60px); width: 60%; transform: translateX(100%); transition: transform 0.3s ease; z-index: 1050;">

    <!-- Isi Menu -->
    <div class="d-flex flex-column text-end p-4 fw-semibold">
        <a href="/" class="text-dark text-decoration-none mb-3">Beranda</a>
        <a href="/kandidat" class="text-dark text-decoration-none mb-3">Kandidat</a>
        <a href="/hasil-vote" class="text-dark text-decoration-none mb-3">Hasil Vote</a>
        <a href="/tentang-kami" class="text-dark text-decoration-none">Tentang Kami</a>
    </div>
</div>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');

    let isOpen = false;

    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation(); // supaya klik tidak langsung nutup
        isOpen = !isOpen;
        mobileMenu.style.transform = isOpen ? 'translateX(0)' : 'translateX(100%)';
        menuIcon.className = isOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
    });

    // Klik di luar sidebar untuk tutup
    document.addEventListener('click', (e) => {
        if (!mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
            mobileMenu.style.transform = 'translateX(100%)';
            isOpen = false;
            menuIcon.className = 'fa-solid fa-bars';
        }
    });
</script>
