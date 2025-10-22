@extends('layouts.main')

@section('title', $title ?? 'Hasil Vote')

@section('content')

<section class="hero-results">
    <div class="hero-content">
        <h2 class="fw-bold mb-3">Hasil Pemilihan Ketua OSIS 2025</h2>
        <p class="lead mb-4">Pemungutan Suara Berakhir Dalam</p>
        
        <div class="countdown-section">
            <div class="countdown-boxes">
                <div class="countdown-box">
                    <span class="countdown-number" id="days">00</span>
                    <span class="countdown-label">HARI</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-number" id="hours">00</span>
                    <span class="countdown-label">JAM</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-number" id="minutes">00</span>
                    <span class="countdown-label">MENIT</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-number" id="seconds">00</span>
                    <span class="countdown-label">DETIK</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="results-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="total-votes-card">
                <i class="bi bi-people-fill mb-2"></i>
                <h3 class="fw-bold mb-1">Total Suara Masuk</h3>
                <h1 class="display-3 fw-bold text-success" id="totalVotes">0</h1>
                <p>dari seluruh pemilih</p>
            </div>
        </div>

        <div id="loadingResults" class="text-center py-5">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Memuat hasil voting...</p>
        </div>

        <div id="resultsContainer" class="d-none">
            <div class="row justify-content-center align-items-end g-4 mb-5" id="chartContainer">
                <!-- Candidates will be loaded here -->
            </div>

            <div class="row mt-5 g-4" id="candidateDetailsContainer">
                <!-- Candidate details will be loaded here -->
            </div>
        </div>

        <div id="noResults" class="text-center py-5 d-none">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #6c757d;"></i>
            <p class="mt-3">Belum ada hasil voting</p>
        </div>
    </div>
</section>

<script>
const targetDate = new Date("2025-11-05T23:59:59+08:00").getTime();
let candidates = [];
let updateInterval;

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
        document.querySelector(".countdown-section").innerHTML = `
            <div class="alert alert-success">
                <h4 class="fw-bold mb-0">🎉 Pemungutan Suara Telah Ditutup!</h4>
            </div>
        `;
    }
}, 1000);

document.addEventListener('DOMContentLoaded', function() {
    loadResults();
    
    updateInterval = setInterval(loadResults, 5000);
});

async function loadResults() {
    try {
        const response = await fetch('/voting/results-api', {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'include'
        });

        if (!response.ok) throw new Error('Failed to load results');
        
        const result = await response.json();
        candidates = result.data || [];
        
        displayResults();
    } catch (error) {
        console.error('Error loading results:', error);
        showNoResults();
    }
}

function displayResults() {
    const chartContainer = document.getElementById('chartContainer');
    const detailsContainer = document.getElementById('candidateDetailsContainer');
    const loadingDiv = document.getElementById('loadingResults');
    const resultsDiv = document.getElementById('resultsContainer');
    const noResultsDiv = document.getElementById('noResults');
    
    loadingDiv.classList.add('d-none');
    
    if (candidates.length === 0) {
        showNoResults();
        return;
    }

    noResultsDiv.classList.add('d-none');
    resultsDiv.classList.remove('d-none');

    const totalVotes = candidates.reduce((sum, c) => sum + (c.total_votes || 0), 0);
    document.getElementById('totalVotes').textContent = totalVotes;

    const maxVotes = Math.max(...candidates.map(c => c.total_votes || 0));
    const maxHeight = 400;

    chartContainer.innerHTML = candidates.map((candidate, index) => {
        const votes = candidate.total_votes || 0;
        const percentage = totalVotes > 0 ? ((votes / totalVotes) * 100).toFixed(1) : 0;
        const height = maxVotes > 0 ? (votes / maxVotes) * maxHeight : 50;
        const barColor = getBarColor(index);
        
        return `
            <div class="col-md-4 col-6">
                <div class="bar-wrapper">
                    <div class="bar animate-bar" style="
                        height: ${height}px;
                        background: ${barColor};
                        transition: height 0.8s ease;
                    ">
                        <div class="bar-content">
                            <div class="vote-count">${votes}</div>
                            <div class="vote-label">SUARA</div>
                        </div>
                    </div>
                    <div class="candidate-info mt-3">
                        ${candidate.image ? 
                            `<img src="/storage/${candidate.image}" class="candidate-thumb" alt="${candidate.name}">` :
                            `<div class="candidate-thumb-placeholder">
                                <i class="bi bi-person-circle"></i>
                            </div>`
                        }
                        <h5 class="candidate-name mt-2">${candidate.name}</h5>
                        <p class="candidate-class small">${candidate.class}</p>
                        <div class="percentage-badge">${percentage}%</div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    detailsContainer.innerHTML = candidates.map((candidate, index) => {
        const votes = candidate.total_votes || 0;
        const percentage = totalVotes > 0 ? ((votes / totalVotes) * 100).toFixed(1) : 0;
        const badgeColor = getBadgeColor(index);
        
        return `
            <div class="col-md-6">
                <div class="candidate-detail-card">
                    <div class="rank-badge" style="background: ${badgeColor};">
                        #${index + 1}
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            ${candidate.image ? 
                                `<img src="/storage/${candidate.image}" class="candidate-detail-img" alt="${candidate.name}">` :
                                `<div class="candidate-detail-img-placeholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>`
                            }
                        </div>
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-2">${candidate.name}</h4>
                            <p class="mb-3">
                                <i class="bi bi-mortarboard me-2"></i>${candidate.class}
                            </p>
                            <div class="vote-stats">
                                <div class="stat-item">
                                    <i class="bi bi-heart-fill text-danger"></i>
                                    <span class="stat-value">${votes}</span>
                                </div>
                                <div class="stat-item">
                                    <i class="bi bi-graph-up text-success"></i>
                                    <span class="stat-value">${percentage}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    setTimeout(() => {
        document.querySelectorAll('.animate-bar').forEach(bar => {
            bar.style.opacity = '1';
        });
    }, 100);
}

function showNoResults() {
    document.getElementById('loadingResults').classList.add('d-none');
    document.getElementById('resultsContainer').classList.add('d-none');
    document.getElementById('noResults').classList.remove('d-none');
}

function getBarColor(index) {
    const colors = [
        'linear-gradient(180deg, #4ade80 0%, #22c55e 100%)',
        'linear-gradient(180deg, #60a5fa 0%, #3b82f6 100%)',
        'linear-gradient(180deg, #fbbf24 0%, #f59e0b 100%)',
        'linear-gradient(180deg, #f472b6 0%, #ec4899 100%)',
    ];
    return colors[index % colors.length];
}

function getBadgeColor(index) {
    const colors = ['#22c55e', '#3b82f6', '#f59e0b', '#ec4899'];
    return colors[index % colors.length];
}
</script>

<style>
body {
    background-color: #061128;
    color: white;
    font-family: "Poppins", sans-serif;
}

/* Hero Section */
.hero-results {
    background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
    padding: 80px 20px 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-results::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="none"/><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.countdown-section {
    margin-top: 30px;
}

.countdown-boxes {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.countdown-box {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 15px 20px;
    min-width: 80px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.countdown-number {
    display: block;
    font-size: 32px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.countdown-label {
    display: block;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.8);
    margin-top: 5px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Results Section */
.results-section {
    background-color: #061128;
    min-height: 70vh;
}

.total-votes-card {
    background: linear-gradient(135deg, #243558 0%, #1a2845 100%);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    margin: 0 auto;
}

.total-votes-card i {
    font-size: 3rem;
    color: #4ade80;
}

/* Chart Bars */
.bar-wrapper {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.bar {
    width: 100%;
    max-width: 150px;
    border-radius: 15px 15px 0 0;
    position: relative;
    box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.8s ease;
    margin-bottom: 25px;
}

.bar-content {
    text-align: center;
}

.vote-count {
    font-size: 48px;
    font-weight: 700;
    color: #ffffff;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    display: block;
}

.vote-label {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    letter-spacing: 2px;
}

.candidate-info {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}


.candidate-thumb {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.candidate-thumb-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 3px solid rgba(255, 255, 255, 0.2);
}

.candidate-thumb-placeholder i {
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.5);
}

.candidate-name {
    color: #ffffff;
    font-size: 16px;
    font-weight: 600;
    margin-top: 10px;
    margin-bottom: 5px;
}

.candidate-class {
    font-size: 13px;
    margin-bottom: 8px;
}

.percentage-badge {
    display: inline-block;
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    margin-top: 5px;
}

/* Candidate Detail Cards */
.candidate-detail-card {
    background: linear-gradient(135deg, #243558 0%, #1a2845 100%);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.candidate-detail-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
}

.rank-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    color: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.candidate-detail-img {
    width: 150px;
    height: 150px;
    border-radius: 15px;
    object-fit: cover;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.candidate-detail-img-placeholder {
    width: 150px;
    height: 150px;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.candidate-detail-img-placeholder i {
    font-size: 5rem;
    color: rgba(255, 255, 255, 0.3);
}

.vote-stats {
    display: flex;
    gap: 30px;
    margin-top: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stat-item i {
    font-size: 1.5rem;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #ffffff;
}

.stat-label {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
    margin-left: 5px;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-results {
        padding: 60px 15px 40px;
    }
    
    .countdown-box {
        min-width: 70px;
        padding: 12px 15px;
    }
    
    .countdown-number {
        font-size: 28px;
    }
    
    .vote-count {
        font-size: 36px;
    }
    
    .bar {
        max-width: 120px;
    }
    
    .total-votes-card {
        padding: 30px 20px;
    }
    
    .candidate-detail-card {
        padding: 20px;
    }
    
    .vote-stats {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

@endsection