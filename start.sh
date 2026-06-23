#!/bin/bash

# Pastikan folder penyimpanan tersedia
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/app/public

# Membuat symlink storage agar file bisa diakses publik
php artisan storage:link

# Jalankan migrasi database otomatis (di mode produksi)
php artisan migrate --force

# Membersihkan dan membuat cache baru untuk optimasi kecepatan
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan server Apache di background
apache2-foreground
