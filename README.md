# NganTeen

NganTeen adalah platform kantin digital berbasis Laravel untuk menghubungkan pembeli, penjual, dan administrator dalam satu alur pemesanan.

## Fitur utama

- Pembeli: jelajah menu, keranjang, checkout, riwayat pesanan, favorit, dan rating.
- Penjual: profil warung, pengelolaan menu, pesanan, QRIS, serta payout.
- Admin: pengelolaan pengguna, warung, transaksi, payout, konten, dan analitik.
- Pembaruan real-time melalui Laravel Echo dan Pusher.

## Teknologi

- PHP 8.2+, Laravel 12, MySQL
- Vite, Tailwind CSS, Bootstrap, Alpine.js
- Laravel Echo dan Pusher

## Struktur proyek

```text
app/                 Logika aplikasi, model, middleware, dan controller
database/            Migration, factory, dan seeder
docs/                Dokumentasi teknis dan deployment
public/              Entry point serta aset publik
resources/           Source CSS, JavaScript, dan Blade views
  views/admin/        Antarmuka administrator
  views/pembeli/      Antarmuka pembeli
  views/penjual/      Antarmuka penjual
routes/               Definisi route web, console, dan broadcasting
tests/                Feature dan browser tests
```

File eksperimen, laporan pengerjaan, dan view cadangan tidak disimpan bersama source produksi. Gunakan branch atau pull request untuk menyimpan riwayat perubahan.

## Menjalankan secara lokal

Prasyarat: PHP 8.2+, Composer, Node.js 20+, npm, MySQL, serta ekstensi PHP `mbstring`, `xml`, `intl`, `pdo_mysql`, dan `gd`.

```bash
git clone https://github.com/PANDORA013/NganTeen.git
cd NganTeen
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Buat database `nganteen_db`, sesuaikan konfigurasi `DB_*` di `.env`, lalu jalankan:

```bash
php artisan migrate
php artisan storage:link
npm run build
php artisan serve
```

Untuk mode pengembangan frontend, gunakan `npm run dev` pada terminal terpisah.

## Membuat administrator

Jangan menyimpan password di source code. Gunakan perintah interaktif berikut:

```bash
php artisan admin:create admin@example.com --name="Administrator"
```

Password diminta secara tersembunyi dan harus berisi minimal 12 karakter.

## Pengujian

```bash
php artisan test
npm run build
```

GitHub Actions menjalankan build frontend, migrasi MySQL, dan Feature Tests pada setiap pull request ke `main`.

## Dokumentasi

- [Broadcasting](docs/BROADCASTING.md)
- [Deployment](docs/DEPLOYMENT.md)
- [Kebijakan keamanan](SECURITY.md)

## Lisensi

Proyek ini menggunakan lisensi MIT.
