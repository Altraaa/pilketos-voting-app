<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OSIS SKENSA - Login')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(180deg, #001a3d 0%, #003366 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: linear-gradient(135deg, #2d3f5f 0%, #3a5270 100%);
            border-radius: 24px;
            padding: 40px 30px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 40px 12px rgba(16, 185, 129, 0.4);
        }

        .login-title {
            color: #ffffff;
            font-size: 22px;
            font-weight: 600;
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
            color: #ffffffff !important;
            font-size: 14px;
            margin-bottom: 18px;
            box-shadow: none !important ;
        }

        .form-control:focus {
            background-color: #1B2A60 !important;
            color: #ffffffff !important;
        }

        .form-control::placeholder {
            color: #b9c0caff !important;
        }

        .btn-login {
            background: #22C55E !important;
            border: none;
            border-radius: 8px;
            padding: 13px;
            font-size: 15px;
            font-weight: 600;
            color: #ffffff !important;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        .alert {
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="bg-[#0b1b38] text-white font-sans">

    {{-- Konten Halaman --}}
    <main class="min-h-screen">
        @yield('content')
        <section class="pt-24 pb-10">
            <div class="login-container">
                <div class="logo-container">
                    <div class="logo">
                        <img src="{{ asset('images/logo.png') }}" alt="LOGO OSKA" class="w-60">
                    </div>
                    <h1 clas="login-title">Masuk Diperlukan</h1>
                    <p class="login-subtitle">Masukkan <span>Kode</span> dan <span>Kata Sandi</span> untuk masuk</p>
                </div>
                @if ($errors->has('login_error'))
                <div class="alert alert-danger text-center">
                    {{ $errors->first('login_error') }}
                </div>
                @endif
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text"
                        placeholder="Kode"
                        class="form-control @error('code') is-invalid @enderror"
                        id="unique_code"
                        name="unique_code"
                        value="{{ old('unique_code') }}"
                        required
                        autofocus>
                    </div>

                    <div class="mb-4">
                        <input type="password"
                        placeholder="Kata Sandi" 
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        value="{{ old('password') }}"
                        required>
                    </div>

                    <button type="submit" class="btn btn-login" >Masuk</button>
                </form>
            </div>
            
        </section>
    </main>

</body>
</html>
