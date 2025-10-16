<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Hilangkan geser kiri-kanan */
    html, body {
        overflow-x: hidden !important;
        margin: 0;
        padding: 0;
        width: 100%;
        max-width: 100%;
    }
    
    /* Tambahkan padding-top untuk konten agar tidak tertutup header */
    body {
        padding-top: 60px;
    }
    
    /* Pastikan semua elemen tidak melebihi lebar layar */
    * {
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .container, .container-fluid {
        overflow-x: hidden;
    }
</style>

<header class="text-white fixed-top shadow-sm" style="background-color: #061128; height: 60px;">
    <div class="container-fluid d-flex align-items-center justify-content-between h-100 px-3">

        <!-- Logo -->
        <div class="d-flex align-items-center gap-2" style="min-width: 0;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo OSIS" class="img-fluid" style="width: 35px; height: 35px; flex-shrink: 0;">
            <h1 class="h6 m-0 fw-normal text-truncate" style="white-space: nowrap;">OSIS SKENSA</h1>
        </div>

        <!-- Tombol Hamburger (Mobile) -->
        <button id="menu-toggle" class="btn text-white d-md-none fs-3 p-0 border-0" aria-label="Toggle menu" style="flex-shrink: 0;">
            <i id="menu-icon" class="fa-solid fa-bars"></i>
        </button>

        <!-- Navigasi Desktop -->
        <nav class="d-none d-md-flex gap-4 align-items-center" style="flex-shrink: 0;">
            <a href="/" class="text-white text-decoration-none hover-link text-nowrap">Beranda</a>
            <a href="/" class="text-white text-decoration-none hover-link text-nowrap">Hasil Vote</a>
            <a href="{{ route('about') }}" class="text-white text-decoration-none hover-link text-nowrap">Tentang Kami</a>
        </nav> 
    </div>
</header>

<!-- Overlay untuk menutup menu -->
<div id="menu-overlay" 
     class="position-fixed top-0 start-0 bg-dark"
     style="width: 100vw; height: 100vh; opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0.3s ease; z-index: 1040;">
</div>

<!-- Sidebar Mobile -->
<div id="mobile-menu"
     class="position-fixed end-0 bg-light shadow"
     style="top: 60px; height: calc(100vh - 60px); width: 60%; max-width: 300px; transform: translateX(100%); transition: transform 0.3s ease; z-index: 1050; overflow-y: auto;">

    <div class="d-flex flex-column text-end p-4 fw-semibold">
        <a href="/" class="text-dark text-decoration-none mb-3 py-2 hover-link-mobile">Beranda</a>    
        <a href="/hasil-vote" class="text-dark text-decoration-none mb-3 py-2 hover-link-mobile">Hasil Vote</a>
        <a href="{{ route('about') }}" class="text-dark text-decoration-none py-2 hover-link-mobile">Tentang Kami</a>
    </div>
</div>

<style>
    /* Hover effect untuk desktop */
    .hover-link {
        position: relative;
        transition: color 0.2s ease;
    }
    
    .hover-link:hover {
        color: #0094FF !important;
    }
    
    .hover-link::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #0094FF;
        transition: width 0.3s ease;
    }
    
    .hover-link:hover::after {
        width: 100%;
    }
    
    /* Hover effect untuk mobile */
    .hover-link-mobile {
        transition: color 0.2s ease, padding-left 0.2s ease;
    }
    
    .hover-link-mobile:hover {
        color: #0094FF !important;
        padding-left: 10px;
    }
    
    /* Active menu highlight */
    .hover-link.active,
    .hover-link-mobile.active {
        color: #0094FF !important;
        font-weight: 600;
    }
</style>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const menuOverlay = document.getElementById('menu-overlay');

    let isOpen = false;

    // Toggle menu
    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        isOpen = !isOpen;
        
        if (isOpen) {
            mobileMenu.style.transform = 'translateX(0)';
            menuOverlay.style.opacity = '0.5';
            menuOverlay.style.visibility = 'visible';
            menuIcon.className = 'fa-solid fa-xmark';
            document.body.style.overflow = 'hidden';
        } else {
            closeMenu();
        }
    });

    function closeMenu() {
        mobileMenu.style.transform = 'translateX(100%)';
        menuOverlay.style.opacity = '0';
        menuOverlay.style.visibility = 'hidden';
        menuIcon.className = 'fa-solid fa-bars';
        document.body.style.overflow = '';
        isOpen = false;
    }

    menuOverlay.addEventListener('click', closeMenu);

    document.addEventListener('click', (e) => {
        if (isOpen && !mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
            closeMenu();
        }
    });

    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    // === Highlight Active Page ===
    const currentPath = window.location.pathname.replace(/\/$/, ""); // hapus slash di akhir

    document.querySelectorAll('.hover-link, .hover-link-mobile').forEach(link => {
        const linkPath = new URL(link.href).pathname.replace(/\/$/, ""); // samakan format path
        if (linkPath === currentPath) {
            link.classList.add('active');
        }
    });
</script>