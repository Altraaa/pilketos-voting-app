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


/* ===== Section Tata Cara ===== */
.tata-cara {
  background-color: #041c3d; /* biru gelap */
  color: #ffffff;
  font-family: 'Poppins', sans-serif;
}

/* Garis bawah judul */
.tata-cara .underline {
  width: 250px;
  height: 1px;
  background-color: #f2f4f5ff;
}

/* Box isi */
.tata-cara-box {
  background-color: #243558; /* biru navy lembut */
  max-width: 600px;
  margin: 0 auto;     /* biar tetap di tengah */
  padding: 2rem;
}

/* List tanpa border */
.tata-cara-box ol {
  list-style: decimal;
  padding-left: 1.2rem;
}

.tata-cara-box li {
  margin-bottom: 10px;
  line-height: 1.6;
}

/* Link gaya biru muda */
.tata-cara-box a {
  color: #5ecbff;
  text-decoration: none;
  transition: 0.2s ease;
}

.tata-cara-box a:hover {
  text-decoration: underline;
  color: #8fe3ff;
}



/* ===== Section kandidat ===== */

.kandidat-section {
  background-color: #0b173a;
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
  background-color: #ffffff; /* warna putih */
  border-radius: 5px;
  margin-top: 5px;
  margin-bottom: 10px;
}

/* Card styling (biar tetap sama seperti sebelumnya) */
.card {
  background-color: #0a1a44;
  color: white;
  border: none;
  border-radius: 15px;
  overflow: hidden;
  transition: transform 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
}

.card img {
  height: 230px;
  object-fit: cover;
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




</style>












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


<!-- Tata Cara Pemilihan Section -->
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

<!--kandidat-->
<section class="kandidat-section py-5 text-center text-white">
  <div class="container">
    <h2 class="fw-bold mb-2">Kandidat Ketua OSIS</h2>
    <div class="underline mx-auto mb-4"></div>

    <div class="row justify-content-center g-4">
      <!-- Card 1 -->
      <div class="col-md-5">
        <div class="card shadow rounded-3">
          <img src="images/dashboard.jpg" class="card-img-top" alt="...">
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

            <a href="#" class="btn w-100 mt-3">Pilih Sekarang</a>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-5">
        <div class="card shadow rounded-3">
          <img src="images/dashboard.jpg" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="fw-bold">I Gede Putra Mahendra</h5>
            <p class="text-secondary mb-2">Calon Ketua OSIS</p>

            <h6 class="fw-semibold mt-3">Visi</h6>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia nisl at bibendum cursus.</p>

            <h6 class="fw-semibold mt-3">Misi</h6>
            <ul>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
            </ul>

            <a href="#" class="btn w-100 mt-3">Pilih Sekarang</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
