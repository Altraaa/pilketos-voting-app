<footer class="text-white mt-5" style="background-color: #08142b; overflow-x: hidden;">
    <div class="container py-5 px-3">

        <div class="row g-4 mx-0"> <!-- Hilangkan negative margin dengan mx-0 -->

            <!-- Kolom Kiri -->
            <div class="col-12 col-md-8 px-2 px-md-3">
                <div class="d-flex align-items-center gap-2 gap-md-3 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo OSIS" class="img-fluid" style="width: 50px; height: 50px; flex-shrink: 0;">
                    <h2 class="fw-bold mb-0" style="font-size: clamp(1.2rem, 4vw, 1.5rem);">OSIS SKENSA</h2>
                </div>

                <p class="mb-2" style="font-size: 0.9rem;">Follow Sosial Media Kami</p>
                <div class="d-flex gap-3 mb-3">
                    <a href="https://instagram.com/osis_skensa" target="_blank" rel="noopener noreferrer" class="text-white fs-4 footer-social"><i class="fab fa-instagram"></i></a>
                    <a href="https://youtube.com/@oskaofficial" target="_blank" rel="noopener noreferrer" class="text-white fs-4 footer-social"><i class="fab fa-youtube"></i></a>
                </div>

                <h6 class="fw-semibold mb-2">Hubungi Kami</h6>
                <div class="mb-3">
                    <p class="mb-1 fw-semibold" style="font-size: 0.9rem;">Alamat:</p>
                    <p class="text-light mb-0" style="font-size: 0.85rem; line-height: 1.6; opacity: 0.9;">
                        84 Cokroaminoto Street, Pemecutan Kaja,<br>
                        North Denpasar District, Denpasar City,<br>
                        Bali 80116
                    </p>
                </div>

                <div class="mb-3">
                    <p class="mb-1 fw-semibold" style="font-size: 0.9rem;">Kontak:</p>
                    <p class="text-light mb-0" style="font-size: 0.85rem; opacity: 0.9;">089524606163 (Wibawa)</p>
                </div>

                <div>
                    <p class="mb-1 fw-semibold" style="font-size: 0.9rem;">E-mail:</p>
                    <p class="text-light mb-0" style="font-size: 0.85rem; opacity: 0.9; word-break: break-word;">osissmkn1denpasar@gmail.com</p>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-12 col-md-4 px-2 px-md-3">
                <h6 class="fw-semibold mb-3">Navigasi</h6>
                <ul class="list-unstyled d-grid gap-2 mb-0" style="font-size: 0.95rem;">
                    <li><a href="/" class="text-white text-decoration-none footer-link">Beranda</a></li>
                    <li><a href="/hasil-vote" class="text-white text-decoration-none footer-link">Hasil Vote</a></li>
                    <li><a href="{{ route('about') }}" class="text-white text-decoration-none footer-link">Tentang Kami</a></li>
                </ul>
            </div>

        </div>

    </div>
</footer>

<style>
    /* Footer styles */
    footer {
        width: 100%;
        max-width: 100vw;
        box-sizing: border-box;
    }
    
    footer .container {
        max-width: 100%;
        box-sizing: border-box;
    }
    
    /* Footer link hover */
    .footer-link {
        opacity: 0.9;
        transition: all 0.2s ease;
        display: inline-block;
    }
    
    .footer-link:hover {
        opacity: 1 !important;
        color: #0094FF !important;
        transform: translateX(5px);
    }
    
    /* Social media hover */
    .footer-social {
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .footer-social:hover {
        color: #0094FF !important;
        transform: scale(1.1);
    }
    
    /* Responsive text */
    @media (max-width: 768px) {
        footer h2 {
            font-size: 1.3rem !important;
        }
        
        footer .container {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }
</style>