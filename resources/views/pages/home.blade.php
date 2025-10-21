@extends('layouts.main')

@section('title', 'Pemilihan Ketua OSIS 2025')

@section('content')

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h2 class="fw-bold">Pemilihan Ketua OSIS 2025</h2>
    <p class="text-white">Suaramu Menentukan Masa Depan Organisasi Kita</p>

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


<!-- Tata Cara -->
<section class="tata-cara py-5">
  <div class="container text-center">
    <h3 class="fw-bold mb-2">Tata Cara Pemilihan</h3>
    <div class="underline mx-auto mb-3"></div>

    <div class="tata-cara-box text-start mx-auto p-4 rounded-4">
      <ol class="m-0">
        <li>Setiap siswa berhak memberikan 1 suara untuk 1 kandidat pilihan</li>
        <li>Pemilihan dilaksanakan secara online melalui website ini</li>
        <li>Klik tombol "Vote Sekarang" pada kandidat pilihan Anda</li>
        <li>Konfirmasi pilihan Anda pada popup yang muncul</li>
        <li>Suara yang sudah diberikan tidak dapat diubah</li>
        <li>Hasil pemilihan akan diumumkan setelah periode voting berakhir</li>
        <li>Voting dibuka mulai tanggal 28 Oktober – 5 November 2025</li>
      </ol>
    </div>
  </div>
</section>

<!-- kandidat -->
<section class="kandidat-section py-5 text-center text-white">
  <div class="container">
    <h2 class="fw-bold mb-2">Kandidat Ketua OSIS</h2>
    <div class="underline mx-auto mb-4"></div>

    <div class="row justify-content-center g-4">
      <div class="col-md-5">
        <div class="card shadow rounded-3 overflow-hidden position-relative">
          <div class="position-relative">
            <img src="images/dashboard.jpg" class="card-img-top" alt="...">
            <div class="nomor-kandidat">1</div>
          </div>
          <div class="card-body">
            <h5 class="fw-bold">Ni Komang Onny Fridayanti</h5>
            <p class="text-secondary mb-2">Calon Ketua OSIS</p>

            <h6 class="fw-semibold mt-3">Visi</h6>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia nisl at bibendum cursus.</p>

            <h6 class="fw-semibold mt-3">Misi</h6>
            <ul>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
            </ul>

            <a href="#" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#konfirmasiModal">
              Pilih Sekarang
            </a>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="card shadow rounded-3 overflow-hidden position-relative">
          <div class="position-relative">
            <img src="images/dashboard.jpg" class="card-img-top" alt="...">
            <div class="nomor-kandidat">2</div>
          </div>
          <div class="card-body">
            <h5 class="fw-bold">Ni Komang Onny Fridayanti</h5>
            <p class="text-secondary mb-2">Calon Ketua OSIS</p>

            <h6 class="fw-semibold mt-3">Visi</h6>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia nisl at bibendum cursus.</p>

            <h6 class="fw-semibold mt-3">Misi</h6>
            <ul>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
            </ul>

            <a href="#" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#konfirmasiModal">
              Pilih Sekarang
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

 
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white rounded-4 shadow-lg border-0">
      <div class="modal-body text-center p-4">
        <h5 class="fw-bold mb-3" id="konfirmasiLabel">Konfirmasi Pilihan</h5>
        <p>Apakah Anda yakin memilih Kandidat ini? Pilihan tidak dapat diubah.</p>
        <div class="d-flex justify-content-center gap-3 mt-4">
          <button type="button" class="btn btn-success px-4">Pilih Sekarang</button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
</div>


<style>
  body {
    background-color: #061128;
    color: white;
    font-family: "Poppins", sans-serif;
  }

  /* ===== HERO SECTION ===== */
  .hero {
    background: url('{{ asset('images/dashboard.jpg') }}') center center / cover no-repeat;
    position: relative;
    text-align: center;
    color: white;
    height: 90vh;
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

  /* ===== TATA CARA SECTION ===== */
  .tata-cara {
    color: #ffffff;
    font-family: 'Poppins', sans-serif;
  }

  .tata-cara .underline {
    width: 250px;
    height: 1px;
    background-color: #f2f4f5ff;
  }

  .tata-cara-box {
    background-color: #243558;
    max-width: 600px;
    margin: 0 auto;
    padding: 2rem;
    border-radius: 15px;
  }

  .tata-cara-box ol {
    list-style: decimal;
    padding-left: 1.2rem;
  }

  .tata-cara-box li {
    margin-bottom: 10px;
    line-height: 1.6;
  }

  .tata-cara-box a {
    color: #5ecbff;
    text-decoration: none;
    transition: 0.2s ease;
  }

  .tata-cara-box a:hover {
    text-decoration: underline;
    color: #8fe3ff;
  }

  /* ===== KANDIDAT SECTION ===== */
  .kandidat-section {
    padding-top: 80px;
    padding-bottom: 80px;
  }

  .section-title {
    font-size: 1.8rem;
    position: relative;
    display: inline-block;
  }

  .underline {
    width: 300px;
    height: 1px;
    background-color: #ffffff;
    border-radius: 5px;
    margin-top: 5px;
    margin-bottom: 10px;
  }

  .card {
    background-color: #243558;
    color: white;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease;
    position: relative;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  .card img {
    height: 250px;
    object-fit: cover;
    width: 100%;
    display: block;
  }

  .card h5 {
    font-weight: 700;
    font-size: 1.25rem;
    text-align: left;
  }

  .card h6 {
    font-weight: 600;
    margin-top: 1rem;
    text-align: left;
  }

  .card p,
  .card li {
    font-size: 14px;
    color: #d6d6d6;
    text-align: left;
  }

  .card .btn {
    background-color: #36f992;
    color: #0a1a44;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
  }

  .card .btn:hover {
    background-color: #2adf80;
    transform: translateY(-2px);
  }

  .nomor-kandidat {
    position: absolute;
    top: 12px;
    left: 12px;
    background-color: rgba(255, 255, 255, 0.9);
    color: #0b173a;
    font-weight: 700;
    font-size: 1.1rem;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
  }

  /* ===== MODAL KONFIRMASI ===== */
  .modal-content {
    background-color: #14285F !important;
    border-radius: 20px;
  }

  .modal-content .btn-success {
    background-color: #22C55E;
    border: none;
    color: #fff;
    font-weight: 600;
  }

  .modal-content .btn-success:hover {
    background-color: #2fe07f;
  }

  .modal-content .btn-secondary {
    background-color: #091a44;
    border: none;
    color: #fff;
    font-weight: 600;
  }

  .modal-content .btn-secondary:hover {
    background-color: #0d2a65;
  }

  

  @media (max-width: 992px) {
    .hero {
      height: 60vh;
    }

    .hero h2 {
      font-size: 1.8rem;
    }

    .countdown .time-box {
      font-size: 0.9rem;
      min-width: 55px;
    }

    .kandidat-section {
      padding-top: 60px;
      padding-bottom: 60px;
    }

    .card img {
      height: 220px;
    }
  }

  @media (max-width: 768px) {
    .hero {
      height: 50vh;
      padding: 0 15px;
    }

    .hero h2 {
      font-size: 1.5rem;
    }

    .card {
      margin: 0 auto;
      max-width: 90%;
    }

    .card h5 {
      font-size: 1.1rem;
    }

    .card p, .card li {
      font-size: 13px;
    }

    .nomor-kandidat {
      width: 35px;
      height: 35px;
      font-size: 1rem;
    }

    .tata-cara-box {
      padding: 1.5rem;
    }
  }

  @media (max-width: 576px) {
    .hero {
      height: 45vh;
      padding: 0 10px;
    }

    .hero h2 {
      font-size: 1.3rem;
    }

    .hero p {
      font-size: 0.9rem;
    }

    .card {
      max-width: 100%;
    }

    .nomor-kandidat {
      top: 10px;
      left: 10px;
      width: 32px;
      height: 32px;
      font-size: 0.9rem;
    }

    .card img {
      height: 200px;
    }

    .btn {
      font-size: 0.9rem;
      padding: 8px 0;
    }

    .modal-content {
      border-radius: 15px;
    }
  }
</style>

@endsection
