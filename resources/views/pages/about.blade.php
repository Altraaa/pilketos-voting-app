@extends('layouts.main')

@section('title', 'Tentang Kami')

@section('content')
<main class="bg-[#061128] text-white" style="padding-top: 20px; margin-top: 0;">
  <div class="container px-4" style="margin-top: 0;">

    <!-- Judul Halaman -->
    <div class="text-center mb-5">
      <h2 class="fw-bold fs-3">Tentang Kami</h2>
      <div class="mx-auto mt-2" style="width: 120px; height: 3px; background-color: white;"></div>
    </div>

    <!-- Bagian OSIS -->
    <section class="text-center mb-5">
      <h3 class="fw-bold fs-4">OSIS SKENSA</h3>
      <p class="fst-italic text-white-50 mb-3">Organisasi Siswa Intra Sekolah</p>

      <!-- Swiper 3D Coverflow -->
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img src="{{ asset('images/osis2.JPG') }}" alt="Gambar 1">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/osis5.JPG') }}" alt="Gambar 2">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/osis3.JPG') }}" alt="Gambar 3">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/osis1.jpg') }}" alt="Gambar 4">
          </div>
        </div>
      </div>
    </section>

    <!-- Program Kerja -->
    <section class="text-white mt-5">
      <div class="text-center mb-5">
      <h2 class="fw-bold fs-3">PROGRAM KERJA</h2>
      <div class="mx-auto mt-2" style="width: 150px; height: 3px; background-color: white;"></div>
      </div>

      <div class="mb-5">
        <h5 class="fw-bold">1. Short Video Public Education</h5>
        <p class="text-white-50" style="margin-top: -2px; margin-bottom: 30px; line-height: 1.2;">
        Program kerja ini bertujuan untuk mengedukasi siswa-siswi SMK Negeri 1 Denpasar ataupun masyarakat di lingkungan sekolah
        mengenai hal-hal yang ada di sekolah.
        </p>
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img src="{{ asset('images/thumbnail8.png') }}" alt="Gambar 1">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/thumbnail7.jpg') }}" alt="Gambar 2">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/thumbnail4.png') }}" alt="Gambar 3">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/thumbnail3.png') }}" alt="Gambar 4">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/thumbnail2.png') }}" alt="Gambar 5">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/thumbnail1.png') }}" alt="Gambar 6">
          </div>
        </div>
      </div>

        <div class="text-center">
          <a href="https://youtube.com/@oskaofficial" class="btn text-white fw-semibold px-4 py-2" style="background-color: #00c46c; border-radius: 30px;">
            Selengkapnya
          </a>
        </div>
      </div>

      <div class="mb-5">
        <h5 class="fw-bold">2. PONSA (Podcast On SKENSA)</h5>
        <p class="text-white-50" style="margin-top: -2px; margin-bottom: 30px; line-height: 1.2;">
          Program kerja ini bertujuan untuk mengadakan Podcast/Speak Up bersama seluruh warga sekolah mengenai isu-isu yang ada di lingkungan sekolah.
        </p>

      <div class="swiper mySwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img src="{{ asset('images/ponsa1.png') }}" alt="Gambar 1">
          </div>
          <div class="swiper-slide">
            <img src="{{ asset('images/ponsa2.jpg') }}" alt="Gambar 2">
          </div>
        </div>
      </div>

        <div class="text-center">
          <a href="https://youtube.com/@oskaofficial" class="btn text-white fw-semibold px-4 py-2" style="background-color: #00c46c; border-radius: 30px;">
            Selengkapnya
          </a>
        </div>
      </div>
    </section>
  </div>
</main>

<!-- SwiperJS CDN -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Inisialisasi Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    coverflowEffect: {
      rotate: 45,
      stretch: 0,
      depth: 150,
      modifier: 1,
      slideShadows: true,
    },
    loop: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
  });
</script>

<!-- Styling -->
<style>
  .swiper {
    width: 100%;
    padding-top: 30px;
    padding-bottom: 40px;
  }

  .swiper-slide {
    background-position: center;
    background-size: cover;
    width: 280px;
    height: 180px;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease;
  }

  .swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
</style>
@endsection

