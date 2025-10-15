@extends('layouts.main')

@section('title', 'Kandidat Ketua OSIS 2025')

@section('content')
<section class="pt-24 pb-10">
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-2">Pemilihan Ketua OSIS 2025</h1>
        <p class="text-sm text-gray-300">Suaramu Menentukan Masa Depan Organisasi Kita</p>
    </div>

    <div class="mt-10 text-center">
        <h2 class="text-xl font-semibold underline mb-4">Tata Cara Pemilihan</h2>
        <div class="bg-[#11224e] max-w-md mx-auto text-left rounded-xl p-5 space-y-2 text-sm">
            <ol class="list-decimal list-inside space-y-1">
                <li>Setiap siswa berhak memberikan 1 suara untuk 1 kandidat pilihan</li>
                <li>Pemilihan dilaksanakan secara online melalui website ini</li>
                <li>Klik tombol <b>“Vote Sekarang”</b> pada kandidat pilihan anda</li>
                <li>Konfirmasi pilihan anda dan tunggu hasil akhir</li>
                <li>Voting dibuka mulai 28 Oktober - 5 November 2025</li>
            </ol>
        </div>
    </div>

    {{-- Daftar Kandidat --}}
    <div class="max-w-2xl mx-auto mt-10 space-y-8">
        <div class="bg-[#122957] rounded-2xl overflow-hidden">
            <img src="{{ asset('images/candidate1.jpg') }}" alt="Kandidat 1" class="w-full h-60 object-cover">
            <div class="p-5">
                <h3 class="font-bold text-lg">Ni Komang Onny Fridayanti</h3>
                <p class="text-sm text-green-400 mb-2">Calon Ketua OSIS</p>
                <h4 class="font-semibold">Visi</h4>
                <p class="text-sm text-gray-300">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <h4 class="mt-2 font-semibold">Misi</h4>
                <ul class="list-decimal list-inside text-sm text-gray-300 space-y-1">
                    <li>Meningkatkan semangat belajar siswa</li>
                    <li>Menumbuhkan kreativitas di lingkungan sekolah</li>
                </ul>
                <button class="mt-4 bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-5 rounded-full w-full">Pilih Sekarang</button>
            </div>
        </div>
    </div>
</section>
@endsection
