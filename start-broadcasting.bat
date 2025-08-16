@echo off
echo Starting Laravel Broadcasting Services...
echo.

echo 1. Starting Queue Worker...
start "Queue Worker" cmd /k "cd /d %~dp0 && php artisan queue:work --timeout=60 --sleep=3 --tries=3"
timeout /t 2 /nobreak > nul

echo 2. Starting Development Server...
start "Laravel Server" cmd /k "cd /d %~dp0 && php artisan serve"
timeout /t 2 /nobreak > nul

echo.
echo ✅ Broadcasting services started!
echo.
echo Services running:
echo - Queue Worker (for stable broadcasting)
echo - Laravel Development Server (http://127.0.0.1:8000)
echo.
echo To test broadcasting:
echo - Visit: http://127.0.0.1:8000/test-broadcasting
echo - Or run: php artisan test:broadcasting order
echo - Or run: php artisan test:broadcasting menu
echo.
echo Press any key to exit...
pause > nul
