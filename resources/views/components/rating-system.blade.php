@props(['menu', 'userRating' => null, 'readonly' => false])

@php
$avgRating = $menu->averageRating();
$totalRatings = $menu->ratings->count();
$currentUserRating = $userRating ? $userRating->rating : 0;
@endphp

<div class="rating-component">
    <!-- Display Rating -->
    <div class="rating-display d-flex align-items-center mb-2">
        <div class="stars-display">
            @for($i = 1; $i <= 5; $i++)
                <i class="fa{{ $i <= floor($avgRating) ? 's' : 'r' }} fa-star text-warning me-1"></i>
            @endfor
        </div>
        <span class="rating-text ms-2">
            <strong>{{ number_format($avgRating, 1) }}</strong>
            <small class="text-muted">({{ $totalRatings }} ulasan)</small>
        </span>
    </div>

    @auth
        @if(!$readonly && auth()->user()->isPembeli())
            <!-- Interactive Rating Form -->
            <div class="rating-form">
                <p class="small mb-2">
                    @if($currentUserRating > 0)
                        <i class="fas fa-edit me-1"></i>Edit rating Anda:
                    @else
                        <i class="fas fa-star me-1"></i>Beri rating:
                    @endif
                </p>
                
                <form class="rating-form-stars" data-menu-id="{{ $menu->id }}">
                    @csrf
                    <div class="interactive-stars d-flex align-items-center mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    class="star-btn btn-rating" 
                                    data-rating="{{ $i }}"
                                    data-bs-toggle="tooltip"
                                    title="{{ $i }} bintang">
                                <i class="fa{{ $i <= $currentUserRating ? 's' : 'r' }} fa-star"></i>
                            </button>
                        @endfor
                        <span class="rating-value ms-2 small text-muted" id="rating-value-{{ $menu->id }}">
                            @if($currentUserRating > 0)
                                {{ $currentUserRating }}/5
                            @else
                                Pilih rating
                            @endif
                        </span>
                    </div>
                    
                    <!-- Review Text -->
                    <div class="review-section">
                        <textarea name="review" 
                                class="form-control form-control-sm" 
                                placeholder="Tulis ulasan Anda (opsional)..." 
                                rows="2"
                                maxlength="500">{{ $userRating ? $userRating->review : '' }}</textarea>
                        <div class="form-text">Maksimal 500 karakter</div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="rating-actions mt-2">
                        <button type="submit" class="btn btn-primary btn-sm me-2" disabled>
                            <i class="fas fa-paper-plane me-1"></i>
                            {{ $currentUserRating > 0 ? 'Update Rating' : 'Kirim Rating' }}
                        </button>
                        
                        @if($currentUserRating > 0)
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteRating({{ $menu->id }})">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        @endif
                    </div>
                    
                    <input type="hidden" name="rating" value="{{ $currentUserRating }}">
                </form>
            </div>
        @endif
    @else
        <div class="rating-guest">
            <small class="text-muted">
                <i class="fas fa-sign-in-alt me-1"></i>
                <a href="{{ route('login') }}">Login</a> untuk memberikan rating
            </small>
        </div>
    @endauth
</div>

@push('styles')
<style>
.rating-component {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.rating-display .stars-display {
    font-size: 1.1em;
}

.interactive-stars .star-btn {
    background: none;
    border: none;
    font-size: 1.5em;
    color: #ddd;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 2px 4px;
}

.interactive-stars .star-btn:hover,
.interactive-stars .star-btn.hover {
    color: #ffc107;
    transform: scale(1.1);
}

.interactive-stars .star-btn .fas {
    color: #ffc107;
}

.interactive-stars .star-btn .far {
    color: #ddd;
}

.interactive-stars .star-btn:hover ~ .star-btn .fas,
.interactive-stars .star-btn.hover ~ .star-btn .fas {
    color: #ddd;
}

.rating-form-stars {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background: white;
    margin-top: 10px;
}

.rating-actions .btn {
    font-size: 0.875rem;
}

.review-section {
    margin-top: 10px;
}

.rating-text {
    font-size: 0.95em;
}

@media (max-width: 576px) {
    .rating-component {
        padding: 10px;
    }
    
    .interactive-stars .star-btn {
        font-size: 1.3em;
        padding: 1px 3px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize rating functionality
    initRatingSystem();
});

function initRatingSystem() {
    document.querySelectorAll('.rating-form-stars').forEach(form => {
        const menuId = form.dataset.menuId;
        const stars = form.querySelectorAll('.star-btn');
        const ratingInput = form.querySelector('input[name="rating"]');
        const submitBtn = form.querySelector('button[type="submit"]');
        const ratingValue = document.getElementById(`rating-value-${menuId}`);
        
        // Star click handlers
        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                setRating(stars, rating, ratingInput, submitBtn, ratingValue);
            });
            
            // Hover effects
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                highlightStars(stars, rating);
            });
        });
        
        // Reset hover on mouse leave
        form.querySelector('.interactive-stars').addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingInput.value) || 0;
            highlightStars(stars, currentRating);
        });
        
        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitRating(this, menuId);
        });
    });
}

function setRating(stars, rating, ratingInput, submitBtn, ratingValue) {
    ratingInput.value = rating;
    highlightStars(stars, rating);
    submitBtn.disabled = false;
    ratingValue.textContent = `${rating}/5`;
}

function highlightStars(stars, rating) {
    stars.forEach((star, index) => {
        const starIcon = star.querySelector('i');
        if (index < rating) {
            starIcon.className = 'fas fa-star';
        } else {
            starIcon.className = 'far fa-star';
        }
    });
}

function submitRating(form, menuId) {
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengirim...';
    
    fetch(`/menu/${menuId}/rating`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('success', 'Rating berhasil disimpan!');
            
            // Update rating display
            updateRatingDisplay(menuId, data.newAverage, data.totalRatings);
            
            // Update button text
            submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Tersimpan';
            
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-edit me-1"></i>Update Rating';
                submitBtn.disabled = false;
            }, 2000);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Gagal menyimpan rating. Silakan coba lagi.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function deleteRating(menuId) {
    if (confirm('Yakin ingin menghapus rating Anda?')) {
        fetch(`/menu/${menuId}/rating`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('info', 'Rating berhasil dihapus');
                location.reload(); // Refresh to update UI
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Gagal menghapus rating');
        });
    }
}

function updateRatingDisplay(menuId, newAverage, totalRatings) {
    // Update the average rating display
    const ratingDisplay = document.querySelector(`[data-menu-id="${menuId}"]`).closest('.rating-component').querySelector('.rating-display');
    if (ratingDisplay) {
        const starsDisplay = ratingDisplay.querySelector('.stars-display');
        const ratingText = ratingDisplay.querySelector('.rating-text');
        
        // Update stars
        const stars = starsDisplay.querySelectorAll('i');
        stars.forEach((star, index) => {
            if (index < Math.floor(newAverage)) {
                star.className = 'fas fa-star text-warning me-1';
            } else {
                star.className = 'far fa-star text-warning me-1';
            }
        });
        
        // Update text
        ratingText.innerHTML = `<strong>${parseFloat(newAverage).toFixed(1)}</strong> <small class="text-muted">(${totalRatings} ulasan)</small>`;
    }
}

function showAlert(type, message) {
    // Create and show alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>
@endpush
