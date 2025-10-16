<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OSIS SKENSA')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Body styling */
        body {
            background-color: #071A44 !important;
            color: #ffffff;
            font-family: "Poppins", sans-serif;
        }

        .hero {
            background: url('{{ asset('images/dashboard.jpg') }}') center center / cover no-repeat;
            position: relative;
            text-align: center;
            color: white;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }
        

        .countdown-section {
            text-align: center;
            margin-bottom: 50px;
        }

        .countdown-boxes {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .countdown-box {
            background: #061128;
            border-radius: 8px;
            padding: 10px 15px;
            margin: 5px;
            display: inline-block;
            font-size: 1rem;
            min-width: 60px;
        }

        .countdown-number {
            font-size: 28px;
            font-weight: 700;
            color: #ffffffff;
            display: block;
            line-height: 1;
        }

        .countdown-label {
            font-size: 12px;
            color: #ffffffff;
            margin-top: 6px;
            display: block;
        }

        .title-section {
            text-align: center;
            margin-bottom: 50px;
            margin-top: 50px;
        }

        .main-title {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .main-title::after {
            content: "";
            display: block;
            width: 80px;
            height: 3px;
            background-color: #0d6efd;
            margin: 10px auto 0;
  }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 40px;
            margin: 0 auto;
            padding: 0 30px;
            height: 450px;
        }

        .bar-wrapper {
            text-align: center;
            flex: 0 0 130px;
        }

        .bar {
            width: 100%;
            border-radius: 12px 12px 0 0;
            position: relative;
            transition: all 0.8s ease;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bar-kandidat1 {
            background: linear-gradient(180deg, #5dbce8 0%, #3b9fc9 100%);
        }

        .bar-kandidat2 {
            background: linear-gradient(180deg, #a3f573 0%, #7bc950 100%);
        }

        .vote-count {
            font-size: 36px;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .kandidat-name {
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            margin-top: 15px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>

<body>
    {{-- Header --}}
    @include('components.header')

    {{-- Konten Halaman --}}
    <main>
        @yield('content')
        <!-- Countdown Section -->
         <section class="hero">
            <div class="hero-content">
                <div class="countdown-section">
                    <div class="countdown-boxes">
                        <div class="countdown-box">
                            <span class="countdown-number" id="hours">{{ $hoursLeft ?? 30 }}</span>
                            <span class="countdown-label">Jam</span>
                        </div>
                        <div class="countdown-box">
                            <span class="countdown-number" id="minutes">{{ $minutesLeft ?? 30 }}</span>
                            <span class="countdown-label">Menit</span>
                        </div>
                        <div class="countdown-box">
                            <span class="countdown-number" id="seconds">{{ $secondsLeft ?? 30 }}</span>
                            <span class="countdown-label">Detik</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Title Section -->
        <div class="title-section">
            <h1 class="main-title">Hasil Pemilihan Ketua<br>OSIS 2025</h1> 
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <div class="bar-wrapper">
                <div class="bar bar-kandidat1" id="bar1" style="height: 0px;">
                    <span class="vote-count" id="votes1">{{ $kandidat1 ?? 200 }}</span>
                </div>
                <div class="kandidat-name">Kandidat 1</div>
            </div>
            <div class="bar-wrapper">
                <div class="bar bar-kandidat2" id="bar2" style="height: 0px;">
                    <span class="vote-count" id="votes2">{{ $kandidat2 ?? 50 }}</span>
                </div>
                <div class="kandidat-name">Kandidat 2</div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    @include('components.footer')
    
    <!-- Bootstrap JS Bundle (termasuk Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initial animation
        window.addEventListener('load', function() {
            const votes1 = {{ $kandidat1 ?? 200 }};
            const votes2 = {{ $kandidat2 ?? 50 }};
            const maxVotes = Math.max(votes1, votes2);
            const maxHeight = 350;

            setTimeout(() => {
                const height1 = (votes1 / maxVotes) * maxHeight;
                const height2 = (votes2 / maxVotes) * maxHeight;
                
                document.getElementById('bar1').style.height = height1 + 'px';
                document.getElementById('bar2').style.height = height2 + 'px';
            }, 300);
        });

        // Realtime update setiap 3 detik
        setInterval(function() {
            fetch('/voting/results-api')
                .then(response => response.json())
                .then(data => {
                    const maxVotes = Math.max(data.kandidat1, data.kandidat2);
                    const maxHeight = 350;
                    
                    const height1 = (data.kandidat1 / maxVotes) * maxHeight;
                    const height2 = (data.kandidat2 / maxVotes) * maxHeight;
                    
                    document.getElementById('votes1').textContent = data.kandidat1;
                    document.getElementById('votes2').textContent = data.kandidat2;
                    document.getElementById('bar1').style.height = height1 + 'px';
                    document.getElementById('bar2').style.height = height2 + 'px';
                });
        }, 3000);
    </script>
</body>
</html> 