# Security Policy

## Melaporkan kerentanan

Jangan membuka issue publik untuk kerentanan atau kredensial yang terekspos. Laporkan melalui GitHub Security Advisories pada repositori ini dengan langkah reproduksi dan dampak yang diketahui.

## Praktik deployment

- Jangan commit `.env`, password, token, atau private key.
- Gunakan `APP_DEBUG=false` di production.
- Buat admin melalui `php artisan admin:create`.
- Rotasi segera kredensial yang pernah dibagikan atau tersimpan di source code.
- Pastikan document root web server mengarah hanya ke folder `public/`.
