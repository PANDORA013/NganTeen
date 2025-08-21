@extends('layouts.penjual')

@section('title', 'Menu Management - Professional')

@section('additional_css')
<link href="{{ asset('css/professional-penjual.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
/* ===========================
   Professional Menu Management Styles
   =========================== */

.menu-professional-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

/* Professional Menu Header */
.menu-professional-header {
    background: var(--gradient-primary);
    color: white;
    padding: var(--spacing-2xl) 0;
    margin: -2rem -2rem var(--spacing-xl) -2rem;
    position: relative;
    overflow: hidden;
}

.menu-professional-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="menuPattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23menuPattern)"/></svg>');
    opacity: 0.3;
}

.menu-header-content {
    position: relative;
    z-index: 2;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.menu-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.menu-subtitle {
    font-size: var(--font-size-lg);
    opacity: 0.9;
    margin-bottom: var(--spacing-lg);
}

.menu-quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.menu-quick-stat {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
    text-align: center;
}

.menu-quick-stat-value {
    font-size: var(--font-size-2xl);
    font-weight: 700;
    margin-bottom: var(--spacing-xs);
}

.menu-quick-stat-label {
    font-size: var(--font-size-sm);
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Professional Controls */
.menu-controls {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow);
    padding: var(--spacing-xl);
    margin-bottom: var(--spacing-xl);
    border: 1px solid #e5e7eb;
}

.menu-controls-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    flex-wrap: wrap;
    gap: var(--spacing-md);
}

.menu-search-container {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.menu-search-input {
    width: 100%;
    padding: 0.875rem 1rem 0.875rem 3rem;
    border: 2px solid #e5e7eb;
    border-radius: var(--border-radius-lg);
    font-size: var(--font-size-sm);
    transition: all 0.3s ease;
    background: #f8fafc;
}

.menu-search-input:focus {
    outline: none;
    border-color: var(--primary);
    background: white;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.menu-search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary);
    font-size: var(--font-size-lg);
}

.menu-filters {
    display: flex;
    gap: var(--spacing-md);
    flex-wrap: wrap;
    align-items: center;
}

.menu-filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: var(--border-radius);
    background: white;
    font-size: var(--font-size-sm);
    min-width: 150px;
    transition: all 0.2s ease;
}

.menu-filter-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Professional Menu Grid */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-xl);
}

.menu-card-professional {
    background: white;
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.menu-card-professional:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.menu-card-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    position: relative;
}

.menu-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.menu-card-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    color: var(--secondary);
    font-size: var(--font-size-lg);
}

.menu-inventory-badge {
    position: absolute;
    top: var(--spacing-md);
    right: var(--spacing-md);
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    backdrop-filter: blur(10px);
}

.menu-inventory-badge.in-stock {
    background: rgba(5, 150, 105, 0.9);
    color: white;
}

.menu-inventory-badge.low-stock {
    background: rgba(217, 119, 6, 0.9);
    color: white;
}

.menu-inventory-badge.out-of-stock {
    background: rgba(220, 38, 38, 0.9);
    color: white;
}

.menu-card-content {
    padding: var(--spacing-xl);
}

.menu-card-title {
    font-size: var(--font-size-xl);
    font-weight: 600;
    color: #1f2937;
    margin-bottom: var(--spacing-sm);
    line-height: 1.4;
}

.menu-card-description {
    color: var(--secondary);
    font-size: var(--font-size-sm);
    line-height: 1.6;
    margin-bottom: var(--spacing-lg);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.menu-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
}

.menu-card-price {
    font-size: var(--font-size-xl);
    font-weight: 700;
    color: var(--primary);
}

.menu-card-stock {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: var(--font-size-sm);
    color: var(--secondary);
}

.menu-card-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.menu-action-btn {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: var(--border-radius);
    font-size: var(--font-size-sm);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
}

.menu-action-btn.edit {
    background: var(--gradient-primary);
    color: white;
}

.menu-action-btn.edit:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

.menu-action-btn.delete {
    background: #fee2e2;
    color: var(--danger);
    border: 1px solid #fca5a5;
}

.menu-action-btn.delete:hover {
    background: var(--danger);
    color: white;
    transform: translateY(-1px);
}

/* Add Menu Button */
.add-menu-floating {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 60px;
    height: 60px;
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: var(--font-size-xl);
    cursor: pointer;
    box-shadow: var(--shadow-xl);
    transition: all 0.3s ease;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-menu-floating:hover {
    transform: scale(1.1);
    box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.5);
}

/* Empty State */
.menu-empty-state {
    text-align: center;
    padding: var(--spacing-2xl);
    color: var(--secondary);
}

.menu-empty-icon {
    font-size: 4rem;
    margin-bottom: var(--spacing-lg);
    opacity: 0.5;
}

.menu-empty-title {
    font-size: var(--font-size-xl);
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: #1f2937;
}

.menu-empty-description {
    font-size: var(--font-size-base);
    margin-bottom: var(--spacing-xl);
}

/* Professional Loading */
.menu-loading {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--spacing-xl);
}

.menu-card-skeleton {
    background: white;
    border-radius: var(--border-radius-xl);
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.menu-skeleton-image {
    width: 100%;
    height: 200px;
    background: var(--professional-skeleton);
}

.menu-skeleton-content {
    padding: var(--spacing-xl);
}

.menu-skeleton-title {
    height: 1.5rem;
    background: var(--professional-skeleton);
    border-radius: var(--border-radius);
    margin-bottom: var(--spacing-sm);
}

.menu-skeleton-description {
    height: 1rem;
    background: var(--professional-skeleton);
    border-radius: var(--border-radius);
    margin-bottom: var(--spacing-sm);
    width: 80%;
}

.menu-skeleton-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-lg);
}

.menu-skeleton-price {
    height: 1.25rem;
    width: 80px;
    background: var(--professional-skeleton);
    border-radius: var(--border-radius);
}

.menu-skeleton-stock {
    height: 1rem;
    width: 60px;
    background: var(--professional-skeleton);
    border-radius: var(--border-radius);
}

.menu-skeleton-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.menu-skeleton-btn {
    flex: 1;
    height: 2.5rem;
    background: var(--professional-skeleton);
    border-radius: var(--border-radius);
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-professional-container {
        padding: 0 var(--spacing-md);
    }
    
    .menu-title {
        font-size: 2rem;
    }
    
    .menu-controls-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .menu-search-container {
        min-width: auto;
        margin-bottom: var(--spacing-md);
    }
    
    .menu-filters {
        justify-content: stretch;
    }
    
    .menu-filter-select {
        flex: 1;
        min-width: auto;
    }
    
    .menu-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .menu-quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .add-menu-floating {
        bottom: 1rem;
        right: 1rem;
        width: 50px;
        height: 50px;
        font-size: var(--font-size-lg);
    }
}

@media (max-width: 480px) {
    .menu-quick-stats {
        grid-template-columns: 1fr;
    }
    
    .menu-card-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="menu-professional-container professional-fade-in">
    <!-- Professional Header -->
    <div class="menu-professional-header">
        <div class="menu-header-content">
            <h1 class="menu-title">
                <i class="fas fa-utensils"></i>
                Menu Management
            </h1>
            <p class="menu-subtitle">
                Kelola menu warung Anda dengan interface profesional yang modern dan intuitif
            </p>
            
            <!-- Quick Stats -->
            <div class="menu-quick-stats">
                <div class="menu-quick-stat">
                    <div class="menu-quick-stat-value">{{ $totalMenus ?? 0 }}</div>
                    <div class="menu-quick-stat-label">Total Menu</div>
                </div>
                <div class="menu-quick-stat">
                    <div class="menu-quick-stat-value">{{ $inStockMenus ?? 0 }}</div>
                    <div class="menu-quick-stat-label">Tersedia</div>
                </div>
                <div class="menu-quick-stat">
                    <div class="menu-quick-stat-value">{{ $lowStockMenus ?? 0 }}</div>
                    <div class="menu-quick-stat-label">Stok Rendah</div>
                </div>
                <div class="menu-quick-stat">
                    <div class="menu-quick-stat-value">{{ $outOfStockMenus ?? 0 }}</div>
                    <div class="menu-quick-stat-label">Habis</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Controls -->
    <div class="menu-controls professional-slide-up">
        <div class="menu-controls-header">
            <div class="menu-search-container">
                <i class="fas fa-search menu-search-icon"></i>
                <input type="text" class="menu-search-input" placeholder="Cari menu berdasarkan nama, kategori, atau deskripsi..." id="menuSearch">
            </div>
            <div class="menu-filters">
                <select class="menu-filter-select" id="categoryFilter">
                    <option value="">Semua Kategori</option>
                    <option value="makanan">Makanan</option>
                    <option value="minuman">Minuman</option>
                    <option value="snack">Snack</option>
                    <option value="dessert">Dessert</option>
                </select>
                <select class="menu-filter-select" id="stockFilter">
                    <option value="">Semua Stok</option>
                    <option value="in-stock">Tersedia</option>
                    <option value="low-stock">Stok Rendah</option>
                    <option value="out-of-stock">Habis</option>
                </select>
                <select class="menu-filter-select" id="priceFilter">
                    <option value="">Semua Harga</option>
                    <option value="0-10000">Di bawah Rp 10.000</option>
                    <option value="10000-25000">Rp 10.000 - 25.000</option>
                    <option value="25000-50000">Rp 25.000 - 50.000</option>
                    <option value="50000+">Di atas Rp 50.000</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="menu-grid" id="menuGrid">
        @if(isset($menus) && $menus->count() > 0)
            @foreach($menus as $menu)
            <div class="menu-card-professional professional-slide-up" data-menu-id="{{ $menu->id }}" data-category="{{ $menu->kategori }}" data-price="{{ $menu->harga }}" data-stock="{{ $menu->stok }}">
                <!-- Menu Image -->
                <div class="menu-card-image">
                    @if($menu->gambar && file_exists(public_path('storage/' . $menu->gambar)))
                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" loading="lazy">
                    @else
                        <div class="menu-card-image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                    
                    <!-- Inventory Badge -->
                    @if($menu->stok <= 0)
                        <div class="menu-inventory-badge out-of-stock">
                            <i class="fas fa-times-circle"></i> Habis
                        </div>
                    @elseif($menu->stok <= 5)
                        <div class="menu-inventory-badge low-stock">
                            <i class="fas fa-exclamation-triangle"></i> Stok Rendah
                        </div>
                    @else
                        <div class="menu-inventory-badge in-stock">
                            <i class="fas fa-check-circle"></i> Tersedia
                        </div>
                    @endif
                </div>

                <!-- Menu Content -->
                <div class="menu-card-content">
                    <h3 class="menu-card-title">{{ $menu->nama_menu }}</h3>
                    <p class="menu-card-description">{{ $menu->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                    
                    <!-- Meta Information -->
                    <div class="menu-card-meta">
                        <div class="menu-card-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                        <div class="menu-card-stock">
                            <i class="fas fa-boxes"></i>
                            Stok: {{ $menu->stok }}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="menu-card-actions">
                        <button class="menu-action-btn edit" onclick="editMenu({{ $menu->id }})">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="menu-action-btn delete" onclick="deleteMenu({{ $menu->id }}, '{{ $menu->nama_menu }}')">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="menu-empty-state" style="grid-column: 1 / -1;">
                <div class="menu-empty-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 class="menu-empty-title">Belum Ada Menu</h3>
                <p class="menu-empty-description">
                    Mulai dengan menambahkan menu pertama warung Anda. Klik tombol tambah di pojok kanan bawah untuk memulai.
                </p>
                <button class="btn-professional-primary" onclick="addMenu()">
                    <i class="fas fa-plus"></i>
                    Tambah Menu Pertama
                </button>
            </div>
        @endif
    </div>

    <!-- Floating Add Button -->
    <button class="add-menu-floating" onclick="addMenu()" title="Tambah Menu Baru">
        <i class="fas fa-plus"></i>
    </button>
</div>

<!-- Loading State Template -->
<template id="loadingTemplate">
    <div class="menu-loading">
        @for($i = 0; $i < 6; $i++)
        <div class="menu-card-skeleton">
            <div class="menu-skeleton-image"></div>
            <div class="menu-skeleton-content">
                <div class="menu-skeleton-title"></div>
                <div class="menu-skeleton-description"></div>
                <div class="menu-skeleton-meta">
                    <div class="menu-skeleton-price"></div>
                    <div class="menu-skeleton-stock"></div>
                </div>
                <div class="menu-skeleton-actions">
                    <div class="menu-skeleton-btn"></div>
                    <div class="menu-skeleton-btn"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</template>
@endsection

@section('additional_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize professional menu management
    initializeMenuManagement();
});

function initializeMenuManagement() {
    const searchInput = document.getElementById('menuSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const stockFilter = document.getElementById('stockFilter');
    const priceFilter = document.getElementById('priceFilter');
    
    // Real-time search and filtering
    const debounce = (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };
    
    const debouncedFilter = debounce(filterMenus, 300);
    
    if (searchInput) searchInput.addEventListener('input', debouncedFilter);
    if (categoryFilter) categoryFilter.addEventListener('change', filterMenus);
    if (stockFilter) stockFilter.addEventListener('change', filterMenus);
    if (priceFilter) priceFilter.addEventListener('change', filterMenus);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'f':
                    e.preventDefault();
                    searchInput?.focus();
                    break;
                case 'n':
                    e.preventDefault();
                    addMenu();
                    break;
            }
        }
    });
}

function filterMenus() {
    const searchTerm = document.getElementById('menuSearch')?.value.toLowerCase() || '';
    const categoryFilter = document.getElementById('categoryFilter')?.value || '';
    const stockFilter = document.getElementById('stockFilter')?.value || '';
    const priceFilter = document.getElementById('priceFilter')?.value || '';
    
    const menuCards = document.querySelectorAll('.menu-card-professional');
    let visibleCount = 0;
    
    menuCards.forEach(card => {
        const title = card.querySelector('.menu-card-title')?.textContent.toLowerCase() || '';
        const description = card.querySelector('.menu-card-description')?.textContent.toLowerCase() || '';
        const category = card.dataset.category || '';
        const price = parseInt(card.dataset.price) || 0;
        const stock = parseInt(card.dataset.stock) || 0;
        
        let showCard = true;
        
        // Search filter
        if (searchTerm && !title.includes(searchTerm) && !description.includes(searchTerm)) {
            showCard = false;
        }
        
        // Category filter
        if (categoryFilter && category !== categoryFilter) {
            showCard = false;
        }
        
        // Stock filter
        if (stockFilter) {
            switch(stockFilter) {
                case 'in-stock':
                    if (stock <= 5) showCard = false;
                    break;
                case 'low-stock':
                    if (stock > 5 || stock <= 0) showCard = false;
                    break;
                case 'out-of-stock':
                    if (stock > 0) showCard = false;
                    break;
            }
        }
        
        // Price filter
        if (priceFilter) {
            const [min, max] = priceFilter.split('-').map(p => parseInt(p.replace('+', '')) || Infinity);
            if (priceFilter.includes('+')) {
                if (price < min) showCard = false;
            } else {
                if (price < min || price > max) showCard = false;
            }
        }
        
        card.style.display = showCard ? 'block' : 'none';
        if (showCard) visibleCount++;
    });
    
    // Show/hide empty state
    const emptyState = document.querySelector('.menu-empty-state');
    if (emptyState) {
        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

function addMenu() {
    // Navigate to add menu page or open modal
    window.location.href = '{{ route("penjual.menu.create") }}';
}

function editMenu(menuId) {
    // Navigate to edit menu page
    window.location.href = `{{ url('penjual/menu') }}/${menuId}/edit`;
}

function deleteMenu(menuId, menuName) {
    if (confirm(`Apakah Anda yakin ingin menghapus menu "${menuName}"? Tindakan ini tidak dapat dibatalkan.`)) {
        // Show loading state
        const card = document.querySelector(`[data-menu-id="${menuId}"]`);
        if (card) {
            card.style.opacity = '0.5';
            card.style.pointerEvents = 'none';
        }
        
        // Submit delete request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('penjual/menu') }}/${menuId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-refresh functionality
setInterval(() => {
    // Could implement auto-refresh of stock status
    updateStockBadges();
}, 60000); // Every minute

function updateStockBadges() {
    // This could fetch updated stock data via AJAX
    console.log('Updating stock badges...');
}
</script>
@endsection
