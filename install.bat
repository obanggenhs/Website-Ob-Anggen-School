@echo off
title Instalasi Ob Anggen School
echo ==============================================
echo   Instalasi website Ob Anggen School
echo   Pastikan MySQL (XAMPP / Laragon) sudah menyala
echo ==============================================
if not exist .env copy .env.example .env
php artisan key:generate --force
php artisan oa:install
echo.
echo Selesai. Jalankan start.bat untuk membuka website.
pause
