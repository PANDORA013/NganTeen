@extends('layouts.penjual')

@section('title', 'Tambah Menu - Professional')

@section('additional_css')
<link href="{{ asset('css/professional-penjual.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
/* ===========================
   Professional Menu Form Styles
   =========================== */

.menu-form-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.menu-form-header {
    background: var(--gradient-primary);
    color: white;
    padding: var(--spacing-2xl) 0;
    margin: -2rem -2rem var(--spacing-xl) -2rem;
    position: relative;
    overflow: hidden;
}

.menu-form-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><defs><pattern id="formPattern" width="10" height="10" patternUnits="userSpaceOnUse"><circle cx="5" cy="5" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="50" height="50" fill="url(%23formPattern)"/></svg>');
}

.menu-form-header-content {
    position: relative;
    z-index: 2;
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.menu-form-title {
    font-size: 2.25rem;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.menu-form-subtitle {
    font-size: var(--font-size-lg);
    opacity: 0.9;
}

.menu-form-card {
    background: white;
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.menu-form-content {
    padding: var(--spacing-2xl);
}

.menu-form-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: var(--spacing-2xl);
}

.menu-form-main {
    min-width: 0; /* Prevent grid overflow */
}

.menu-form-sidebar {
    background: #f8fafc;
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-xl);
    border: 1px solid #e5e7eb;
    height: fit-content;
    position: sticky;
    top: var(--spacing-xl);
}

/* Professional Form Groups */
.professional-form-group {
    margin-bottom: var(--spacing-xl);
}

.professional-form-group.required .professional-label::after {
    content: ' *';
    color: var(--danger);
}

.professional-label {
    display: block;
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: #374151;
    margin-bottom: var(--spacing-sm);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.professional-input,
.professional-textarea,
.professional-select {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-base);
    transition: all 0.3s ease;
    background: white;
    font-family: var(--font-family);
}

.professional-input:focus,
.professional-textarea:focus,
.professional-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    background: #fefefe;
}

.professional-textarea {
    min-height: 120px;
    resize: vertical;
}

.professional-input-group {
    position: relative;
}

.professional-input-group .professional-input {
    padding-left: 3.5rem;
}

.professional-input-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary);
    font-size: var(--font-size-lg);
}

/* Image Upload Component */
.image-upload-container {
    border: 2px dashed #d1d5db;
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-2xl);
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.image-upload-container:hover {
    border-color: var(--primary);
    background: #f0f9ff;
}

.image-upload-container.has-image {
    padding: 0;
    border: none;
    background: transparent;
}

.image-upload-input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    top: 0;
    left: 0;
}

.image-upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
    color: var(--secondary);
}

.image-upload-icon {
    font-size: 3rem;
    color: var(--primary);
    opacity: 0.7;
}

.image-upload-text {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: #374151;
}

.image-upload-subtext {
    font-size: var(--font-size-sm);
    color: var(--secondary);
}

.image-preview-container {
    position: relative;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    background: #f3f4f6;
    aspect-ratio: 16/9;
}

.image-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-remove-btn {
    position: absolute;
    top: var(--spacing-md);
    right: var(--spacing-md);
    width: 2.5rem;
    height: 2.5rem;
    background: rgba(220, 38, 38, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-sm);
    transition: all 0.2s ease;
    backdrop-filter: blur(4px);
}

.image-remove-btn:hover {
    background: var(--danger);
    transform: scale(1.1);
}

/* Sidebar Content */
.sidebar-section {
    margin-bottom: var(--spacing-xl);
}

.sidebar-title {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: #1f2937;
    margin-bottom: var(--spacing-md);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm) 0;
    color: var(--secondary);
    font-size: var(--font-size-sm);
}

.sidebar-icon {
    width: 1rem;
    color: var(--primary);
}

/* Quick Actions */
.quick-actions {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-xl);
    border: 1px solid #e5e7eb;
    margin-bottom: var(--spacing-lg);
}

.quick-actions-title {
    font-size: var(--font-size-lg);
    font-weight: 600;
    margin-bottom: var(--spacing-lg);
    color: #1f2937;
}

.quick-action-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-md);
}

.quick-action-btn {
    padding: var(--spacing-md);
    border: 2px solid #e5e7eb;
    border-radius: var(--border-radius);
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    color: var(--secondary);
    font-size: var(--font-size-sm);
}

.quick-action-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: #f0f9ff;
}

.quick-action-btn.active {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
}

/* Form Actions */
.form-actions {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: var(--spacing-xl) var(--spacing-2xl);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-md);
}

.form-actions-left {
    display: flex;
    gap: var(--spacing-md);
}

.form-actions-right {
    display: flex;
    gap: var(--spacing-md);
}

.btn-professional-large {
    padding: 1rem 2rem;
    font-size: var(--font-size-base);
    font-weight: 600;
    border-radius: var(--border-radius-lg);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    min-width: 150px;
    justify-content: center;
}

.btn-cancel {
    background: white;
    color: var(--secondary);
    border: 2px solid #d1d5db;
}

.btn-cancel:hover {
    background: #f3f4f6;
    border-color: var(--secondary);
    color: #374151;
}

.btn-save-draft {
    background: #f59e0b;
    color: white;
    border: 2px solid #f59e0b;
}

.btn-save-draft:hover {
    background: #d97706;
    border-color: #d97706;
    transform: translateY(-1px);
}

.btn-publish {
    background: var(--gradient-primary);
    color: white;
    border: 2px solid var(--primary);
}

.btn-publish:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-form-container {
        padding: 0 var(--spacing-md);
    }
    
    .menu-form-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .menu-form-sidebar {
        order: -1;
        position: static;
    }
    
    .menu-form-content {
        padding: var(--spacing-xl);
    }
    
    .form-actions {
        flex-direction: column;
        gap: var(--spacing-lg);
        align-items: stretch;
    }
    
    .form-actions-left,
    .form-actions-right {
        justify-content: stretch;
    }
    
    .btn-professional-large {
        min-width: auto;
    }
    
    .quick-action-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="menu-form-container professional-fade-in">
    <!-- Professional Header -->
    <div class="menu-form-header">
        <div class="menu-form-header-content">
            <h1 class="menu-form-title">
                <i class="fas fa-plus-circle"></i>
                {{ isset($menu) ? 'Edit Menu' : 'Tambah Menu Baru' }}
            </h1>
            <p class="menu-form-subtitle">
                {{ isset($menu) ? 'Perbarui informasi menu warung Anda' : 'Buat menu baru untuk warung Anda dengan mudah' }}
            </p>
        </div>
    </div>

    <!-- Professional Form -->
    <div class="menu-form-card professional-slide-up">
        <form action="{{ isset($menu) ? route('penjual.menu.update', $menu->id) : route('penjual.menu.store') }}" method="POST" enctype="multipart/form-data" id="menuForm">
            @csrf
            @if(isset($menu))
                @method('PUT')
            @endif
            
            <div class="menu-form-content">
                <div class="menu-form-grid">
                    <!-- Main Form -->
                    <div class="menu-form-main">
                        <!-- Menu Name -->
                        <div class="professional-form-group required">
                            <label class="professional-label" for="nama">Nama Menu</label>
                            <div class="professional-input-group">
                                <i class="professional-input-icon fas fa-utensils"></i>
                                <input type="text" class="professional-input" id="nama" name="nama" 
                                       value="{{ old('nama_menu', $menu->nama_menu ?? '') }}" 
                                       placeholder="Masukkan nama menu yang menarik"
                                       required maxlength="100">
                            </div>
                            @error('nama')
                                <div class="professional-alert professional-alert-danger mt-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="professional-form-group">
                            <label class="professional-label" for="deskripsi">Deskripsi Menu</label>
                            <textarea class="professional-textarea" id="deskripsi" name="deskripsi" 
                                      placeholder="Jelaskan menu Anda dengan detail yang menarik...">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <div class="professional-alert professional-alert-danger mt-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Price and Stock Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div class="professional-form-group required">
                                <label class="professional-label" for="harga">Harga</label>
                                <div class="professional-input-group">
                                    <i class="professional-input-icon fas fa-money-bill-wave"></i>
                                    <input type="number" class="professional-input" id="harga" name="harga" 
                                           value="{{ old('harga', $menu->harga ?? '') }}" 
                                           placeholder="0" min="0" step="500" required>
                                </div>
                                @error('harga')
                                    <div class="professional-alert professional-alert-danger mt-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div class="professional-form-group required">
                                <label class="professional-label" for="stok">Stok</label>
                                <div class="professional-input-group">
                                    <i class="professional-input-icon fas fa-boxes"></i>
                                    <input type="number" class="professional-input" id="stok" name="stok" 
                                           value="{{ old('stok', $menu->stok ?? '') }}" 
                                           placeholder="0" min="0" required>
                                </div>
                                @error('stok')
                                    <div class="professional-alert professional-alert-danger mt-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="professional-form-group required">
                            <label class="professional-label" for="kategori">Kategori</label>
                            <select class="professional-select" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="makanan" {{ old('kategori', $menu->kategori ?? '') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                                <option value="minuman" {{ old('kategori', $menu->kategori ?? '') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                                <option value="snack" {{ old('kategori', $menu->kategori ?? '') == 'snack' ? 'selected' : '' }}>Snack</option>
                                <option value="dessert" {{ old('kategori', $menu->kategori ?? '') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                            </select>
                            @error('kategori')
                                <div class="professional-alert professional-alert-danger mt-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="professional-form-group">
                            <label class="professional-label" for="gambar">Gambar Menu</label>
                            <div class="image-upload-container" id="imageUploadContainer">
                                <input type="file" class="image-upload-input" id="gambar" name="gambar" accept="image/*">
                                
                                @if(isset($menu) && $menu->gambar && file_exists(public_path('storage/' . $menu->gambar)))
                                    <div class="image-preview-container">
                                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Preview" class="image-preview" id="imagePreview">
                                        <button type="button" class="image-remove-btn" id="removeImageBtn">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="image-upload-placeholder" id="uploadPlaceholder">
                                        <i class="image-upload-icon fas fa-cloud-upload-alt"></i>
                                        <div class="image-upload-text">Upload Gambar Menu</div>
                                        <div class="image-upload-subtext">
                                            Klik atau seret file gambar ke sini<br>
                                            <small>JPG, PNG, atau WEBP (Max: 2MB)</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @error('gambar')
                                <div class="professional-alert professional-alert-danger mt-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="menu-form-sidebar">
                        <!-- Menu Tips -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-title">
                                <i class="fas fa-lightbulb"></i>
                                Tips Menu Menarik
                            </h3>
                            <div class="sidebar-item">
                                <i class="sidebar-icon fas fa-check"></i>
                                Gunakan nama yang menggugah selera
                            </div>
                            <div class="sidebar-item">
                                <i class="sidebar-icon fas fa-check"></i>
                                Foto dengan pencahayaan yang baik
                            </div>
                            <div class="sidebar-item">
                                <i class="sidebar-icon fas fa-check"></i>
                                Deskripsi yang detail dan jujur
                            </div>
                            <div class="sidebar-item">
                                <i class="sidebar-icon fas fa-check"></i>
                                Harga yang kompetitif
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-title">
                                <i class="fas fa-bolt"></i>
                                Template Cepat
                            </h3>
                            <div class="quick-action-grid">
                                <button type="button" class="quick-action-btn" onclick="applyTemplate('nasi')">
                                    <i class="fas fa-rice"></i><br>Nasi
                                </button>
                                <button type="button" class="quick-action-btn" onclick="applyTemplate('mie')">
                                    <i class="fas fa-bowl-food"></i><br>Mie
                                </button>
                                <button type="button" class="quick-action-btn" onclick="applyTemplate('minuman')">
                                    <i class="fas fa-glass-water"></i><br>Minuman
                                </button>
                                <button type="button" class="quick-action-btn" onclick="applyTemplate('snack')">
                                    <i class="fas fa-cookie-bite"></i><br>Snack
                                </button>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-title">
                                <i class="fas fa-eye"></i>
                                Preview Live
                            </h3>
                            <div class="menu-preview-card" style="background: white; border: 1px solid #e5e7eb; border-radius: var(--border-radius); padding: var(--spacing-md); font-size: var(--font-size-sm);">
                                <div class="preview-name" id="previewName">Nama Menu</div>
                                <div class="preview-price" id="previewPrice" style="color: var(--primary); font-weight: 600; margin: var(--spacing-xs) 0;">Rp 0</div>
                                <div class="preview-category" id="previewCategory" style="color: var(--secondary);">Kategori</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="form-actions-left">
                    <a href="{{ route('penjual.menu.index') }}" class="btn-professional-large btn-cancel">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
                <div class="form-actions-right">
                    <button type="button" class="btn-professional-large btn-save-draft" onclick="saveDraft()">
                        <i class="fas fa-save"></i>
                        Simpan Draft
                    </button>
                    <button type="submit" class="btn-professional-large btn-publish">
                        <i class="fas fa-check-circle"></i>
                        {{ isset($menu) ? 'Update Menu' : 'Tambah Menu' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('additional_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeMenuForm();
});

function initializeMenuForm() {
    const imageInput = document.getElementById('gambar');
    const imageContainer = document.getElementById('imageUploadContainer');
    const removeBtn = document.getElementById('removeImageBtn');
    
    // Image upload handling
    if (imageInput) {
        imageInput.addEventListener('change', handleImageUpload);
    }
    
    if (removeBtn) {
        removeBtn.addEventListener('click', removeImage);
    }
    
    // Drag and drop
    imageContainer.addEventListener('dragover', handleDragOver);
    imageContainer.addEventListener('drop', handleDrop);
    
    // Live preview
    document.getElementById('nama')?.addEventListener('input', updatePreview);
    document.getElementById('harga')?.addEventListener('input', updatePreview);
    document.getElementById('kategori')?.addEventListener('change', updatePreview);
    
    // Auto-save draft
    setInterval(autoSaveDraft, 30000); // Every 30 seconds
}

function handleImageUpload(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            showImagePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function showImagePreview(src) {
    const container = document.getElementById('imageUploadContainer');
    container.classList.add('has-image');
    container.innerHTML = `
        <div class="image-preview-container">
            <img src="${src}" alt="Preview" class="image-preview" id="imagePreview">
            <button type="button" class="image-remove-btn" id="removeImageBtn" onclick="removeImage()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
}

function removeImage() {
    const container = document.getElementById('imageUploadContainer');
    const imageInput = document.getElementById('gambar');
    
    container.classList.remove('has-image');
    container.innerHTML = `
        <input type="file" class="image-upload-input" id="gambar" name="gambar" accept="image/*">
        <div class="image-upload-placeholder" id="uploadPlaceholder">
            <i class="image-upload-icon fas fa-cloud-upload-alt"></i>
            <div class="image-upload-text">Upload Gambar Menu</div>
            <div class="image-upload-subtext">
                Klik atau seret file gambar ke sini<br>
                <small>JPG, PNG, atau WEBP (Max: 2MB)</small>
            </div>
        </div>
    `;
    
    // Re-attach event listener
    document.getElementById('gambar').addEventListener('change', handleImageUpload);
}

function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.style.borderColor = 'var(--primary)';
}

function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.style.borderColor = '#d1d5db';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const imageInput = document.getElementById('gambar');
        imageInput.files = files;
        handleImageUpload({ target: { files: files } });
    }
}

function applyTemplate(type) {
    const templates = {
        nasi: {
            kategori: 'makanan',
            deskripsi: 'Nasi hangat dengan lauk pilihan yang lezat dan bergizi'
        },
        mie: {
            kategori: 'makanan', 
            deskripsi: 'Mie yang dimasak dengan bumbu spesial dan topping melimpah'
        },
        minuman: {
            kategori: 'minuman',
            deskripsi: 'Minuman segar yang menyegarkan dan nikmat'
        },
        snack: {
            kategori: 'snack',
            deskripsi: 'Camilan renyah dan gurih yang cocok untuk segala suasana'
        }
    };
    
    const template = templates[type];
    if (template) {
        document.getElementById('kategori').value = template.kategori;
        if (!document.getElementById('deskripsi').value) {
            document.getElementById('deskripsi').value = template.deskripsi;
        }
        updatePreview();
    }
}

function updatePreview() {
    const nama = document.getElementById('nama')?.value || 'Nama Menu';
    const harga = document.getElementById('harga')?.value || 0;
    const kategori = document.getElementById('kategori')?.value || 'Kategori';
    
    document.getElementById('previewName').textContent = nama;
    document.getElementById('previewPrice').textContent = `Rp ${parseInt(harga).toLocaleString('id-ID')}`;
    document.getElementById('previewCategory').textContent = kategori.charAt(0).toUpperCase() + kategori.slice(1);
}

function saveDraft() {
    const formData = new FormData(document.getElementById('menuForm'));
    formData.append('draft', '1');
    
    // Save to localStorage for now
    const draftData = {
        nama: formData.get('nama'),
        deskripsi: formData.get('deskripsi'),
        harga: formData.get('harga'),
        stok: formData.get('stok'),
        kategori: formData.get('kategori'),
        timestamp: new Date().toISOString()
    };
    
    localStorage.setItem('menuDraft', JSON.stringify(draftData));
    
    // Show success message
    const alert = document.createElement('div');
    alert.className = 'professional-alert professional-alert-success';
    alert.innerHTML = '<i class="fas fa-check-circle"></i> Draft berhasil disimpan!';
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

function autoSaveDraft() {
    const nama = document.getElementById('nama')?.value;
    if (nama && nama.length > 3) {
        saveDraft();
    }
}

// Load draft on page load
window.addEventListener('load', function() {
    const draft = localStorage.getItem('menuDraft');
    if (draft && !document.getElementById('nama').value) {
        const draftData = JSON.parse(draft);
        const now = new Date();
        const draftTime = new Date(draftData.timestamp);
        const hoursDiff = (now - draftTime) / (1000 * 60 * 60);
        
        if (hoursDiff < 24) { // Draft valid for 24 hours
            if (confirm('Ditemukan draft yang belum selesai. Muat draft tersebut?')) {
                document.getElementById('nama').value = draftData.nama || '';
                document.getElementById('deskripsi').value = draftData.deskripsi || '';
                document.getElementById('harga').value = draftData.harga || '';
                document.getElementById('stok').value = draftData.stok || '';
                document.getElementById('kategori').value = draftData.kategori || '';
                updatePreview();
            }
        }
    }
});
</script>
@endsection
