# Deployment

## Arsitektur yang disarankan

NganTeen adalah aplikasi Laravel monolit yang membutuhkan PHP 8.2+, MySQL, penyimpanan persisten untuk unggahan, session/database queue, dan proses worker. Pasang aplikasi pada layanan yang mendukung runtime PHP atau container secara native.

Komponen minimum production:

- web server Nginx/Apache dengan document root ke `public/`;
- PHP-FPM 8.2+ dan ekstensi yang tercantum di README;
- MySQL;
- storage persisten untuk `storage/app/public`;
- queue worker yang dikelola process supervisor;
- cron `php artisan schedule:run` setiap menit;
- Pusher atau layanan broadcasting kompatibel bila fitur real-time dipakai.

Set `APP_ENV=production`, `APP_DEBUG=false`, URL dan kredensial database yang benar, lalu jalankan:

```bash
composer install --no-dev --classmap-authoritative
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

## Catatan Vercel

Vercel tidak menyediakan runtime PHP resmi. Menjalankan Laravel di Vercel memerlukan runtime komunitas dan tetap tidak menyelesaikan kebutuhan worker, filesystem persisten, serta proses aplikasi jangka panjang. Karena itu repositori ini tidak menyertakan `vercel.json` yang berpotensi memberi kesan deployment production sudah didukung.

Jika Vercel wajib digunakan, pendekatan yang lebih stabil adalah memisahkan frontend statis/Next.js ke Vercel dan menempatkan Laravel sebagai API pada host PHP/container tersendiri.
