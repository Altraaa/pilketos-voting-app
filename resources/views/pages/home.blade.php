@extends('layouts.main')

@section('title', 'Pemilihan Ketua OSIS 2025')

@section('content')
<style>
  body {
    background-color: #0b1a3a;
    color: white;
    font-family: "Poppins", sans-serif;
  }

  /* Hero Section */
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

  .countdown .time-box {
    background: #061128;
    border-radius: 8px;
    padding: 10px 15px;
    margin: 5px;
    display: inline-block;
    font-size: 1rem;
    min-width: 60px;
  }

  .section-title {
    text-align: center;
    font-weight: 700;
    margin-top: 60px;
    margin-bottom: 20px;
    position: relative;
  }

  .section-title::after {
    content: "";
    display: block;
    width: 80px;
    height: 3px;
    background-color: #0d6efd;
    margin: 10px auto 0;
  }

  .card-custom {
    background-color: #1b2b54;
    border-radius: 20px;
    color: #fff;
    padding: 25px;
  }
</style>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h2 class="fw-bold">Pemilihan Ketua OSIS 2025</h2>
    <p class="text-white-50">Suaramu Menentukan Masa Depan Organisasi Kita</p>

    <div class="countdown mt-3 d-flex justify-content-center">
      <div class="time-box">
        <span id="days">08</span><br><small>Hari</small>
      </div>
      <div class="time-box">
        <span id="hours">23</span><br><small>Jam</small>
      </div>
      <div class="time-box">
        <span id="minutes">50</span><br><small>Menit</small>
      </div>
      <div class="time-box">
        <span id="seconds">30</span><br><small>Detik</small>
      </div>
    </div>
  </div>
</section>

<!-- Tata Cara Section -->
<section class="container">
  <h3 class="section-title">Tata Cara Pemilihan</h3>
  <div class="card-custom mx-auto mb-5" style="max-width: 700px;">
    <ol class="mb-0">
      <li>Setiap siswa berhak memberikan 1 suara untuk 1 kandidat pilihan</li>
      <li>Pemilihan dilaksanakan secara online melalui website ini</li>
      <li>Klik tombol "Vote Sekarang" pada kandidat pilihan Anda</li>
      <li>Konfirmasi pilihan Anda pada popup yang muncul</li>
      <li>Suara yang sudah diberikan tidak dapat diubah</li>
      <li>Hasil pemilihan akan diumumkan setelah periode voting berakhir</li>
      <li>Voting dibuka mulai tanggal 28 Oktober – 5 November 2025</li>
    </ol>
  </div>
</section>
@endsection
