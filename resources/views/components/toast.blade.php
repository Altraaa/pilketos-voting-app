@if (session('success') || session('error') || $errors->has('login_error'))
<div id="toast" class="toast {{ session('error') || $errors->has('login_error') ? 'toast-error' : 'toast-success' }}">
  {{ session('success') ?? session('error') ?? $errors->first('login_error') }}
</div>
@endif

<style>
.toast {
  position: fixed;
  top: 20px;
  right: -300px;
  padding: 14px 22px;
  border-radius: 10px;
  font-weight: 500;
  font-size: 14px;
  z-index: 9999;
  opacity: 0;
  transform: translateX(120%);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.25);
  animation: slideIn 0.6s ease forwards, slideOut 0.6s ease 3.8s forwards;
}

.toast-success {
  background: linear-gradient(135deg, #10b981, #059669);
  color: #ffffff;
}

.toast-error {
  background: linear-gradient(135deg, #ef4444, #b91c1c);
  color: #ffffff;
}

@keyframes slideIn {
  to {
    right: 20px;
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideOut {
  to {
    opacity: 0;
    right: -300px;
    transform: translateX(120%);
  }
}
</style>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const toast = document.getElementById("toast");
    if (toast) {
      setTimeout(() => {
        toast.style.transition = "transform 0.6s ease, opacity 0.6s ease";
        toast.style.transform = "translateX(120%)";
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 600);
      }, 4000);
    }
  });
</script>
