@extends('layouts.admin.adminLayout')

@section('title', 'Candidate Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="header-content">
            <div>
                <h2 class="page-title mb-2">Manajemen Kandidat</h2>
                <p class="page-subtitle">Kelola data kandidat pemilihan Ketua OSIS</p>
            </div>
            <div class="d-flex gap-3">
                <div class="category-filter">
                    <select class="form-select modern-input" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                    </select>
                </div>
                <button class="btn-add-candidate" data-bs-toggle="modal" data-bs-target="#candidateModal" onclick="resetForm()">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kandidat
                </button>
            </div>
        </div>
    </div>

    <div id="alertContainer"></div>

    <!-- Candidates Grid -->
    <div class="candidates-grid" id="candidatesContainer">
        <!-- Candidates will be loaded here -->
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="candidateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Kandidat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="candidateForm" enctype="multipart/form-data">
                <div class="modal-body px-4">
                    <input type="hidden" id="candidateId">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Kandidat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control modern-input" id="name" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control modern-input" id="class" required placeholder="Contoh: XII IPA 1">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select modern-input" id="category_id" required>
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control modern-input" id="desc" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Visi <span class="text-danger">*</span></label>
                        <textarea class="form-control modern-input" id="vision" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Misi <span class="text-danger">*</span></label>
                        <textarea class="form-control modern-input" id="mission" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Kandidat</label>
                        <input type="file" class="form-control modern-input" id="image" accept="image/jpeg,image/jpg,image/png">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                        <div id="imagePreview" class="mt-3"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-modern" id="submitBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="submitSpinner"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Kandidat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4" id="detailContent">
                <!-- Detail will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
    --danger-gradient: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    --card-bg: #1e293b;
    --card-hover-bg: #2d3748;
    --border-color: rgba(255, 255, 255, 0.1);
    --text-primary: #ffffff;
    --text-secondary: #94a3b8;
}

body {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    min-height: 100vh;
}

/* Page Header */
.page-header {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 32px;
    border: 1px solid var(--border-color);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.page-subtitle {
    color: var(--text-secondary);
    margin: 0;
    font-size: 15px;
}

.btn-add-candidate {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-add-candidate:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

.category-filter {
    min-width: 200px;
}

/* Candidates Grid */
.candidates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.candidate-card {
    background: var(--card-bg);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.candidate-card:hover {
    transform: translateY(-8px);
    border-color: rgba(102, 126, 234, 0.5);
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.2);
}

.candidate-image-wrapper {
    position: relative;
    width: 100%;
    height: 280px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
}

.candidate-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.candidate-card:hover .candidate-image {
    transform: scale(1.05);
}

.candidate-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
}

.candidate-image-placeholder i {
    font-size: 80px;
    color: rgba(255, 255, 255, 0.2);
}

.candidate-body {
    padding: 24px;
}

.candidate-name {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.candidate-class {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-secondary);
    font-size: 14px;
    margin-bottom: 12px;
}

.candidate-class i {
    color: #667eea;
}

.candidate-category {
    display: inline-block;
    background: rgba(102, 126, 234, 0.2);
    color: #818cf8;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
}

.candidate-desc {
    color: var(--text-secondary);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
}

.candidate-actions {
    display: flex;
    gap: 10px;
}

.btn-action {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-detail {
    background: rgba(102, 126, 234, 0.15);
    color: #818cf8;
    border: 1px solid rgba(102, 126, 234, 0.3);
}

.btn-detail:hover {
    background: rgba(102, 126, 234, 0.25);
    border-color: rgba(102, 126, 234, 0.5);
}

.btn-edit {
    background: rgba(74, 222, 128, 0.15);
    color: #4ade80;
    border: 1px solid rgba(74, 222, 128, 0.3);
}

.btn-edit:hover {
    background: rgba(74, 222, 128, 0.25);
    border-color: rgba(74, 222, 128, 0.5);
}

.btn-delete {
    background: rgba(248, 113, 113, 0.15);
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.btn-delete:hover {
    background: rgba(248, 113, 113, 0.25);
    border-color: rgba(248, 113, 113, 0.5);
}

/* Empty State */
.empty-state {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 60px 40px;
    text-align: center;
    border: 1px solid var(--border-color);
}

.empty-state i {
    font-size: 80px;
    color: rgba(255, 255, 255, 0.2);
    margin-bottom: 20px;
}

.empty-state p {
    color: var(--text-secondary);
    font-size: 16px;
    margin: 0;
}

/* Modal Styles */
.modern-modal {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.modern-modal .modal-header {
    padding: 24px 32px 16px;
}

.modern-modal .modal-title {
    color: var(--text-primary);
    font-size: 22px;
}

.modern-modal .btn-close {
    filter: invert(1);
    opacity: 0.7;
}

.modern-modal .btn-close:hover {
    opacity: 1;
}

.form-label {
    color: var(--text-primary);
    font-size: 14px;
    margin-bottom: 8px;
}

.modern-input {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    border-radius: 10px;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.modern-input:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    color: var(--text-primary);
}

.modern-input::placeholder {
    color: rgba(255, 255, 255, 0.3);
}

.btn-primary-modern {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 12px 32px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary-modern {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 12px 32px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-secondary-modern:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

/* Image Preview */
#imagePreview img {
    border-radius: 12px;
    border: 2px solid var(--border-color);
    max-width: 200px;
}

/* Detail Modal Content */
.detail-candidate-image {
    width: 100%;
    max-width: 300px;
    height: 300px;
    object-fit: cover;
    border-radius: 16px;
    margin: 0 auto 24px;
    display: block;
    border: 2px solid var(--border-color);
}

.detail-section {
    margin-bottom: 24px;
}

.detail-section h6 {
    color: #667eea;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
}

.detail-section p {
    color: var(--text-secondary);
    line-height: 1.7;
    margin: 0;
}

.detail-category {
    display: inline-block;
    background: rgba(102, 126, 234, 0.2);
    color: #818cf8;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}

/* Alerts */
.alert {
    border-radius: 12px;
    border: none;
    padding: 16px 20px;
    margin-bottom: 20px;
}

.alert-success {
    background: rgba(74, 222, 128, 0.15);
    color: #4ade80;
    border: 1px solid rgba(74, 222, 128, 0.3);
}

.alert-danger {
    background: rgba(248, 113, 113, 0.15);
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.alert-warning {
    background: rgba(251, 191, 36, 0.15);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .btn-add-candidate {
        width: 100%;
        justify-content: center;
    }
    
    .category-filter {
        width: 100%;
        margin-bottom: 15px;
    }
    
    .candidates-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        padding: 24px;
    }
}

@media (max-width: 576px) {
    .candidate-actions {
        flex-wrap: wrap;
    }
    
    .btn-detail {
        flex: 1 1 100%;
    }
}
</style>

<script>
let candidates = [];
let categories = [];
const API_BASE_URL = '/api';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadCandidates();
    
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').innerHTML = 
                    `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('categoryFilter').addEventListener('change', function() {
        const categoryId = this.value;
        if (categoryId) {
            loadCandidatesByCategory(categoryId);
        } else {
            loadCandidates();
        }
    });
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
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }

        const response = await fetch(`${API_BASE_URL}/categories`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            credentials: 'include'
        });

        if (response.status === 401) {
            showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }

        if (!response.ok) throw new Error('Failed to load categories');
        
        const result = await response.json();
        
        categories = result.data ? result.data : result;
        
        // Populate category filter dropdown
        const categoryFilter = document.getElementById('categoryFilter');
        categoryFilter.innerHTML = '<option value="">Semua Kategori</option>';
        
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categoryFilter.appendChild(option);
        });
        
        // Populate category dropdown in form
        const categorySelect = document.getElementById('category_id');
        categorySelect.innerHTML = '<option value="">Pilih Kategori</option>';
        
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    } catch (error) {
        showAlert('Gagal memuat data kategori', 'danger');
        console.error(error);
    }
}

async function loadCandidates() {
    try {
        const token = await getSessionToken();
        
        if (!token) {
            showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
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
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }

        if (!response.ok) throw new Error('Failed to load candidates');
        
        const result = await response.json();
        
        candidates = result.data ? result.data : result;
        displayCandidates();
    } catch (error) {
        showAlert('Gagal memuat data kandidat', 'danger');
        console.error(error);
    }
}

async function loadCandidatesByCategory(categoryId) {
    try {
        const token = await getSessionToken();
        
        if (!token) {
            showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }

        const response = await fetch(`${API_BASE_URL}/candidates/category/${categoryId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            credentials: 'include'
        });

        if (response.status === 401) {
            showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }

        if (!response.ok) throw new Error('Failed to load candidates by category');
        
        const result = await response.json();
        
        candidates = result.data ? result.data : result;
        displayCandidates();
    } catch (error) {
        showAlert('Gagal memuat data kandidat berdasarkan kategori', 'danger');
        console.error(error);
    }
}

function displayCandidates() {
    const container = document.getElementById('candidatesContainer');
    
    if (candidates.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>Belum ada kandidat. Klik tombol "Tambah Kandidat" untuk menambahkan kandidat baru.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = candidates.map(candidate => {
        const category = categories.find(c => c.id === candidate.category_id);
        const categoryName = category ? category.name : 'Tidak ada kategori';
        
        return `
        <div class="candidate-card">
            <div class="candidate-image-wrapper">
                ${candidate.image ? 
                    `<img src="/public/storage/${candidate.image}" class="candidate-image" alt="${candidate.name}">` :
                    `<div class="candidate-image-placeholder">
                        <i class="bi bi-person-circle"></i>
                    </div>`
                }
            </div>
            <div class="candidate-body">
                <h5 class="candidate-name">${candidate.name}</h5>
                <p class="candidate-class">
                    <i class="bi bi-mortarboard"></i>${candidate.class}
                </p>
                <span class="candidate-category">${categoryName}</span>
                <p class="candidate-desc">${truncateText(candidate.desc, 100)}</p>
                
                <div class="candidate-actions">
                    <button class="btn-action btn-detail" onclick="viewDetail(${candidate.id})">
                        <i class="bi bi-eye"></i>Detail
                    </button>
                    <button class="btn-action btn-edit" onclick="editCandidate(${candidate.id})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteCandidate(${candidate.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `}).join('');
}

document.getElementById('candidateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const candidateId = document.getElementById('candidateId').value;
    const formData = new FormData();
    
    formData.append('name', document.getElementById('name').value);
    formData.append('class', document.getElementById('class').value);
    formData.append('desc', document.getElementById('desc').value);
    formData.append('vision', document.getElementById('vision').value);
    formData.append('mission', document.getElementById('mission').value);
    formData.append('category_id', document.getElementById('category_id').value);
    
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('submitSpinner');
    submitBtn.disabled = true;
    spinner.classList.remove('d-none');

    try {
        const token = await getSessionToken();
        
        if (!token) {
            showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
            setTimeout(() => window.location.href = '/login', 2000);
            return;
        }

        let url = `${API_BASE_URL}/candidates`;
        let method = 'POST';
        
        if (candidateId) {
            url = `${API_BASE_URL}/candidates/${candidateId}`;
            formData.append('_method', 'PUT');
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include',
            body: formData
        });

        if (response.status === 401) {
            showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
            setTimeout(() => window.location.href = '/login', 2000);
            return;
        }

        if (!response.ok) throw new Error('Failed to save candidate');

        const result = await response.json();
        showAlert(candidateId ? 'Kandidat berhasil diperbarui' : 'Kandidat berhasil ditambahkan', 'success');
        
        const modalElement = document.getElementById('candidateModal');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }
        
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }, 300);
        
        document.getElementById('candidateForm').reset();
        document.getElementById('candidateId').value = '';
        document.getElementById('imagePreview').innerHTML = '';
        
        // Reload candidates based on current filter
        const categoryId = document.getElementById('categoryFilter').value;
        if (categoryId) {
            loadCandidatesByCategory(categoryId);
        } else {
            loadCandidates();
        }
    } catch (error) {
        showAlert('Gagal menyimpan kandidat', 'danger');
        console.error(error);
    } finally {
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
    }
});

function editCandidate(id) {
    const candidate = candidates.find(c => c.id === id);
    if (!candidate) return;

    document.getElementById('modalTitle').textContent = 'Edit Kandidat';
    document.getElementById('candidateId').value = candidate.id;
    document.getElementById('name').value = candidate.name;
    document.getElementById('class').value = candidate.class;
    document.getElementById('category_id').value = candidate.category_id;
    document.getElementById('desc').value = candidate.desc;
    document.getElementById('vision').value = candidate.vision;
    document.getElementById('mission').value = candidate.mission;
    
    if (candidate.image) {
        document.getElementById('imagePreview').innerHTML = 
            `<img src="/public/storage/${candidate.image}" class="img-thumbnail" style="max-width: 200px;">`;
    }

    new bootstrap.Modal(document.getElementById('candidateModal')).show();
}

function viewDetail(id) {
    const candidate = candidates.find(c => c.id === id);
    if (!candidate) return;
    
    const category = categories.find(c => c.id === candidate.category_id);
    const categoryName = category ? category.name : 'Tidak ada kategori';

    document.getElementById('detailContent').innerHTML = `
        ${candidate.image ? 
            `<img src="/public/storage/${candidate.image}" class="detail-candidate-image" alt="${candidate.name}">` :
            `<div class="detail-candidate-image candidate-image-placeholder">
                <i class="bi bi-person-circle" style="font-size: 80px;"></i>
            </div>`
        }
        
        <h4 class="fw-bold mb-1" style="color: var(--text-primary);">${candidate.name}</h4>
        <p class="candidate-class mb-2">
            <i class="bi bi-mortarboard"></i>${candidate.class}
        </p>
        <span class="detail-category">${categoryName}</span>
        
        <div class="detail-section">
            <h6>Deskripsi</h6>
            <p>${candidate.desc}</p>
        </div>
        
        <div class="detail-section">
            <h6>Visi</h6>
            <p>${candidate.vision}</p>
        </div>
        
        <div class="detail-section">
            <h6>Misi</h6>
            <p>${candidate.mission}</p>
        </div>
    `;

    new bootstrap.Modal(document.getElementById('detailModal')).show();
}

async function deleteCandidate(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus kandidat ini?')) return;

    try {
        const token = await getSessionToken();
        
        if (!token) {
            showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
            setTimeout(() => window.location.href = '/login', 2000);
            return;
        }

        const response = await fetch(`${API_BASE_URL}/candidates/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include'
        });

        if (response.status === 401) {
            showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
            setTimeout(() => window.location.href = '/login', 2000);
            return;
        }

        if (!response.ok) throw new Error('Failed to delete candidate');

        showAlert('Kandidat berhasil dihapus', 'success');
        
        // Reload candidates based on current filter
        const categoryId = document.getElementById('categoryFilter').value;
        if (categoryId) {
            loadCandidatesByCategory(categoryId);
        } else {
            loadCandidates();
        }
    } catch (error) {
        showAlert('Gagal menghapus kandidat', 'danger');
        console.error(error);
    }
}

function resetForm() {
    document.getElementById('modalTitle').textContent = 'Tambah Kandidat';
    document.getElementById('candidateForm').reset();
    document.getElementById('candidateId').value = '';
    document.getElementById('imagePreview').innerHTML = '';
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.getElementById('alertContainer').innerHTML = alertHtml;
    
    setTimeout(() => {
        document.getElementById('alertContainer').innerHTML = '';
    }, 5000);
}

function truncateText(text, length) {
    return text.length > length ? text.substring(0, length) + '...' : text;
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection