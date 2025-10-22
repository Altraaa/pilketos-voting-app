<nav class="sidebar">
  <div class="logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
    <span class="logo-text">OSKA Official</span>
  </div>
   <a href="{{ route('admin.category') }}" class="{{ request()->routeIs('admin.category') ? 'active' : '' }}">
        <i class="bi bi-tags"></i>
        <span>Kategori</span>
    </a>
  <a href="{{ route('admin.candidate') }}" class="{{ request()->routeIs('admin.candidate') ? 'active' : '' }}">
    <i class="bi bi-person-lines-fill"></i> Candidates
  </a>
  <a href="{{ route('admin.user') }}" class="{{ request()->routeIs('admin.user') ? 'active' : '' }}">
    <i class="bi bi-people"></i> Users Management
  </a>
</nav>
