<nav class="sidebar">
  <div class="logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
    <span class="logo-text">OSKA Official</span>
  </div>

  <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
  </a>
  <a href="{{ route('admin.candidate') }}" class="{{ request()->routeIs('admin.candidate') ? 'active' : '' }}">
    <i class="bi bi-person-lines-fill"></i> Candidates
  </a>
  <a href="{{ route('admin.result') }}" class="{{ request()->routeIs('admin.result') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-line"></i> Vote Results
  </a>
  <a href="{{ route('admin.user') }}" class="{{ request()->routeIs('admin.user') ? 'active' : '' }}">
    <i class="bi bi-people"></i> Users Management
  </a>
</nav>
