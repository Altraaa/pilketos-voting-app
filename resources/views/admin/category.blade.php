@extends('layouts.admin.adminLayout')

@section('title', 'Category Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="header-content">
            <div>
                <h2 class="page-title mb-2">Manajemen Kategori</h2>
                <p class="page-subtitle">Kelola kategori untuk pengelompokan kandidat</p>
            </div>
            <div class="header-actions">
                <button class="btn-action-header btn-export" onclick="exportCategories()">
                    <i class="bi bi-download me-2"></i>Export Data
                </button>
                <button class="btn-action-header btn-generate" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
                </button>
            </div>
        </div>
    </div>

    <div id="alertContainer"></div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <i class="bi bi-tags-fill"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number" id="totalCategories">0</h3>
                <p class="stat-label">Total Kategori</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number" id="activeCategories">0</h3>
                <p class="stat-label">Kategori Aktif</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <i class="bi bi-eye-slash-fill"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number" id="inactiveCategories">0</h3>
                <p class="stat-label">Kategori Nonaktif</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number" id="totalCandidates">0</h3>
                <p class="stat-label">Total Kandidat</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <div class="table-header">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari kategori..." onkeyup="filterCategories()">
            </div>
            <div class="table-actions">
                <button class="btn-table-action btn-delete-selected" onclick="showDeleteMultipleConfirmation()" id="deleteSelectedBtn" style="display: none;">
                    <i class="bi bi-trash me-2"></i>Hapus Terpilih (<span id="selectedCount">0</span>)
                </button>
                <div class="filter-actions">
                    <select class="form-select modern-select" id="statusFilter" onchange="filterCategories()">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="modern-table" id="categoriesTable">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th width="60">No</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Jumlah Kandidat</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th>Dibuat</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    <!-- Categories will be loaded here -->
                </tbody>
            </table>
        </div>

        <div id="loadingTable" class="loading-state">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Memuat data kategori...</p>
        </div>

        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="bi bi-inbox"></i>
            <p>Belum ada kategori. Klik "Tambah Kategori" untuk membuat kategori baru.</p>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <form id="createCategoryForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control modern-input" id="categoryName" name="name" required placeholder="Masukkan nama kategori">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" class="form-control modern-input" id="categorySlug" name="slug" placeholder="Slug akan otomatis tergenerate">
                        <div class="form-text text-muted">Slug digunakan untuk URL yang lebih bersih</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control modern-input" id="categoryDescription" name="description" rows="3" placeholder="Masukkan deskripsi kategori (opsional)"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Urutan</label>
                                <input type="number" class="form-control modern-input" id="categoryOrder" name="order" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="categoryStatus" name="is_active" checked>
                                    <label class="form-check-label" for="categoryStatus">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-modern" onclick="createCategory()">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <form id="editCategoryForm">
                    <input type="hidden" id="editCategoryId">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control modern-input" id="editCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" class="form-control modern-input" id="editCategorySlug" name="slug">
                        <div class="form-text text-muted">Slug digunakan untuk URL yang lebih bersih</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control modern-input" id="editCategoryDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Urutan</label>
                                <input type="number" class="form-control modern-input" id="editCategoryOrder" name="order" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editCategoryStatus" name="is_active">
                                    <label class="form-check-label" for="editCategoryStatus">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-modern" onclick="updateCategory()">
                    <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Category Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Kategori</h5>
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
                <p class="text-center text-light" id="deleteModalMessage">Apakah Anda yakin ingin menghapus kategori ini?</p>
                <div class="alert alert-warning mt-3" id="deleteWarningAlert" style="display: none;">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <span id="deleteWarningText">Kategori yang memiliki kandidat tidak dapat dihapus!</span>
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
    --text-secondary: #cbd5e1;
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

.candidate-count-badge {
    background: rgba(102, 126, 234, 0.2);
    color: #818cf8;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-active {
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
    border: 1px solid rgba(74, 222, 128, 0.3);
}

.status-inactive {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.order-badge {
    background: rgba(96, 165, 250, 0.2);
    color: #60a5fa;
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    min-width: 40px;
    text-align: center;
}

.date-text {
    color: var(--text-secondary);
    font-size: 13px;
}

.action-buttons {
    display: flex;
    gap: 6px;
}

.btn-action-table {
    padding: 6px 10px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.btn-view {
    background: rgba(102, 126, 234, 0.15);
    color: #818cf8;
    border: 1px solid rgba(102, 126, 234, 0.3);
}

.btn-view:hover {
    background: rgba(102, 126, 234, 0.25);
}

.btn-edit {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.btn-edit:hover {
    background: rgba(34, 197, 94, 0.25);
}

.btn-delete {
    background: rgba(248, 113, 113, 0.15);
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.btn-delete:hover {
    background: rgba(248, 113, 113, 0.25);
}

.btn-toggle {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.btn-toggle:hover {
    background: rgba(245, 158, 11, 0.25);
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

/* Form styles */
.form-text {
    color: var(--text-secondary) !important;
    font-size: 12px;
}

.form-label {
    color: var(--text-secondary) !important;
    font-weight: 600;
    margin-bottom: 8px;
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

/* Additional Styles for Category Management */
.filter-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

.modern-select {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    border-radius: 10px;
    padding: 8px 16px;
    min-width: 150px;
}

.modern-select:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    color: var(--text-primary);
    outline: none;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
    
    .filter-actions {
        width: 100%;
    }
    
    .modern-select {
        min-width: 100%;
    }
    
    .action-buttons {
        flex-wrap: wrap;
    }
    
    .btn-action-table {
        padding: 4px 8px;
        font-size: 11px;
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
    
    .modal-footer {
        flex-direction: column;
        gap: 10px;
    }
    
    .modal-footer .btn {
        width: 100%;
    }
}
</style>

<script>
// Global variables
let allCategories = [];
let selectedCategories = new Set();
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

// Load categories on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    updateStats();
    
    // Setup delete confirmation button
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        if (currentDeleteAction && currentDeleteData) {
            currentDeleteAction(currentDeleteData);
        }
    });

    // Auto-generate slug from name
    document.getElementById('categoryName').addEventListener('input', function() {
        const name = this.value;
        const slugInput = document.getElementById('categorySlug');
        if (name && !slugInput.value) {
            slugInput.value = generateSlug(name);
        }
    });

    document.getElementById('editCategoryName').addEventListener('input', function() {
        const name = this.value;
        const slugInput = document.getElementById('editCategorySlug');
        if (name && !slugInput.value) {
            slugInput.value = generateSlug(name);
        }
    });
});

// Generate slug from name
function generateSlug(name) {
    return name
        .toLowerCase()
        .replace(/[^\w ]+/g, '')
        .replace(/ +/g, '-');
}

// Load categories from API
async function loadCategories() {
    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        showLoading();
        
        const response = await fetch('/api/categories', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            credentials: 'include'
        });

        if (!response.ok) {
            throw new Error('Failed to load categories');
        }

        const result = await response.json();
        
        if (result.success) {
            allCategories = result.data || [];
            displayCategories(allCategories);
            updateStats();
        } else {
            throw new Error(result.message || 'Failed to load categories');
        }
        
    } catch (error) {
        console.error('Error loading categories:', error);
        showError('Gagal memuat data kategori: ' + error.message);
    }
}

// Display categories in table
function displayCategories(categories) {
    const tableBody = document.getElementById('categoriesTableBody');
    const loadingDiv = document.getElementById('loadingTable');
    const emptyState = document.getElementById('emptyState');

    loadingDiv.style.display = 'none';

    if (categories.length === 0) {
        emptyState.style.display = 'block';
        tableBody.innerHTML = '';
        return;
    }

    emptyState.style.display = 'none';

    tableBody.innerHTML = categories.map((category, index) => `
        <tr>
            <td>
                <input type="checkbox" class="category-checkbox" value="${category.id}" onchange="toggleCategorySelection(${category.id})">
            </td>
            <td>${index + 1}</td>
            <td>
                <div class="user-name">${category.name}</div>
                ${category.description ? `<small class="text-muted">${category.description}</small>` : ''}
            </td>
            <td>
                <div class="code-badge">
                    <i class="bi bi-link-45deg"></i>
                    ${category.slug}
                </div>
            </td>
            <td>
                <span class="candidate-count-badge">
                    <i class="bi bi-people-fill me-1"></i>
                    ${category.candidates_count || 0} Kandidat
                </span>
            </td>
            <td>
                <span class="status-badge ${category.is_active ? 'status-active' : 'status-inactive'}">
                    ${category.is_active ? 'Aktif' : 'Nonaktif'}
                </span>
            </td>
            <td>
                <span class="order-badge">${category.order}</span>
            </td>
            <td>
                <div class="date-text">
                    ${formatDate(category.created_at)}
                </div>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action-table btn-view" onclick="showCategoryDetail(${category.id})" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn-action-table btn-edit" onclick="showEditCategoryModal(${category.id})" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn-action-table btn-toggle" onclick="toggleCategoryStatus(${category.id})" title="${category.is_active ? 'Nonaktifkan' : 'Aktifkan'}">
                        <i class="bi ${category.is_active ? 'bi-eye-slash' : 'bi-eye'}"></i>
                    </button>
                    <button class="btn-action-table btn-delete" onclick="showDeleteConfirmation(${category.id})" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Update statistics
function updateStats() {
    const totalCategories = allCategories.length;
    const activeCategories = allCategories.filter(cat => cat.is_active).length;
    const inactiveCategories = totalCategories - activeCategories;
    const totalCandidates = allCategories.reduce((sum, cat) => sum + (cat.candidates_count || 0), 0);

    document.getElementById('totalCategories').textContent = totalCategories;
    document.getElementById('activeCategories').textContent = activeCategories;
    document.getElementById('inactiveCategories').textContent = inactiveCategories;
    document.getElementById('totalCandidates').textContent = totalCandidates;
}

// Show loading state
function showLoading() {
    document.getElementById('loadingTable').style.display = 'block';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('categoriesTableBody').innerHTML = '';
}

// Show error state
function showError(message) {
    const loadingDiv = document.getElementById('loadingTable');
    const tableBody = document.getElementById('categoriesTableBody');
    
    loadingDiv.style.display = 'none';
    tableBody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center py-4">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${message}
                    <button class="btn btn-sm btn-outline-light ms-2" onclick="loadCategories()">Coba Lagi</button>
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

// Filter categories
function filterCategories() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    
    let filteredCategories = allCategories;

    // Filter by search term
    if (searchTerm) {
        filteredCategories = filteredCategories.filter(category => 
            category.name.toLowerCase().includes(searchTerm) ||
            category.slug.toLowerCase().includes(searchTerm) ||
            (category.description && category.description.toLowerCase().includes(searchTerm))
        );
    }

    // Filter by status
    if (statusFilter === 'active') {
        filteredCategories = filteredCategories.filter(category => category.is_active);
    } else if (statusFilter === 'inactive') {
        filteredCategories = filteredCategories.filter(category => !category.is_active);
    }
    
    displayCategories(filteredCategories);
}

// Category selection functions
function toggleCategorySelection(categoryId) {
    if (selectedCategories.has(categoryId)) {
        selectedCategories.delete(categoryId);
    } else {
        selectedCategories.add(categoryId);
    }
    updateSelectionUI();
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.category-checkbox');
    
    if (selectAll.checked) {
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
            selectedCategories.add(parseInt(checkbox.value));
        });
    } else {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectedCategories.clear();
    }
    
    updateSelectionUI();
}

function updateSelectionUI() {
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedCount.textContent = selectedCategories.size;
    
    if (selectedCategories.size > 0) {
        deleteBtn.style.display = 'flex';
    } else {
        deleteBtn.style.display = 'none';
    }
    
    // Update select all checkbox
    const checkboxes = document.querySelectorAll('.category-checkbox');
    const selectAll = document.getElementById('selectAll');
    selectAll.checked = checkboxes.length > 0 && selectedCategories.size === checkboxes.length;
}

// Create new category
async function createCategory() {
    const form = document.getElementById('createCategoryForm');
    const formData = new FormData(form);
    
    const categoryData = {
        name: formData.get('name'),
        slug: formData.get('slug') || undefined,
        description: formData.get('description') || undefined,
        order: parseInt(formData.get('order')) || 0,
        is_active: document.getElementById('categoryStatus').checked
    };

    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch('/api/categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(categoryData)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to create category');
        }

        if (result.success) {
            showAlert(result.message, 'success');
            
            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('createCategoryModal'));
            modal.hide();
            form.reset();
            document.getElementById('categoryStatus').checked = true;
            
            // Reload categories
            loadCategories();
        }
        
    } catch (error) {
        console.error('Error creating category:', error);
        showAlert('Gagal membuat kategori: ' + error.message, 'error');
    }
}

// Show edit category modal
async function showEditCategoryModal(categoryId) {
    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch(`/api/categories/${categoryId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to load category');
        }

        if (result.success) {
            const category = result.data;
            
            // Fill form with category data
            document.getElementById('editCategoryId').value = category.id;
            document.getElementById('editCategoryName').value = category.name;
            document.getElementById('editCategorySlug').value = category.slug;
            document.getElementById('editCategoryDescription').value = category.description || '';
            document.getElementById('editCategoryOrder').value = category.order;
            document.getElementById('editCategoryStatus').checked = category.is_active;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            modal.show();
        }
        
    } catch (error) {
        console.error('Error loading category:', error);
        showAlert('Gagal memuat data kategori: ' + error.message, 'error');
    }
}

// Update category
async function updateCategory() {
    const categoryId = document.getElementById('editCategoryId').value;
    const form = document.getElementById('editCategoryForm');
    const formData = new FormData(form);
    
    const categoryData = {
        name: formData.get('name'),
        slug: formData.get('slug') || undefined,
        description: formData.get('description') || undefined,
        order: parseInt(formData.get('order')) || 0,
        is_active: document.getElementById('editCategoryStatus').checked
    };

    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch(`/api/categories/${categoryId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(categoryData)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to update category');
        }

        if (result.success) {
            showAlert(result.message, 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
            modal.hide();
            
            // Reload categories
            loadCategories();
        }
        
    } catch (error) {
        console.error('Error updating category:', error);
        showAlert('Gagal memperbarui kategori: ' + error.message, 'error');
    }
}

// Toggle category status
async function toggleCategoryStatus(categoryId) {
    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch(`/api/categories/${categoryId}/toggle-status`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to toggle category status');
        }

        if (result.success) {
            showAlert(result.message, 'success');
            loadCategories();
        }
        
    } catch (error) {
        console.error('Error toggling category status:', error);
        showAlert('Gagal mengubah status kategori: ' + error.message, 'error');
    }
}

// Show delete confirmation for single category
function showDeleteConfirmation(categoryId) {
    const category = allCategories.find(cat => cat.id === categoryId);
    if (!category) return;
    
    document.getElementById('deleteModalTitle').textContent = 'Konfirmasi Hapus Kategori';
    document.getElementById('deleteModalMessage').innerHTML = `
        Anda akan menghapus kategori: <strong>"${category.name}"</strong><br>
        <span class="text-muted">${category.slug}</span>
    `;
    
    // Show warning if category has candidates
    if (category.candidates_count > 0) {
        document.getElementById('deleteWarningAlert').style.display = 'block';
        document.getElementById('deleteWarningText').textContent = 
            `Kategori ini memiliki ${category.candidates_count} kandidat dan tidak dapat dihapus!`;
        document.getElementById('confirmDeleteButton').disabled = true;
    } else {
        document.getElementById('deleteWarningAlert').style.display = 'none';
        document.getElementById('confirmDeleteButton').disabled = false;
    }
    
    currentDeleteAction = performDeleteCategory;
    currentDeleteData = categoryId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

// Show delete confirmation for multiple categories
function showDeleteMultipleConfirmation() {
    if (selectedCategories.size === 0) return;
    
    document.getElementById('deleteModalTitle').textContent = 'Konfirmasi Hapus Multiple Kategori';
    document.getElementById('deleteModalMessage').innerHTML = `
        Anda akan menghapus <strong>${selectedCategories.size} kategori</strong> yang dipilih.
    `;
    
    // Check if any selected category has candidates
    const hasCandidates = Array.from(selectedCategories).some(catId => {
        const category = allCategories.find(cat => cat.id === catId);
        return category && category.candidates_count > 0;
    });
    
    if (hasCandidates) {
        document.getElementById('deleteWarningAlert').style.display = 'block';
        document.getElementById('deleteWarningText').textContent = 
            'Beberapa kategori memiliki kandidat dan tidak dapat dihapus!';
        document.getElementById('confirmDeleteButton').disabled = true;
    } else {
        document.getElementById('deleteWarningAlert').style.display = 'none';
        document.getElementById('confirmDeleteButton').disabled = false;
    }
    
    currentDeleteAction = performDeleteSelected;
    currentDeleteData = null;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

// Perform single category deletion
async function performDeleteCategory(categoryId) {
    const deleteButton = document.getElementById('confirmDeleteButton');
    const originalText = deleteButton.innerHTML;
    
    try {
        deleteButton.disabled = true;
        deleteButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menghapus...';
        
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch(`/api/categories/${categoryId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to delete category');
        }

        if (result.success) {
            showAlert(result.message, 'success');
            loadCategories();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            modal.hide();
        }
        
    } catch (error) {
        console.error('Error deleting category:', error);
        showAlert('Gagal menghapus kategori: ' + error.message, 'error');
    } finally {
        deleteButton.disabled = false;
        deleteButton.innerHTML = originalText;
    }
}

// Perform multiple categories deletion
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

        const response = await fetch('/api/categories/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                ids: Array.from(selectedCategories)
            })
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to delete categories');
        }

        if (result.success) {
            showAlert(result.message, 'success');
            selectedCategories.clear();
            loadCategories();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            modal.hide();
        }
        
    } catch (error) {
        console.error('Error deleting categories:', error);
        showAlert('Gagal menghapus kategori: ' + error.message, 'error');
    } finally {
        deleteButton.disabled = false;
        deleteButton.innerHTML = originalText;
    }
}

// Show category detail
async function showCategoryDetail(categoryId) {
    try {
        const token = await getSessionToken();
        if (!token) {
            throw new Error('Token tidak tersedia. Silakan login kembali.');
        }

        const response = await fetch(`/api/categories/${categoryId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to load category detail');
        }

        if (result.success) {
            const category = result.data;
            const modalContent = document.getElementById('detailContent');
            
            modalContent.innerHTML = `
                <div class="category-detail">
                    <div class="text-center mb-4">
                        <div class="user-avatar-lg">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                        <h4 class="fw-bold mt-3 text-white">${category.name}</h4>
                        <p class="text-light">${category.slug}</p>
                    </div>
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="text-light">Deskripsi</label>
                            <span class="text-white">${category.description || 'Tidak ada deskripsi'}</span>
                        </div>
                        <div class="detail-item">
                            <label class="text-light">Status</label>
                            <span class="status-badge ${category.is_active ? 'status-active' : 'status-inactive'}">
                                ${category.is_active ? 'Aktif' : 'Nonaktif'}
                            </span>
                        </div>
                        <div class="detail-item">
                            <label class="text-light">Urutan</label>
                            <span class="text-white">${category.order}</span>
                        </div>
                        <div class="detail-item">
                            <label class="text-light">Jumlah Kandidat</label>
                            <span class="text-white">${category.candidates ? category.candidates.length : 0} Kandidat</span>
                        </div>
                        <div class="detail-item">
                            <label class="text-light">Dibuat</label>
                            <span class="text-white">${formatDate(category.created_at)}</span>
                        </div>
                        <div class="detail-item">
                            <label class="text-light">Diperbarui</label>
                            <span class="text-white">${formatDate(category.updated_at)}</span>
                        </div>
                    </div>

                    ${category.candidates && category.candidates.length > 0 ? `
                    <div class="mt-4">
                        <h6 class="fw-semibold text-light mb-3">Daftar Kandidat</h6>
                        <div class="candidates-list">
                            ${category.candidates.map(candidate => `
                                <div class="candidate-item bg-dark-soft p-3 rounded mb-2">
                                    <div class="d-flex align-items-center">
                                        ${candidate.image ? `
                                            <img src="/storage/${candidate.image}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        ` : `
                                            <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-light"></i>
                                            </div>
                                        `}
                                        <div>
                                            <div class="fw-semibold text-white">${candidate.name}</div>
                                            <small class="text-muted">${candidate.class}</small>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }
        
    } catch (error) {
        console.error('Error loading category detail:', error);
        showAlert('Gagal memuat detail kategori: ' + error.message, 'error');
    }
}

// Export categories
function exportCategories() {
    showAlert('Fitur export akan segera tersedia', 'info');
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
</script>
@endsection