@extends('layouts.pembeli')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pembeli.menu.index') }}">Menu</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $menu->nama_menu }}</li>
                </ol>
            </nav>

            <!-- Menu Card -->
            <div class="card shadow-sm border-0 overflow-hidden mb-5">
                <div class="position-relative">
                    @if($menu->image)
                        <img src="{{ asset('storage/'.$menu->image) }}" class="card-img-top object-fit-cover" style="height: 400px" alt="{{ $menu->nama_menu }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px">
                            <i class="fas fa-utensils fa-4x text-muted"></i>
                        </div>
                    @endif
                    
                    <!-- Location Badge -->
                    <span class="badge bg-primary position-absolute top-0 end-0 m-3 fs-6">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $menu->area_kampus }}
                    </span>
                    
                    <!-- Favorite Button -->
                    @auth
                        <button class="btn btn-light position-absolute top-0 start-0 m-3 p-3 rounded-circle shadow-sm" 
                                onclick="toggleFavorite({{ $menu->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="right" 
                                title="{{ $menu->isFavoritedBy(auth()->user()) ? 'Hapus dari favorit' : 'Tambahkan ke favorit' }}"
                                id="favoriteBtn">
                            <i class="fas fa-heart {{ $menu->isFavoritedBy(auth()->user()) ? 'text-danger' : 'text-muted' }} fa-lg"></i>
                        </button>
                    @endauth
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    <!-- Menu Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h2 fw-bold mb-2">{{ $menu->nama_menu }}</h1>
                            <h3 class="h5 text-muted">{{ $menu->nama_warung }}</h3>
                        </div>
                        <div class="text-end">
                            <div class="h3 fw-bold text-primary mb-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                            <span class="badge bg-{{ $menu->stok > 0 ? 'success' : 'danger' }} px-3 py-2">
                                <i class="fas fa-{{ $menu->stok > 0 ? 'check' : 'times' }}-circle me-1"></i>
                                {{ $menu->stok > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Rating -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="rating-stars me-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($menu->averageRating()) ? 'text-warning' : 'text-light' }}"></i>
                            @endfor
                        </div>
                        <small class="text-muted">({{ $menu->ratings->count() }} ulasan)</small>
                        <span class="mx-2 text-muted">•</span>
                        <span class="text-muted"><i class="fas fa-clock me-1"></i> {{ $menu->estimated_time }} menit</span>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Deskripsi Menu</h5>
                        <p class="mb-0">{{ $menu->description ?? 'Tidak ada deskripsi tersedia' }}</p>
                    </div>
                    
                    <!-- Order Form -->
                    @auth
                        @if(auth()->user()->role === 'pembeli' && $menu->stok > 0)
                            <form action="{{ route('pembeli.cart.store') }}" method="POST" class="mb-4">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-4">
                                        <label for="jumlah" class="form-label fw-bold">Jumlah Pesanan</label>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="jumlah" id="jumlah" 
                                                class="form-control text-center" min="1" max="{{ $menu->stok }}" value="1">
                                            <button class="btn btn-outline-secondary" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-8 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3" id="addToCartBtn">
                                            <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @elseif($menu->stok === 0)
                            <div class="alert alert-warning text-center py-3 mb-4">
                                <i class="fas fa-exclamation-circle me-2"></i>Stok menu ini sedang habis
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info text-center py-3 mb-4">
                            <i class="fas fa-info-circle me-2"></i>Silakan <a href="{{ route('login') }}" class="alert-link">login</a> untuk memesan menu ini
                        </div>
                    @endauth
                    
                    <!-- Recommended Menus -->
                    @if($menu->recommendedMenus->isNotEmpty())
                        <div class="mt-5 pt-4 border-top">
                            <h4 class="h4 fw-bold mb-4"><i class="fas fa-utensils text-primary me-2"></i>Menu Rekomendasi</h4>
                            <div class="row g-3">
                                @foreach($menu->recommendedMenus as $recommended)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <a href="{{ route('pembeli.menu.show', $recommended->id) }}" class="text-decoration-none">
                                                @if($recommended->image)
                                                    <img src="{{ asset('storage/'.$recommended->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $recommended->nama_menu }}">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px">
                                                        <i class="fas fa-utensils fa-3x text-muted"></i>
                                                    </div>
                                                @endif
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold mb-1">{{ $recommended->nama_menu }}</h5>
                                                <p class="text-muted small mb-2">{{ $recommended->nama_warung }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-primary">Rp {{ number_format($recommended->harga, 0, ',', '.') }}</span>
                                                    <span class="badge bg-{{ $recommended->stok > 0 ? 'success' : 'danger' }} px-2">
                                                        {{ $recommended->stok > 0 ? 'Tersedia' : 'Habis' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Reviews Section -->
                    <!-- Rating & Reviews Section -->
                    <div class="mt-5 pt-4 border-top">
                        <h4 class="h4 fw-bold mb-4">
                            <i class="fas fa-comments text-primary me-2"></i>Rating & Ulasan
                        </h4>
                        
                        <!-- Interactive Rating Component -->
                        @php
                            $userRating = null;
                            if(auth()->check()) {
                                $userRating = $menu->ratings()->where('user_id', auth()->id())->first();
                            }
                        @endphp
                        
                        <x-rating-system 
                            :menu="$menu" 
                            :user-rating="$userRating" 
                            :readonly="false" 
                        />
                        
                        <!-- Reviews List -->
                        <div class="reviews-list mt-4">
                            <h5 class="h5 mb-3">Semua Ulasan</h5>
                            @forelse($menu->ratings()->with('user')->orderBy('created_at', 'desc')->get() as $rating)
                                <div class="card mb-3 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($rating->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $rating->user->name }}</h6>
                                                    <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-light' }}"></i>
                                                @endfor
                                                <span class="ms-1 text-muted small">{{ $rating->rating }}/5</span>
                                            </div>
                                        </div>
                                        
                                        @if($rating->review)
                                            <p class="card-text mb-0">{{ $rating->review }}</p>
                                        @else
                                            <p class="card-text text-muted fst-italic mb-0">Tidak ada komentar</p>
                                        @endif
                                        
                                        @if(auth()->check() && auth()->id() === $rating->user_id)
                                            <div class="mt-2">
                                                <small class="text-success">
                                                    <i class="fas fa-check me-1"></i>Ulasan Anda
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum Ada Ulasan</h5>
                                    <p class="text-muted">Jadilah yang pertama memberikan ulasan untuk menu ini!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Back Button -->
            <div class="mt-4 text-center">
                <a href="{{ route('pembeli.menu.index') }}" class="btn btn-outline-primary px-4">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Menu
                </a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function toggleFavorite(menuId) {
    const btn = document.getElementById('favoriteBtn');
    const icon = btn.querySelector('i');
    
    btn.disabled = true;
    
    fetch(`/menu/${menuId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            if(data.is_favorited) {
                icon.classList.remove('text-muted');
                icon.classList.add('text-danger');
                btn.setAttribute('title', 'Hapus dari favorit');
            } else {
                icon.classList.remove('text-danger');
                icon.classList.add('text-muted');
                btn.setAttribute('title', 'Tambahkan ke favorit');
            }
            
            // Update tooltip
            const tooltip = bootstrap.Tooltip.getInstance(btn);
            if (tooltip) {
                tooltip.dispose();
                new bootstrap.Tooltip(btn);
            }
        }
    })
    .finally(() => {
        btn.disabled = false;
    });
}

// Form submission handling
const reviewForm = document.getElementById('reviewForm');
if(reviewForm) {
    reviewForm.addEventListener('submit', function(e) {
        const btn = document.getElementById('submitReviewBtn');
        const spinner = btn.querySelector('.spinner-border');
        const text = btn.querySelector('.submit-text');
        
        btn.disabled = true;
        spinner.classList.remove('d-none');
        text.textContent = 'Mengirim...';
    });
}

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Star rating hover effect
const starLabels = document.querySelectorAll('.star-label');
if(starLabels) {
    starLabels.forEach(label => {
        label.addEventListener('mouseover', function() {
            const rating = this.getAttribute('for').replace('star', '');
            highlightStars(rating);
        });
        
        label.addEventListener('mouseout', function() {
            const checked = document.querySelector('input[name="rating"]:checked');
            highlightStars(checked ? checked.value : 0);
        });
    });
    
    document.querySelectorAll('input[name="rating"]').forEach(input => {
        input.addEventListener('change', function() {
            highlightStars(this.value);
        });
    });
}

function highlightStars(rating) {
    const stars = document.querySelectorAll('.star-label i');
    stars.forEach((star, index) => {
        if(index < rating) {
            star.classList.add('text-warning');
            star.classList.remove('text-light');
        } else {
            star.classList.remove('text-warning');
            star.classList.add('text-light');
        }
    });
}
</script>
@endsection
@endsection