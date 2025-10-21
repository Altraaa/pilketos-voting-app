<header class="admin-header">
  <button id="sidebarToggle" class="btn btn-sm btn-outline-light d-lg-none">
    <i class="bi bi-list"></i>
  </button>

  <h5 class="mb-0 fw-semibold">@yield('title', 'Dashboard')</h5>

  <div class="d-flex align-items-center gap-3">
    <span class="fw-medium">{{ session('user')['name'] ?? 'Admin' }}</span>

    <form id="adminLogoutForm" action="{{ route('logout') }}" method="POST" class="m-0">
      @csrf
      <button type="button" id="adminLogoutBtn" class="btn-logout d-flex align-items-center gap-2">
        <i class="bi bi-box-arrow-right"></i>
      </button>
    </form>
  </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const logoutBtn = document.getElementById("adminLogoutBtn");
    const logoutForm = document.getElementById("adminLogoutForm");

    if (logoutBtn && logoutForm) {
      logoutBtn.addEventListener("click", async (e) => {
        e.preventDefault();

        const result = await Swal.fire({
          title: "Yakin ingin logout?",
          text: "Kamu akan keluar dari akun admin.",
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

          logoutForm.submit();
        }
      });
    }
  });
</script>
