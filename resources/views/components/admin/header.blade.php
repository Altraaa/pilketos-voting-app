<header class="admin-header">
  <button id="sidebarToggle" class="btn btn-sm btn-outline-light d-lg-none">
    <i class="bi bi-list"></i>
  </button>

  <h5 class="mb-0 fw-semibold">@yield('title', 'Dashboard')</h5>

  <div>
    <span class="me-3">{{ session('user')['name'] ?? 'Admin' }}</span>
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
      @csrf
      <button type="submit" class="btn-logout"><i class="bi bi-box-arrow-right"></i></button>
    </form>
  </div>
</header>
