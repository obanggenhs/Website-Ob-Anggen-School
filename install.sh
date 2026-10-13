#!/usr/bin/env bash
# Instalasi website Ob Anggen School (Linux / macOS)
set -e
[ -f .env ] || cp .env.example .env
php artisan key:generate --force
php artisan oa:install
echo "Selesai. Jalankan: php artisan serve"
