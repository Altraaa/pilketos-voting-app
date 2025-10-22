@extends('layouts.admin.adminLayout')

@section('title', 'User Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="header-content">
            <div>
                <h2 class="page-title mb-2">Manajemen User</h2>
                <p class="page-subtitle">Generate dan kelola akun user pemilih</p>
            </div>
            <div class="header-actions">
                <button class="btn-action-header btn-export" onclick="exportUsers()">
                    <i class="bi bi-download me-2"></i>Export Data
                </button>
                <button class="btn-action-header btn-generate" data-bs-toggle="modal" data-bs-target="#generateModal">
                    <i class="bi bi-lightning-charge me-2"></i>Generate Users
                </button>
            </div>
        </div>
    </div>

    <div id="alertContainer"></div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number" id="totalUsers">0</h3>
                <p class="stat-label">Total Users</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number" id="votedUsers">0</h3>
                <p class="stat-label">Sudah Vote</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number" id="notVotedUsers">0</h3>
                <p class="stat-label">Belum Vote</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <i class="bi bi-percent"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number" id="participationRate">0%</h3>
                <p class="stat-label">Partisipasi</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <div class="table-header">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari user..." onkeyup="filterUsers()">
            </div>
            <div class="table-actions">
                <button class="btn-table-action btn-delete-selected" onclick="showDeleteMultipleConfirmation()" id="deleteSelectedBtn" style="display: none;">
                    <i class="bi bi-trash me-2"></i>Hapus Terpilih (<span id="selectedCount">0</span>)
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="modern-table" id="usersTable">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>Unique Code</th>
                        <th>Password</th>
                        <th>Status Vote</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Users will be loaded here -->
                </tbody>
            </table>
        </div>

        <div id="loadingTable" class="loading-state">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Memuat data users...</p>
        </div>

        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="bi bi-inbox"></i>
            <p>Belum ada user. Klik "Generate Users" untuk membuat user baru.</p>
        </div>
    </div>
</div>

<!-- Generate Users Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Generate Users Otomatis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <p class="text-muted mb-4">Pilih jumlah user yang ingin di-generate. Setiap user akan mendapatkan unique code dan password acak.</p>
                
                <div class="generate-options">
                    <button class="generate-option-btn" onclick="selectGenerateCount(1)">
                        <i class="bi bi-person-fill"></i>
                        <span class="option-number">1</span>
                        <span class="option-label">User</span>
                    </button>
                    <button class="generate-option-btn" onclick="selectGenerateCount(10)">
                        <i class="bi bi-people-fill"></i>
                        <span class="option-number">10</span>
                        <span class="option-label">Users</span>
                    </button>
                    <button class="generate-option-btn" onclick="selectGenerateCount(50)">
                        <i class="bi bi-people-fill"></i>
                        <span class="option-number">50</span>
                        <span class="option-label">Users</span>
                    </button>
                    <button class="generate-option-btn" onclick="selectGenerateCount(100)">
                        <i class="bi bi-people-fill"></i>
                        <span class="option-number">100</span>
                        <span class="option-label">Users</span>
                    </button>
                    <button class="generate-option-btn" onclick="selectGenerateCount(200)">
                        <i class="bi bi-people-fill"></i>
                        <span class="option-number">200</span>
                        <span class="option-label">Users</span>
                    </button>
                </div>

                <div class="custom-count mt-4">
                    <label class="form-label fw-semibold">Atau masukkan jumlah custom:</label>
                    <input type="number" class="form-control modern-input" id="customCount" min="1" max="500" placeholder="Masukkan jumlah (max 500)">
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-modern" onclick="generateUsers()">
                    <i class="bi bi-lightning-charge me-2"></i>Generate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- User Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4" id="detailContent">
                <!-- Detail will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-body px-4">
                <div class="text-center mb-3">
                    <i class="bi bi-trash-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="text-center text-white fw-semibold mb-3" id="deleteModalTitle">Konfirmasi Hapus</h6>
                <p class="text-center text-light" id="deleteModalMessage">Apakah Anda yakin ingin menghapus user ini?</p>
                <div class="alert alert-warning mt-3" id="deleteWarningAlert" style="display: none;">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <span id="deleteWarningText">Tindakan ini tidak dapat dibatalkan!</span>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger-modern" id="confirmDeleteButton">
                    <i class="bi bi-trash me-2"></i>Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
    --danger-gradient: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    --warning-gradient: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    --info-gradient: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    --card-bg: #1e293b;
    --card-hover-bg: #2d3748;
    --border-color: rgba(255, 255, 255, 0.1);
    --text-primary: #ffffff;
    --text-secondary: #cbd5e1; /* Diubah menjadi lebih terang */
}

body {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    min-height: 100vh;
}

/* Improved text visibility */
.text-muted {
    color: #cbd5e1 !important;
}

.text-light {
    color: #f1f5f9 !important;
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

.header-actions {
    display: flex;
    gap: 12px;
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

.btn-action-header {
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-generate {
    background: var(--primary-gradient);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-generate:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

.btn-export {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid var(--border-color);
}

.btn-export:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon-primary {
    background: rgba(102, 126, 234, 0.2);
    color: #667eea;
}

.stat-icon-success {
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
}

.stat-icon-warning {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.stat-icon-info {
    background: rgba(96, 165, 250, 0.2);
    color: #60a5fa;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 4px 0;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0;
}

/* Table Container */
.table-container {
    background: var(--card-bg);
    border-radius: 20px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.table-header {
    padding: 24px 32px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-box i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
}

.search-box input {
    width: 100%;
    padding: 12px 16px 12px 45px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.08);
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.search-box input::placeholder {
    color: #94a3b8 !important;
    opacity: 1;
}

.table-actions {
    display: flex;
    gap: 12px;
}

.btn-table-action {
    padding: 10px 20px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.btn-delete-selected {
    background: rgba(248, 113, 113, 0.15);
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.btn-delete-selected:hover {
    background: rgba(248, 113, 113, 0.25);
    border-color: rgba(248, 113, 113, 0.5);
}

/* Table */
.table-wrapper {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead th {
    background: rgba(255, 255, 255, 0.03);
    padding: 16px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid var(--border-color);
}

.modern-table tbody tr {
    border-bottom: 1px solid var(--border-color);
    transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.03);
}

.modern-table tbody td {
    padding: 16px;
    color: var(--text-primary);
    font-size: 14px;
}

.user-name {
    font-weight: 600;
    color: var(--text-primary);
}

.code-badge {
    background: rgba(102, 126, 234, 0.2);
    color: #818cf8;
    padding: 6px 12px;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.password-field {
    display: flex;
    align-items: center;
    gap: 8px;
}

.password-text {
    font-family: 'Courier New', monospace;
    background: rgba(255, 255, 255, 0.05);
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    color: var(--text-primary);
}

.btn-copy {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-copy:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-voted {
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
    border: 1px solid rgba(74, 222, 128, 0.3);
}

.status-not-voted {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.role-badge {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.role-admin {
    background: rgba(248, 113, 113, 0.2);
    color: #f87171;
}

.role-user {
    background: rgba(96, 165, 250, 0.2);
    color: #60a5fa;
}

.date-text {
    color: var(--text-secondary);
    font-size: 13px;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action-table {
    padding: 8px 12px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.btn-view {
    background: rgba(102, 126, 234, 0.15);
    color: #818cf8;
    border: 1px solid rgba(102, 126, 234, 0.3);
}

.btn-view:hover {
    background: rgba(102, 126, 234, 0.25);
}

.btn-delete {
    background: rgba(248, 113, 113, 0.15);
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.btn-delete:hover {
    background: rgba(248, 113, 113, 0.25);
}

/* Loading & Empty States */
.loading-state, .empty-state {
    padding: 60px 40px;
    text-align: center;
}

.empty-state i {
    font-size: 60px;
    color: rgba(255, 255, 255, 0.2);
    margin-bottom: 16px;
}

.empty-state p {
    color: var(--text-secondary);
    margin: 0;
}

.loading-state p {
    color: var(--text-secondary);
}

/* Generate Modal */
.generate-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 12px;
}

.generate-option-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 20px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.generate-option-btn:hover {
    background: rgba(102, 126, 234, 0.1);
    border-color: #667eea;
    transform: translateY(-2px);
}

.generate-option-btn.active {
    background: rgba(102, 126, 234, 0.2);
    border-color: #667eea;
}

.generate-option-btn i {
    font-size: 24px;
    color: #667eea;
}

.option-number {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
}

.option-label {
    font-size: 12px;
    color: var(--text-secondary);
}

.generate-option-btn:hover .option-label,
.generate-option-btn.active .option-label {
    color: var(--text-primary);
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
    font-size: 20px;
}

.modern-modal .btn-close {
    filter: invert(0.8);
    opacity: 0.8;
}

.modern-modal .btn-close:hover {
    filter: invert(1);
    opacity: 1;
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
    outline: none;
}

.modern-input::placeholder {
    color: #94a3b8 !important;
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

/* Danger Button */
.btn-danger-modern {
    background: var(--danger-gradient);
    border: none;
    color: white;
    padding: 12px 32px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.btn-danger-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.btn-danger-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    color: #ffffff !important;
}

.alert-success {
    background: var(--success-gradient);
}

.alert-danger {
    background: var(--danger-gradient);
}

.alert-warning {
    background: var(--warning-gradient);
}

.alert-info {
    background: var(--info-gradient);
}

.user-avatar-lg {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 40px;
    color: rgba(255, 255, 255, 0.5);
}

.detail-grid {
    display: grid;
    gap: 16px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item label {
    font-weight: 600;
    color: var(--text-secondary);
    margin: 0;
}

.detail-item span {
    color: var(--text-primary);
}

.password-display {
    display: flex;
    align-items: center;
    gap: 8px;
}

.password-display code {
    background: rgba(255, 255, 255, 0.05);
    padding: 6px 12px;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--text-primary);
}

/* Custom count input styling */
.custom-count .form-label {
    color: var(--text-secondary) !important;
    margin-bottom: 8px;
}

/* Specific fix for custom count input */
#customCount {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid var(--border-color) !important;
    color: #ffffff !important;
    border-radius: 10px;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

#customCount::placeholder {
    color: #94a3b8 !important;
    opacity: 1;
}

#customCount:focus {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2) !important;
    color: #ffffff !important;
    outline: none;
}

/* Delete Confirmation Modal Specific Styles */
#deleteConfirmationModal .modal-title {
    color: #f87171 !important;
}

#deleteConfirmationModal .modal-body {
    padding-bottom: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .search-box {
        max-width: 100%;
    }
    
    .table-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .modal-footer {
        flex-direction: column;
        gap: 10px;
    }
    
    .modal-footer .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 24px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .generate-options {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<script>
// Global variables
let allUsers = [];
let selectedUsers = new Set();
let currentDeleteAction = null;
let currentDeleteData = null;

// Function to get session token
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

// Load users on page load
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    updateStats();
    
    // Setup delete confirmation button
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        if (currentDeleteAction && currentDeleteData) {
            currentDeleteAction(currentDeleteData);
        }
    });
});

// Load users from API
async function loadUsers() {
    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        showLoading();
        
        const response = await fetch('/api/users', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            credentials: 'include'
        });

        if (!response.ok) {
            throw new Error('Failed to load users');
        }

        const result = await response.json();
        
        if (result.success) {
            allUsers = result.data || [];
            displayUsers(allUsers);
            updateStats();
        } else {
            throw new Error(result.message || 'Failed to load users');
        }
        
    } catch (error) {
        console.error('Error loading users:', error);
        showError('Gagal memuat data users: ' + error.message);
    }
}

// Display users in table
function displayUsers(users) {
    const tableBody = document.getElementById('usersTableBody');
    const loadingDiv = document.getElementById('loadingTable');
    const emptyState = document.getElementById('emptyState');

    loadingDiv.style.display = 'none';

    if (users.length === 0) {
        emptyState.style.display = 'block';
        tableBody.innerHTML = '';
        return;
    }

    emptyState.style.display = 'none';

    tableBody.innerHTML = users.map((user, index) => `
        <tr>
            <td>
                <input type="checkbox" class="user-checkbox" value="${user.id}" onchange="toggleUserSelection(${user.id})">
            </td>
            <td>${index + 1}</td>
            <td>
                <div class="user-name">${user.name}</div>
            </td>
            <td>
                <div class="code-badge">
                    <i class="bi bi-key-fill"></i>
                    ${user.unique_code}
                </div>
            </td>
            <td>
                <div class="password-field">
                    <span class="password-text" id="password-${user.id}">${user.password}</span>
                    <button class="btn-copy" onclick="copyToClipboard('${user.password}', 'password-${user.id}')">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </td>
            <td>
                <span class="status-badge ${user.has_voted ? 'status-voted' : 'status-not-voted'}">
                    ${user.has_voted ? 'Sudah Vote' : 'Belum Vote'}
                </span>
            </td>
            <td>
                <span class="role-badge role-${user.role}">
                    ${user.role}
                </span>
            </td>
            <td>
                <div class="date-text">
                    ${formatDate(user.created_at)}
                </div>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action-table btn-view" onclick="showUserDetail(${user.id})">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn-action-table btn-delete" onclick="showDeleteConfirmation(${user.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Update statistics
function updateStats() {
    const totalUsers = allUsers.length;
    const votedUsers = allUsers.filter(user => user.has_voted).length;
    const notVotedUsers = totalUsers - votedUsers;
    const participationRate = totalUsers > 0 ? ((votedUsers / totalUsers) * 100).toFixed(1) : 0;

    document.getElementById('totalUsers').textContent = totalUsers;
    document.getElementById('votedUsers').textContent = votedUsers;
    document.getElementById('notVotedUsers').textContent = notVotedUsers;
    document.getElementById('participationRate').textContent = participationRate + '%';
}

// Show loading state
function showLoading() {
    document.getElementById('loadingTable').style.display = 'block';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('usersTableBody').innerHTML = '';
}

// Show error state
function showError(message) {
    const loadingDiv = document.getElementById('loadingTable');
    const tableBody = document.getElementById('usersTableBody');
    
    loadingDiv.style.display = 'none';
    tableBody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center py-4">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${message}
                    <button class="btn btn-sm btn-outline-light ms-2" onclick="loadUsers()">Coba Lagi</button>
                </div>
            </td>
        </tr>
    `;
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Copy to clipboard
function copyToClipboard(text, elementId) {
    navigator.clipboard.writeText(text).then(() => {
        const element = document.getElementById(elementId);
        const originalText = element.textContent;
        element.textContent = 'Copied!';
        element.style.color = '#4ade80';
        
        setTimeout(() => {
            element.textContent = originalText;
            element.style.color = '';
        }, 1500);
    }).catch(err => {
        console.error('Failed to copy: ', err);
        showAlert('Gagal menyalin teks', 'error');
    });
}

// Filter users
function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    if (searchTerm === '') {
        displayUsers(allUsers);
        return;
    }
    
    const filteredUsers = allUsers.filter(user => 
        user.name.toLowerCase().includes(searchTerm) ||
        user.unique_code.toLowerCase().includes(searchTerm) ||
        user.role.toLowerCase().includes(searchTerm)
    );
    
    displayUsers(filteredUsers);
}

// User selection functions
function toggleUserSelection(userId) {
    if (selectedUsers.has(userId)) {
        selectedUsers.delete(userId);
    } else {
        selectedUsers.add(userId);
    }
    updateSelectionUI();
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    
    if (selectAll.checked) {
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
            selectedUsers.add(parseInt(checkbox.value));
        });
    } else {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectedUsers.clear();
    }
    
    updateSelectionUI();
}

function updateSelectionUI() {
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedCount.textContent = selectedUsers.size;
    
    if (selectedUsers.size > 0) {
        deleteBtn.style.display = 'flex';
    } else {
        deleteBtn.style.display = 'none';
    }
    
    // Update select all checkbox
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectAll = document.getElementById('selectAll');
    selectAll.checked = checkboxes.length > 0 && selectedUsers.size === checkboxes.length;
}

// Show delete confirmation for single user
function showDeleteConfirmation(userId) {
    const user = allUsers.find(u => u.id === userId);
    if (!user) return;
    
    document.getElementById('deleteModalTitle').textContent = 'Konsfirmasi Hapus User';
    document.getElementById('deleteModalMessage').innerHTML = `
        Anda akan menghapus user: <strong>"${user.name}"</strong><br>
        <span class="text-muted">${user.unique_code}</span>
    `;
    document.getElementById('deleteWarningAlert').style.display = 'block';
    document.getElementById('deleteWarningText').textContent = 'Tindakan ini tidak dapat dibatalkan!';
    
    currentDeleteAction = performDeleteUser;
    currentDeleteData = userId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

// Show delete confirmation for multiple users
function showDeleteMultipleConfirmation() {
    if (selectedUsers.size === 0) return;
    
    document.getElementById('deleteModalTitle').textContent = 'Konfirmasi Hapus Multiple User';
    document.getElementById('deleteModalMessage').innerHTML = `
        Anda akan menghapus <strong>${selectedUsers.size} user</strong> yang dipilih.
    `;
    document.getElementById('deleteWarningAlert').style.display = 'block';
    document.getElementById('deleteWarningText').textContent = 'Tindakan ini tidak dapat dibatalkan!';
    
    currentDeleteAction = performDeleteSelected;
    currentDeleteData = null;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

// Perform single user deletion
async function performDeleteUser(userId) {
    const deleteButton = document.getElementById('confirmDeleteButton');
    const originalText = deleteButton.innerHTML;
    
    try {
        deleteButton.disabled = true;
        deleteButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menghapus...';
        
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch(`/api/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to delete user');
        }

        if (result.success) {
            showAlert(result.message, 'success');
            loadUsers();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            modal.hide();
        }
        
    } catch (error) {
        console.error('Error deleting user:', error);
        showAlert('Gagal menghapus user: ' + error.message, 'error');
    } finally {
        deleteButton.disabled = false;
        deleteButton.innerHTML = originalText;
    }
}

// Perform multiple users deletion
async function performDeleteSelected() {
    const deleteButton = document.getElementById('confirmDeleteButton');
    const originalText = deleteButton.innerHTML;
    
    try {
        deleteButton.disabled = true;
        deleteButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menghapus...';
        
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch('/api/users/delete-multiple', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                ids: Array.from(selectedUsers)
            })
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to delete users');
        }

        if (result.success) {
            showAlert(result.message, 'success');
            selectedUsers.clear();
            loadUsers();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            modal.hide();
        }
        
    } catch (error) {
        console.error('Error deleting users:', error);
        showAlert('Gagal menghapus user: ' + error.message, 'error');
    } finally {
        deleteButton.disabled = false;
        deleteButton.innerHTML = originalText;
    }
}

// Generate users functions
let selectedCount = 0;

function selectGenerateCount(count) {
    selectedCount = count;
    
    // Update UI
    document.querySelectorAll('.generate-option-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Find and activate the clicked button
    const buttons = document.querySelectorAll('.generate-option-btn');
    buttons.forEach(btn => {
        if (btn.querySelector('.option-number').textContent == count) {
            btn.classList.add('active');
        }
    });
    
    // Also update custom input
    document.getElementById('customCount').value = count;
}

async function generateUsers() {
    let count = selectedCount;
    
    // Check if custom count is used
    const customCount = document.getElementById('customCount').value;
    if (customCount && customCount > 0) {
        count = parseInt(customCount);
    }
    
    if (!count || count < 1 || count > 500) {
        showAlert('Masukkan jumlah user yang valid (1-500)', 'error');
        return;
    }
    
    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch('/api/users/generate-bulk', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                count: count
            })
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to generate users');
        }

        if (result.success) {
            showAlert(result.message, 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('generateModal'));
            modal.hide();
            
            // Reload users
            loadUsers();
            
            // Reset selection
            selectedCount = 0;
            document.querySelectorAll('.generate-option-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById('customCount').value = '';
        }
        
    } catch (error) {
        console.error('Error generating users:', error);
        showAlert('Gagal generate user: ' + error.message, 'error');
    }
}

// Show user detail - Improved version with better text visibility
async function showUserDetail(userId) {
    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch(`/api/users/${userId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to load user detail');
        }

        if (result.success) {
            const user = result.data;
            const modalContent = document.getElementById('detailContent');
            
            modalContent.innerHTML = `
                <div class="user-detail">
                    <div class="text-center mb-4">
                        <div class="user-avatar-lg">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h4 class="fw-bold mt-3 text-white">${user.name}</h4>
                        <p class="text-light">${user.unique_code}</p>
                    </div>
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="text-light">Role</label>
                            <span class="role-badge role-${user.role}">${user.role}</span>
                        </div>
                        <div class="detail-item">
                            <label class="text-light">Status Vote</label>
                            <span class="status-badge ${user.has_voted ? 'status-voted' : 'status-not-voted'}">
                                ${user.has_voted ? 'Sudah Vote' : 'Belum Vote'}
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="text-light">Password</label>
                            <div class="password-display">
                                <code class="text-white">${user.password}</code>
                                <button class="btn-copy" onclick="copyToClipboard('${user.password}')" title="Salin password">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="text-light">Dibuat</label>
                            <span class="text-white">${formatDate(user.created_at)}</span>
                        </div>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }
        
    } catch (error) {
        console.error('Error loading user detail:', error);
        showAlert('Gagal memuat detail user: ' + error.message, 'error');
    }
}

// Export users
function exportUsers() {
    showAlert('Memproses export data...', 'info');
    
    window.location.href = '/users/export';
    
    setTimeout(() => {
        showAlert('Data berhasil diexport!', 'success');
    }, 1000);
}

function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alertContainer');
    const alertId = 'alert-' + Date.now();
    
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';
    
    const alertHTML = `
        <div id="${alertId}" class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-${getAlertIcon(type)} me-2"></i>
                <div>${message}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    alertContainer.innerHTML = alertHTML;
    
    setTimeout(() => {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.remove();
        }
    }, 5000);
}

function getAlertIcon(type) {
    const icons = {
        'success': 'check-circle-fill',
        'error': 'exclamation-triangle-fill',
        'warning': 'exclamation-triangle-fill',
        'info': 'info-circle-fill'
    };
    return icons[type] || 'info-circle-fill';
}

document.getElementById('customCount').addEventListener('input', function() {
    const value = parseInt(this.value);
    if (value && value > 0) {
        selectedCount = value;
        document.querySelectorAll('.generate-option-btn').forEach(btn => {
            btn.classList.remove('active');
        });
    }
});
</script>
@endsection