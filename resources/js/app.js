import './bootstrap';
import './notifications';
import './cart-manager';

// Make jQuery available globally if needed
import $ from 'jquery';
window.$ = window.jQuery = $;
import Alpine from 'alpinejs';

// Initialize AlpineJS
window.Alpine = Alpine;
Alpine.start();
