@echo off
echo Enabling GD Extension for PHP...
echo.

REM Backup original php.ini
copy "C:\xampp\php\php.ini" "C:\xampp\php\php.ini.backup" >nul 2>&1
echo [BACKUP] Created backup of php.ini

REM Enable GD extension by uncommenting it
powershell -Command "(Get-Content 'C:\xampp\php\php.ini') -replace ';extension=gd', 'extension=gd' | Set-Content 'C:\xampp\php\php.ini'"
echo [ENABLED] GD extension enabled in php.ini

echo.
echo [INFO] Please restart Apache in XAMPP Control Panel for changes to take effect
echo [INFO] You can then verify GD is working by running: php -m | findstr gd
echo.
pause
