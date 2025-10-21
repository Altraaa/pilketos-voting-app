<!-- Font Awesome -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>

<style>
  html,
  body {
    overflow-x: hidden !important;
    margin: 0;
    padding: 0;
    width: 100%;
    max-width: 100%;
  }

  body {
    padding-top: 60px;
    font-family: "Poppins", sans-serif;
  }

  * {
    max-width: 100%;
    box-sizing: border-box;
  }

  .container,
  .container-fluid {
    overflow-x: hidden;
  }

  /* Hover effect untuk desktop */
  .hover-link {
    position: relative;
    transition: color 0.2s ease;
  }

  .hover-link:hover {
    color: #0094ff !important;
  }

  .hover-link::after {
    content: "";
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background-color: #0094ff;
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
    color: #0094ff !important;
    padding-left: 10px;
  }

  /* Active menu highlight */
  .hover-link.active,
  .hover-link-mobile.active {
    color: #0094ff !important;
    font-weight: 600;
  }
</style>

<header
  class="text-white fixed-top shadow-sm"
  style="background-color: #061128; height: 60px;"
>
  <div
    class="container-fluid d-flex align-items-center justify-content-between h-100 px-3"
  >
    <!-- Logo -->
    <div class="d-flex align-items-center gap-2" style="min-width: 0;">
      <img
        src="{{ asset('images/logo.png') }}"
        alt="Logo OSIS"
        class="img-fluid"
        style="width: 35px; height: 35px; flex-shrink: 0;"
      />
      <h1
        class="h6 m-0 fw-normal text-truncate"
        style="white-space: nowrap;"
      >
        OSIS SKENSA
      </h1>
    </div>

    <!-- Navigasi Tengah -->
    <nav
      class="d-none d-md-flex justify-content-center flex-grow-1 gap-4 align-items-center"
    >
      <a
        href="/"
        class="text-white text-decoration-none hover-link text-nowrap"
        >Beranda</a
      >

      @if(session('user') && session('user')['role'] === 'admin')
      <a
        href="/hasil-vote"
        class="text-white text-decoration-none hover-link text-nowrap"
        >Hasil Vote</a
      >
      @endif

      <a
        href="{{ route('about') }}"
        class="text-white text-decoration-none hover-link text-nowrap"
        >Tentang Kami</a
      >
    </nav>

    <!-- Logout di kanan -->
    @if(Auth::check())
    <form
      id="logoutForm"
      action="{{ route('logout') }}"
      method="POST"
      class="m-0 d-none d-md-block"
    >
      @csrf
      <button
        type="button"
        id="logoutBtn"
        class="btn text-white d-flex align-items-center gap-2 border-0"
      >
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
      </button>
    </form>
    @endif

    <!-- Tombol Hamburger (Mobile) -->
    <button
      id="menu-toggle"
      class="btn text-white d-md-none fs-3 p-0 border-0"
      aria-label="Toggle menu"
      style="flex-shrink: 0;"
    >
      <i id="menu-icon" class="fa-solid fa-bars"></i>
    </button>
  </div>
</header>

<!-- Overlay -->
<div
  id="menu-overlay"
  class="position-fixed top-0 start-0 bg-dark"
  style="width: 100vw; height: 100vh; opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0.3s ease; z-index: 1040;"
></div>

<!-- Sidebar Mobile -->
<div
  id="mobile-menu"
  class="position-fixed end-0 bg-light shadow"
  style="top: 60px; height: calc(100vh - 60px); width: 60%; max-width: 300px; transform: translateX(100%); transition: transform 0.3s ease; z-index: 1050; overflow-y: auto;"
>
  <div class="d-flex flex-column text-end p-4 fw-semibold">
    <a
      href="/"
      class="text-dark text-decoration-none mb-3 py-2 hover-link-mobile"
      >Beranda</a
    >
    @if(session('user') && session('user')['role'] === 'admin')
    <a
      href="/hasil-vote"
      class="text-dark text-decoration-none mb-3 py-2 hover-link-mobile"
      >Hasil Vote</a
    >
    @endif
    <a
      href="{{ route('about') }}"
      class="text-dark text-decoration-none mb-3 py-2 hover-link-mobile"
      >Tentang Kami</a
    >

    @if(Auth::check())
    <form id="logoutFormMobile" action="{{ route('logout') }}" method="POST">
      @csrf
      <button
        type="button"
        id="logoutBtnMobile"
        class="btn btn-danger w-100 mt-3 py-2"
      >
        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
      </button>
    </form>
    @endif
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  const menuToggle = document.getElementById("menu-toggle");
  const mobileMenu = document.getElementById("mobile-menu");
  const menuIcon = document.getElementById("menu-icon");
  const menuOverlay = document.getElementById("menu-overlay");

  let isOpen = false;

  // Toggle menu
  menuToggle.addEventListener("click", (e) => {
    e.stopPropagation();
    isOpen = !isOpen;

    if (isOpen) {
      mobileMenu.style.transform = "translateX(0)";
      menuOverlay.style.opacity = "0.5";
      menuOverlay.style.visibility = "visible";
      menuIcon.className = "fa-solid fa-xmark";
      document.body.style.overflow = "hidden";
    } else {
      closeMenu();
    }
  });

  function closeMenu() {
    mobileMenu.style.transform = "translateX(100%)";
    menuOverlay.style.opacity = "0";
    menuOverlay.style.visibility = "hidden";
    menuIcon.className = "fa-solid fa-bars";
    document.body.style.overflow = "";
    isOpen = false;
  }

  menuOverlay.addEventListener("click", closeMenu);

  document.addEventListener("click", (e) => {
    if (isOpen && !mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
      closeMenu();
    }
  });

  const mobileLinks = mobileMenu.querySelectorAll("a");
  mobileLinks.forEach((link) => {
    link.addEventListener("click", closeMenu);
  });

  // === Highlight Active Page ===
  const currentPath = window.location.pathname.replace(/\/$/, "");

  document.querySelectorAll(".hover-link, .hover-link-mobile").forEach((link) => {
    const linkPath = new URL(link.href).pathname.replace(/\/$/, "");
    if (linkPath === currentPath) {
      link.classList.add("active");
    }
  });

  // === Logout Confirmation (Desktop + Mobile) ===
  function handleLogout(buttonId, formId) {
    const btn = document.getElementById(buttonId);
    const form = document.getElementById(formId);

    if (btn && form) {
      btn.addEventListener("click", async (e) => {
        e.preventDefault();

        const result = await Swal.fire({
          title: "Yakin ingin logout?",
          text: "Kamu akan keluar dari akun ini.",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya, logout",
          cancelButtonText: "Batal",
        });

        if (result.isConfirmed) {
          Swal.fire({
            title: "Keluar...",
            text: "Sedang memproses logout kamu",
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });

          form.submit();
        }
      });
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    handleLogout("logoutBtn", "logoutForm");
    handleLogout("logoutBtnMobile", "logoutFormMobile");
  });
</script>