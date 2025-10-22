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
    
    <!-- Status Voting -->
    <div class="voting-status mt-4">
      <div id="statusIndicator" class="status-indicator">
        <div class="status-dot"></div>
        <span id="statusText">Memeriksa status voting...</span>
      </div>
    </div>
  </div>
</section>

<!-- Alert Container -->
<div id="alertContainer" class="container mt-3"></div>

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

    <div class="row justify-content-center g-4" id="candidatesContainer">
      <!-- Loading spinner -->
      <div class="col-12 text-center" id="loadingSpinner">
        <div class="spinner-border text-light" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Memuat kandidat...</p>
      </div>
    </div>
  </div>
</section>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white rounded-4 shadow-lg border-0">
      <div class="modal-body text-center p-4">
        <h5 class="fw-bold mb-3" id="konfirmasiLabel">Konfirmasi Pilihan</h5>
        <div id="candidateInfo" class="mb-3">
          <!-- Candidate info will be inserted here -->
        </div>
        <p>Apakah Anda yakin memilih kandidat ini? Pilihan tidak dapat diubah.</p>
        <div class="d-flex justify-content-center gap-3 mt-4">
          <button type="button" class="btn btn-success px-4" id="confirmVoteBtn">
            <span class="spinner-border spinner-border-sm me-2 d-none" id="voteSpinner"></span>
            Pilih Sekarang
          </button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const API_BASE_URL = '/api';
const targetDate = new Date("2025-10-23T10:00:00+08:00").getTime();
let candidates = [];
let categories = [];
let votingEnabled = false;
let selectedCandidateId = null;
let statusCheckInterval;

const countdownInterval = setInterval(() => {
  const now = new Date().getTime();
  const distance = targetDate - now;

  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);

  document.getElementById("days").innerText = days.toString().padStart(2, '0');
  document.getElementById("hours").innerText = hours.toString().padStart(2, '0');
  document.getElementById("minutes").innerText = minutes.toString().padStart(2, '0');
  document.getElementById("seconds").innerText = seconds.toString().padStart(2, '0');

  if (distance < 0) {
    clearInterval(countdownInterval);
    document.querySelector(".countdown").innerHTML = `
      <h4 class="text-success fw-bold">🎉 Voting Telah Dibuka!</h4>
    `;
  }
}, 1000);

document.addEventListener('DOMContentLoaded', function() {
  loadCategories();
  loadCandidates();
  
  document.getElementById('confirmVoteBtn').addEventListener('click', submitVote);
  
  // Start checking voting status every 5 seconds
  statusCheckInterval = setInterval(checkVotingStatus, 5000);
});

// Clean up on page unload
window.addEventListener('beforeunload', function() {
  if (statusCheckInterval) {
    clearInterval(statusCheckInterval);
  }
});

async function getSessionToken() {
  try {
    const response = await fetch('/get-token', {
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'include'
    });
    
    if (response.ok) {
      const data = await response.json();
      return data.token;
    }
  } catch (error) {
    console.error('Failed to get token:', error);
  }
  return null;
}

async function loadCategories() {
  try {
    const token = await getSessionToken();
    
    if (!token) {
      showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
      setTimeout(() => window.location.href = '/login', 2000);
      return;
    }

    const response = await fetch(`${API_BASE_URL}/categories/active`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      credentials: 'include'
    });

    if (response.status === 401) {
      showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
      setTimeout(() => window.location.href = '/login', 2000);
      return;
    }

    if (response.status === 404) {
      // Tidak ada kategori aktif
      categories = [];
      votingEnabled = false;
      updateVotingStatus();
      return;
    }

    if (!response.ok) throw new Error('Failed to load categories');
    
    const result = await response.json();
    categories = result.data ? result.data : result;
    
    // Check if there are active categories
    votingEnabled = categories.length > 0;
    updateVotingStatus();
  } catch (error) {
    showAlert('Gagal memuat data kategori', 'danger');
    console.error(error);
  }
}

async function checkVotingStatus() {
  try {
    const token = await getSessionToken();
    
    if (!token) {
      return;
    }

    const response = await fetch(`${API_BASE_URL}/categories/active`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      credentials: 'include'
    });

    if (response.status === 404) {
      // Tidak ada kategori aktif
      const newVotingStatus = false;
      
      // If voting status changed, update UI
      if (newVotingStatus !== votingEnabled) {
        votingEnabled = newVotingStatus;
        updateVotingStatus();
        
        // Only reload candidates if we have candidates loaded
        if (candidates.length > 0) {
          loadCandidates();
        }
      }
      return;
    }

    if (response.ok) {
      const result = await response.json();
      const activeCategories = result.data ? result.data : result;
      const newVotingStatus = activeCategories.length > 0;
      
      // If voting status changed, update UI
      if (newVotingStatus !== votingEnabled) {
        votingEnabled = newVotingStatus;
        updateVotingStatus();
        
        // Only reload candidates if we have candidates loaded
        if (candidates.length > 0) {
          loadCandidates();
        }
      }
    }
  } catch (error) {
    console.error('Error checking voting status:', error);
    // Don't show alert for this error as it's a background check
  }
}

function updateVotingStatus() {
  const statusIndicator = document.getElementById('statusIndicator');
  const statusText = document.getElementById('statusText');
  const statusDot = statusIndicator.querySelector('.status-dot');
  
  if (votingEnabled) {
    statusIndicator.className = 'status-indicator active';
    statusText.textContent = 'Voting Aktif - Anda dapat memilih kandidat sekarang';
    statusDot.style.backgroundColor = '#4ade80';
  } else {
    statusIndicator.className = 'status-indicator inactive';
    statusText.textContent = 'Voting Nonaktif - Belum waktu memilih';
    statusDot.style.backgroundColor = '#f87171';
  }
  
  // Update all vote buttons if they exist
  const voteButtons = document.querySelectorAll('.vote-btn');
  if (voteButtons.length > 0) {
    voteButtons.forEach(button => {
      if (votingEnabled) {
        button.classList.remove('btn-secondary', 'disabled');
        button.classList.add('btn-success');
        button.disabled = false;
        button.innerHTML = '<i class="bi bi-check-circle me-2"></i>Pilih Sekarang';
      } else {
        button.classList.remove('btn-success');
        button.classList.add('btn-secondary', 'disabled');
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-check-circle me-2"></i>Voting Nonaktif';
      }
    });
  }
}

function openVoteModal(candidateId) {
  if (!votingEnabled) {
    showAlert('Voting saat ini nonaktif. Silakan tunggu admin mengaktifkan voting.', 'warning');
    return;
  }
  
  const candidate = candidates.find(c => c.id === candidateId);
  if (!candidate) return;

  selectedCandidateId = candidateId;
  
  // Find candidate category
  const category = categories.find(c => c.id === candidate.category_id);
  const categoryName = category ? category.name : 'Tidak ada kategori';
  
  document.getElementById('candidateInfo').innerHTML = `
    <div class="text-center mb-3">
      ${candidate.image ? 
        `<img src="/storage/${candidate.image}" class="rounded mx-auto d-block" style="max-width: 150px; max-height: 150px; object-fit: cover;" alt="${candidate.name}">` :
        `<div class="bg-secondary rounded mx-auto d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
          <i class="bi bi-person-circle" style="font-size: 4rem; color: #ccc;"></i>
        </div>`
      }
      <h6 class="fw-bold mb-1 mt-3">${candidate.name}</h6>
      <p class="text-muted small mb-0">${candidate.class}</p>
      <span class="badge bg-primary mt-2">${categoryName}</span>
    </div>
  `;

  new bootstrap.Modal(document.getElementById('konfirmasiModal')).show();
}

async function loadCandidates() {
  try {
    const token = await getSessionToken();
    
    if (!token) {
      showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
      setTimeout(() => window.location.href = '/login', 2000);
      return;
    }

    // Show loading indicator if not already showing
    const loadingSpinner = document.getElementById('loadingSpinner');
    if (!loadingSpinner) {
      const container = document.getElementById('candidatesContainer');
      container.innerHTML = `
        <div class="col-12 text-center" id="loadingSpinner">
          <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Memuat kandidat...</p>
        </div>
      `;
    }

    const response = await fetch(`${API_BASE_URL}/candidates`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      credentials: 'include'
    });

    if (response.status === 401) {
      showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
      setTimeout(() => window.location.href = '/login', 2000);
      return;
    }

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const result = await response.json();
    
    // Check if the response has error property
    if (result.error === true) {
      throw new Error(result.message || 'Failed to load candidates');
    }
    
    candidates = result.data ? result.data : result;
    displayCandidates();
  } catch (error) {
    console.error('Error in loadCandidates:', error);
    
    // Only show alert if it's not a network error or aborted request
    if (error.name !== 'AbortError' && !error.message.includes('HTTP error! status: 0')) {
      showAlert('Gagal memuat data kandidat: ' + error.message, 'danger');
    }
    
    // Show error state in UI
    const container = document.getElementById('candidatesContainer');
    if (container) {
      container.innerHTML = `
        <div class="col-12">
          <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Gagal memuat kandidat. Silakan refresh halaman.
          </div>
        </div>
      `;
    }
  }
}

function displayCandidates() {
  const container = document.getElementById('candidatesContainer');
  const loadingSpinner = document.getElementById('loadingSpinner');
  
  // Remove loading spinner if it exists
  if (loadingSpinner) {
    loadingSpinner.remove();
  }
  
  if (candidates.length === 0) {
    container.innerHTML = `
      <div class="col-12">
        <div class="alert alert-info">
          <i class="bi bi-info-circle me-2"></i>
          ${votingEnabled ? 'Belum ada kandidat yang terdaftar.' : 'Voting belum dibuka atau belum ada kandidat yang terdaftar.'}
        </div>
      </div>
    `;
    return;
  }

  container.innerHTML = candidates.map((candidate, index) => {
    // Find candidate category
    const category = categories.find(c => c.id === candidate.category_id);
    const categoryName = category ? category.name : 'Tidak ada kategori';
    
    return `
    <div class="col-md-5">
      <div class="card shadow rounded-3 overflow-hidden position-relative d-flex flex-column">
        <div class="position-relative">
          ${candidate.image ? 
            `<img src="/storage/${candidate.image}" class="card-img-top" alt="${candidate.name}">` :
            `<img src="/images/dashboard.jpg" class="card-img-top" alt="${candidate.name}">`
          }
          <div class="nomor-kandidat">${index + 1}</div>
          <div class="category-badge">${categoryName}</div>
        </div>
        <div class="card-body d-flex flex-column flex-grow-1">
          <h5 class="fw-bold">${candidate.name}</h5>
          <p class="text-secondary mb-2">${candidate.class}</p>

          <h6 class="fw-semibold mt-3">Deskripsi</h6>
          <p class="description-text">${candidate.desc}</p>

          <h6 class="fw-semibold mt-3">Visi</h6>
          <p class="vision-text">${candidate.vision}</p>

          <h6 class="fw-semibold mt-3">Misi</h6>
          <div class="mission-text">${formatMission(candidate.mission)}</div>

          <button class="btn vote-btn w-100 mt-auto ${votingEnabled ? 'btn-success' : 'btn-secondary disabled'}" 
                  onclick="openVoteModal(${candidate.id})" 
                  ${!votingEnabled ? 'disabled' : ''}>
            <i class="bi bi-check-circle me-2"></i>
            ${votingEnabled ? 'Pilih Sekarang' : 'Voting Nonaktif'}
          </button>
        </div>
      </div>
    </div>
  `}).join('');
}

// Format mission text into numbered list
function formatMission(mission) {
  // Check if mission already contains numbered list pattern
  if (mission.match(/^\d+\./m)) {
    // Split by numbered items
    const items = mission.split(/(?=\d+\.)/g).filter(item => item.trim());
    return `<ol class="mission-list">${items.map(item => {
      const text = item.replace(/^\d+\.\s*/, '').trim();
      return `<li>${text}</li>`;
    }).join('')}</ol>`;
  }
  
  // If no numbered format, check for sentence breaks
  const sentences = mission.split(/\.\s+/).filter(s => s.trim());
  if (sentences.length > 1) {
    return `<ol class="mission-list">${sentences.map(s => `<li>${s.trim()}${s.endsWith('.') ? '' : '.'}</li>`).join('')}</ol>`;
  }
  
  // Fallback to paragraph
  return `<p>${mission}</p>`;
}

// Open vote confirmation modal
function openVoteModal(candidateId) {
  if (!votingEnabled) {
    showAlert('Voting saat belum di buka. Silakan tunggu waktu yang telah di tentukan.', 'warning');
    return;
  }
  
  const candidate = candidates.find(c => c.id === candidateId);
  if (!candidate) return;

  selectedCandidateId = candidateId;
  
  // Find candidate category
  const category = categories.find(c => c.id === candidate.category_id);
  const categoryName = category ? category.name : 'Tidak ada kategori';
  
  document.getElementById('candidateInfo').innerHTML = `
    <div class="text-center mb-3">
      ${candidate.image ? 
        `<img src="/storage/${candidate.image}" class="rounded mx-auto d-block" style="max-width: 150px; max-height: 150px; object-fit: cover;" alt="${candidate.name}">` :
        `<div class="bg-secondary rounded mx-auto d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
          <i class="bi bi-person-circle" style="font-size: 4rem; color: #ccc;"></i>
        </div>`
      }
      <h6 class="fw-bold mb-1 mt-3">${candidate.name}</h6>
      <p class="text-muted small mb-0">${candidate.class}</p>
      <span class="badge bg-primary mt-2">${categoryName}</span>
    </div>
  `;

  new bootstrap.Modal(document.getElementById('konfirmasiModal')).show();
}

// Submit vote
async function submitVote() {
  if (!selectedCandidateId) return;

  const confirmBtn = document.getElementById('confirmVoteBtn');
  const spinner = document.getElementById('voteSpinner');
  
  confirmBtn.disabled = true;
  spinner.classList.remove('d-none');

  try {
    const token = await getSessionToken();
    
    if (!token) {
      showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
      setTimeout(() => window.location.href = '/login', 2000);
      return;
    }

    // Get current user ID from session/auth
    const userResponse = await fetch('/api/user', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      credentials: 'include'
    });

    if (!userResponse.ok) {
      throw new Error('Failed to get user info');
    }

    const userData = await userResponse.json();
    const userId = userData.id;

    // Submit vote
    const response = await fetch(`${API_BASE_URL}/votes`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      credentials: 'include',
      body: JSON.stringify({
        user_id: userId,
        candidate_id: selectedCandidateId
      })
    });

    if (response.status === 401) {
      showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
      setTimeout(() => window.location.href = '/login', 2000);
      return;
    }

    const result = await response.json();

    if (!response.ok) {
      // Check if already voted
      if (result.message && result.message.includes('already voted')) {
        showAlert('Anda sudah melakukan voting sebelumnya!', 'warning');
      } else {
        throw new Error(result.message || 'Failed to submit vote');
      }
    } else {
      showAlert('Vote berhasil! Terima kasih atas partisipasi Anda.', 'success');
      
      // Close modal
      const modalElement = document.getElementById('konfirmasiModal');
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      if (modalInstance) {
        modalInstance.hide();
      }
      
      // Remove backdrop manually
      setTimeout(() => {
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
          backdrop.remove();
        }
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
      }, 300);

      // Reload candidates to update vote counts
      setTimeout(() => {
        loadCandidates();
      }, 1500);
    }
  } catch (error) {
    showAlert('Gagal melakukan voting: ' + error.message, 'danger');
    console.error(error);
  } finally {
    confirmBtn.disabled = false;
    spinner.classList.add('d-none');
  }
}

// Show alert message
function showAlert(message, type) {
  const alertHtml = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  `;
  document.getElementById('alertContainer').innerHTML = alertHtml;
  
  // Scroll to alert
  document.getElementById('alertContainer').scrollIntoView({ behavior: 'smooth', block: 'center' });
  
  setTimeout(() => {
    document.getElementById('alertContainer').innerHTML = '';
  }, 5000);
}
</script>

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

  /* Voting Status Indicator */
  .voting-status {
    margin-top: 20px;
  }

  .status-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 10px 20px;
    border-radius: 30px;
    background-color: rgba(0, 0, 0, 0.5);
    transition: all 0.3s ease;
  }

  .status-indicator.active {
    background-color: rgba(74, 222, 128, 0.2);
    border: 1px solid rgba(74, 222, 128, 0.3);
  }

  .status-indicator.inactive {
    background-color: rgba(248, 113, 113, 0.2);
    border: 1px solid rgba(248, 113, 113, 0.3);
  }

  .status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #fbbf24;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(251, 191, 36, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(251, 191, 36, 0);
    }
  }

  /* ===== ALERT ===== */
  #alertContainer {
    position: relative;
    z-index: 1050;
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

  /* ===== KANDIDAT SECTION ===== */
  .kandidat-section {
    padding-top: 80px;
    padding-bottom: 80px;
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
    height: 100%;
    display: flex;
    flex-direction: column;
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

  .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .card h5 {
    font-weight: 700;
    font-size: 1.25rem;
    text-align: left;
  }

  .card h6 {
    font-weight: 600;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
    text-align: left;
  }

  .card p,
  .card li {
    font-size: 14px;
    color: #d6d6d6;
    text-align: left;
  }

  .description-text,
  .vision-text {
    margin-bottom: 0.5rem;
  }

  .mission-text {
    margin-bottom: 1rem;
    flex-grow: 1;
  }

  .mission-list {
    margin: 0;
    padding-left: 1.5rem;
    font-size: 14px;
    color: #d6d6d6;
    list-style-type: decimal;
    list-style-position: outside;
  }

  .mission-list li {
    margin-bottom: 0.5rem;
    line-height: 1.5;
    padding-left: 0.3rem;
  }

  .mission-list li::marker {
    color: #d6d6d6;
    font-weight: 600;
  }

  .card .btn {
    font-weight: 600;
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
  }

  .card .btn-success {
    background-color: #36f992;
    color: #0a1a44;
  }

  .card .btn-success:hover {
    background-color: #2adf80;
    transform: translateY(-2px);
  }

  .card .btn-secondary {
    background-color: #6c757d;
    color: white;
  }

  .card .btn:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
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

  .category-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background-color: rgba(102, 126, 234, 0.9);
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 5px 12px;
    border-radius: 20px;
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

  .modal-content .btn-success:disabled {
    background-color: #6c757d;
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

    .category-badge {
      font-size: 0.7rem;
      padding: 4px 10px;
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

    .category-badge {
      top: 10px;
      right: 10px;
      font-size: 0.65rem;
      padding: 3px 8px;
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

@endsection