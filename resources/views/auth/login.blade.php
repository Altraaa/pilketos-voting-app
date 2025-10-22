@extends('layouts.main')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background: linear-gradient(180deg, #001a3d 0%, #003366 100%);">
    <div class="login-container">
        <div class="logo-container text-center mb-4">
            <div class="logo mx-auto mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="LOGO OSKA" class="w-60">
            </div>
            <h2 class="login-title">Login Diperlukan</h2>
            <p class="login-subtitle">Masukkan <span>Kode</span> dan <span>Kata Sandi</span> untuk masuk</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form id="loginForm" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text"
                    placeholder="Masukkan Kode"
                    class="form-control @error('code') is-invalid @enderror"
                    id="unique_code"
                    name="unique_code"
                    value="{{ old('unique_code') }}"
                    required
                    autofocus>
            </div>

            <div class="mb-4">
                <input type="password"
                    placeholder="Masukkan Password" 
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    value="{{ old('password') }}"
                    required>
            </div>

            <button type="submit" class="btn btn-login w-100" id="loginBtn">
                <span id="btnText">Masuk</span>
                <span id="loadingSpinner" class="spinner" style="display: none;"></span>
            </button>
        </form>
    </div>
</div>

<style>
    .login-container {
        background: linear-gradient(135deg, #2d3f5f 0%, #3a5270 100%);
        border-radius: 24px;
        padding: 60px 40px;
        width: 100%;
        max-width: 380px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .logo {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 40px 12px rgba(16, 185, 129, 0.4);
    }

    .login-title {
        color: #ffffff;
        margin-bottom: 8px;
    }

    .login-subtitle {
        color: #94a3b8;
        font-size: 13px;
        margin-bottom: 30px;
    }

    .login-subtitle span {
        color: #10b981;
        font-weight: 500;
    }

    .form-control {
        background-color: #1B2A60 !important;
        border: none !important;
        padding: 12px 15px;
        color: #ffffff !important;
        font-size: 14px;
        margin-bottom: 18px;
        border-radius: 8px;
    }
    
    /* Tambahkan CSS untuk placeholder agar terlihat jelas */
    .form-control::placeholder {
        color: #94a3b8 !important;
        opacity: 1 !important;
    }

    .btn-login {
        background: #22C55E !important;
        border: none;
        border-radius: 8px;
        padding: 13px;
        font-size: 15px;
        font-weight: 600;
        color: #ffffff !important;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    .spinner {
        border: 3px solid rgba(255, 255, 255, 0.2);
        border-top: 3px solid #fff;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: inline-block;
        margin-left: 8px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('loginBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('loadingSpinner');

    form.addEventListener('submit', () => {
        btn.disabled = true;
        btnText.textContent = "Memproses...";
        spinner.style.display = "inline-block";
    });
});
</script>
@endsection