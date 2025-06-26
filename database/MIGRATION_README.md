# Dokumentasi Migrasi Database

## Struktur File Migrasi

1. **Migrasi Sistem** (format simple):
   - `create_users_table.php`
   - `create_cache_table.php`  
   - `create_jobs_table.php`

2. **Migrasi Bisnis** (format tanggal):
   - `2024_01_09_*` (tabel menu, cart, order)
   - `2025_06_*` (modifikasi terbaru)

## Panduan
- Untuk migrasi baru, gunakan format:
  ```bash
  php artisan make:migration create_nama_tabel_table
  ```
- Untuk modifikasi tabel, gunakan format:
  ```bash
  php artisan make:migration add_kolom_to_tabel_table
  ```

## Best Practices
- Gunakan snake_case untuk nama tabel
- Tambahkan docblock untuk setiap migrasi
- Tes migrasi setelah dibuat
