# 🚀 Sistem Real-time Broadcasting NganTeen

## Overview
Sistem notifikasi real-time menggunakan Laravel Broadcasting dengan Pusher untuk memberikan update langsung kepada user tentang status pesanan dan menu baru.

## 🔧 Komponen Utama

### 1. Broadcasting Configuration
- **File**: `config/broadcasting.php`
- **Driver**: Pusher + Log fallback
- **Pusher Credentials**:
  - App ID: 2037762
  - Key: f9fe0dca9f0fd8fd8fbb
  - Cluster: ap1

### 2. Events Broadcasting

#### OrderStatusUpdated Event
- **File**: `app/Events/OrderStatusUpdated.php`
- **Channels**: 
  - `user.{user_id}` (private)
  - `orders.{order_id}` (public)
- **Trigger**: Ketika status order berubah
- **Data**: order_id, old_status, new_status, message, timestamp

#### NewMenuAdded Event
- **File**: `app/Events/NewMenuAdded.php`
- **Channels**:
  - `menu.updates` (public)
  - `area.{area_kampus}` (public)
- **Trigger**: Ketika menu baru ditambahkan
- **Data**: menu details, message, timestamp

### 3. Frontend Integration
- **File**: `resources/js/notifications.js`
- **Library**: Laravel Echo + Pusher JS
- **Features**:
  - Real-time notifications
  - Toast notifications
  - Channel subscriptions
  - Error handling

### 4. Queue System
- **Driver**: Database
- **Command**: `php artisan queue:work`
- **Purpose**: Stable background processing untuk broadcasting

## 🚀 Cara Menjalankan

### Quick Start
```bash
# Jalankan semua servis sekaligus
start-broadcasting.bat
```

### Manual Start
```bash
# 1. Start Queue Worker
php artisan queue:work --timeout=60 --sleep=3 --tries=3

# 2. Start Laravel Server
php artisan serve
```

## 🧪 Testing Broadcasting

### 1. Test Commands
```bash
# Test order status update
php artisan test:broadcasting order

# Test new menu added
php artisan test:broadcasting menu
```

### 2. Test Page
- URL: `http://127.0.0.1:8000/test-broadcasting`
- Features: Manual testing buttons untuk semua events

### 3. API Endpoints
```bash
# Test order update via API
POST /api/test-order-broadcast
{
    "order_id": 123,
    "old_status": "pending",
    "new_status": "ready"
}

# Test menu broadcast via API
POST /api/test-menu-broadcast
{
    "menu_name": "Nasi Goreng",
    "price": 15000
}
```

## 🔧 Environment Setup

### Required .env Variables
```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=2037762
PUSHER_APP_KEY=f9fe0dca9f0fd8fd8fbb
PUSHER_APP_SECRET=27e29160eddabdfd129d
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=ap1
```

### Queue Configuration
```env
QUEUE_CONNECTION=database
```

## 📝 Usage dalam Code

### Trigger Order Status Update
```php
use App\Events\OrderStatusUpdated;

// Dalam controller atau model
$order = Order::find(1);
$oldStatus = $order->status;
$order->status = 'ready';
$order->save();

// Broadcast event
OrderStatusUpdated::dispatch($order, $oldStatus, 'ready');
```

### Trigger New Menu Added
```php
use App\Events\NewMenuAdded;

// Setelah menu dibuat
$menu = Menu::create([...]);
NewMenuAdded::dispatch($menu);
```

### Frontend Subscription
```javascript
// Subscribe ke notifikasi user
Echo.private(`user.${userId}`)
    .listen('.order.status.updated', (data) => {
        showNotification(data.message, 'success');
    });

// Subscribe ke menu updates
Echo.channel('menu.updates')
    .listen('.menu.added', (data) => {
        showNotification(data.message, 'info');
    });
```

## 🐛 Troubleshooting

### 1. Broadcasting Tidak Bekerja
- Cek koneksi internet (Pusher memerlukan koneksi)
- Pastikan queue worker berjalan
- Cek kredensial Pusher di .env

### 2. Queue Jobs Gagal
- Cek tabel `failed_jobs`
- Restart queue worker: `php artisan queue:restart`
- Cek log: `storage/logs/laravel.log`

### 3. Frontend Tidak Menerima Events
- Cek console browser untuk error
- Pastikan CSRF token tersedia
- Cek channel authorization di `routes/channels.php`

## 📊 Monitoring

### Queue Status
```bash
# Cek status queue
php artisan queue:monitor

# Lihat failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Broadcasting Logs
- Log driver: `storage/logs/laravel.log`
- Browser console untuk frontend errors
- Network tab untuk WebSocket connections

## 🔄 Status System

### Order Status Flow
1. `pending` → User membuat pesanan
2. `paid` → User melakukan pembayaran
3. `processing` → Penjual mulai proses
4. `ready` → Pesanan siap diambil
5. `completed` → Pesanan selesai
6. `cancelled` → Pesanan dibatalkan

### Notification Messages
- **ready**: "Pesanan Anda sudah siap! Silakan ambil di lokasi penjual."
- **paid**: "Terima kasih! Pembayaran pesanan Anda sudah dikonfirmasi."
- **cancelled**: "Maaf, pesanan Anda dibatalkan. Silakan hubungi penjual untuk info lebih lanjut."

## 🎯 Best Practices

1. **Selalu jalankan queue worker** untuk broadcasting yang stabil
2. **Handle koneksi error** di frontend dengan reconnection logic
3. **Log semua broadcasting events** untuk debugging
4. **Gunakan private channels** untuk data sensitif user
5. **Batch multiple notifications** untuk performa optimal

## 🔐 Security

1. **Private Channels**: Hanya user yang bersangkutan bisa akses
2. **CSRF Protection**: Token included dalam meta tags
3. **Rate Limiting**: Pusher otomatis handle rate limiting
4. **Data Validation**: Semua data di-validate sebelum broadcast
