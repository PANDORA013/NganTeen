/**
 * Cart Management dengan AJAX - NganTeen
 * Menggunakan jQuery untuk interaksi real-time keranjang belanja
 */

class CartManager {
    constructor() {
        this.init();
    }

    init() {
        // Setup CSRF token untuk semua AJAX request
        this.setupCSRF();
        
        // Bind event listeners
        this.bindEvents();
        
        // Update cart count saat page load
        this.updateCartCount();
        
        console.log('🛒 CartManager initialized');
    }

    setupCSRF() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    bindEvents() {
        // Add to cart button
        $(document).on('click', '.btn-add-to-cart', (e) => {
            e.preventDefault();
            this.addToCart(e.currentTarget);
        });

        // Update quantity in cart
        $(document).on('change', '.cart-quantity-input', (e) => {
            this.updateCartQuantity(e.currentTarget);
        });

        // Remove from cart
        $(document).on('click', '.btn-remove-from-cart', (e) => {
            e.preventDefault();
            this.removeFromCart(e.currentTarget);
        });

        // Quick add buttons (+/-)
        $(document).on('click', '.btn-quantity-plus', (e) => {
            e.preventDefault();
            this.changeQuantity(e.currentTarget, 1);
        });

        $(document).on('click', '.btn-quantity-minus', (e) => {
            e.preventDefault();
            this.changeQuantity(e.currentTarget, -1);
        });
    }

    addToCart(button) {
        const $button = $(button);
        const menuId = $button.data('menu-id');
        const quantity = parseInt($button.data('quantity') || 1);
        
        // Show loading state
        const originalText = $button.text();
        $button.prop('disabled', true).text('Menambahkan...');

        $.ajax({
            url: '/api/cart/add',
            type: 'POST',
            data: {
                menu_id: menuId,
                jumlah: quantity
            },
            success: (response) => {
                if (response.success) {
                    // Show success notification
                    this.showNotification('success', response.message);
                    
                    // Update cart count badge
                    this.updateCartCountBadge(response.data.cart_count);
                    
                    // Update button text temporarily
                    $button.text('✓ Ditambahkan');
                    setTimeout(() => {
                        $button.text(originalText);
                    }, 2000);
                } else {
                    this.showNotification('error', response.message);
                }
            },
            error: (xhr) => {
                const errorMsg = xhr.responseJSON?.message || 'Gagal menambahkan ke keranjang';
                this.showNotification('error', errorMsg);
            },
            complete: () => {
                // Reset button state
                $button.prop('disabled', false);
                if ($button.text() === 'Menambahkan...') {
                    $button.text(originalText);
                }
            }
        });
    }

    updateCartQuantity(input) {
        const $input = $(input);
        const cartItemId = $input.data('cart-id');
        const newQuantity = parseInt($input.val());
        
        if (newQuantity < 1) {
            $input.val(1);
            return;
        }

        // Show loading state
        $input.prop('disabled', true);

        $.ajax({
            url: `/api/cart/update/${cartItemId}`,
            type: 'PUT',
            data: {
                jumlah: newQuantity
            },
            success: (response) => {
                if (response.success) {
                    // Update item total
                    const $itemTotal = $input.closest('.cart-item').find('.item-total');
                    $itemTotal.text(response.data.formatted_item_total);
                    
                    // Update cart total
                    $('.cart-total').text(response.data.formatted_cart_total);
                    
                    this.showNotification('success', response.message);
                } else {
                    // Reset to previous value if failed
                    $input.val($input.data('previous-value') || 1);
                    this.showNotification('error', response.message);
                }
            },
            error: (xhr) => {
                // Reset to previous value if failed
                $input.val($input.data('previous-value') || 1);
                const errorMsg = xhr.responseJSON?.message || 'Gagal memperbarui jumlah';
                this.showNotification('error', errorMsg);
            },
            complete: () => {
                $input.prop('disabled', false);
            }
        });
    }

    removeFromCart(button) {
        const $button = $(button);
        const cartItemId = $button.data('cart-id');
        const $cartItem = $button.closest('.cart-item');
        
        // Confirm deletion
        if (!confirm('Yakin ingin menghapus item ini dari keranjang?')) {
            return;
        }

        // Show loading state
        $button.prop('disabled', true).text('Menghapus...');

        $.ajax({
            url: `/api/cart/remove/${cartItemId}`,
            type: 'DELETE',
            success: (response) => {
                if (response.success) {
                    // Animate removal
                    $cartItem.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Update cart total
                        $('.cart-total').text(response.data.formatted_cart_total);
                        
                        // Update cart count badge
                        cartManager.updateCartCountBadge(response.data.cart_count);
                        
                        // Show empty cart message if needed
                        if (response.data.is_empty) {
                            $('.cart-items-container').html(`
                                <div class="text-center py-4">
                                    <p>Keranjang belanja Anda kosong</p>
                                    <a href="/pembeli/menu" class="btn btn-primary">Mulai Belanja</a>
                                </div>
                            `);
                        }
                    });
                    
                    this.showNotification('success', response.message);
                } else {
                    this.showNotification('error', response.message);
                }
            },
            error: (xhr) => {
                const errorMsg = xhr.responseJSON?.message || 'Gagal menghapus item';
                this.showNotification('error', errorMsg);
            },
            complete: () => {
                $button.prop('disabled', false).text('Hapus');
            }
        });
    }

    changeQuantity(button, change) {
        const $button = $(button);
        const $input = $button.siblings('.cart-quantity-input');
        const currentValue = parseInt($input.val());
        const newValue = Math.max(1, currentValue + change);
        
        $input.data('previous-value', currentValue).val(newValue).trigger('change');
    }

    updateCartCount() {
        $.ajax({
            url: '/api/cart/count',
            type: 'GET',
            success: (response) => {
                if (response.success) {
                    this.updateCartCountBadge(response.cart_count);
                }
            },
            error: () => {
                console.log('Failed to update cart count');
            }
        });
    }

    updateCartCountBadge(count) {
        const $badge = $('.cart-count-badge');
        if (count > 0) {
            $badge.text(count).show();
        } else {
            $badge.hide();
        }
    }

    showNotification(type, message) {
        // Use existing notification system or create simple toast
        if (typeof window.NotificationHandler !== 'undefined') {
            window.NotificationHandler.show(type, message);
        } else {
            // Fallback: simple alert or toast
            this.showSimpleToast(type, message);
        }
    }

    showSimpleToast(type, message) {
        // Create simple toast notification
        const toastClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const toast = $(`
            <div class="alert ${toastClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(toast);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            toast.fadeOut(() => toast.remove());
        }, 3000);
    }
}

// Initialize Cart Manager when document is ready
$(document).ready(() => {
    window.cartManager = new CartManager();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartManager;
}
