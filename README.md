# API Sistem Pemesanan Tiket Event Online

## Judul Proyek
**API Sistem Pemesanan Tiket Event Online**

## Deskripsi Singkat
Sebuah RESTful Web Service yang dibuat dengan Laravel untuk mengelola event, tiket, transaksi, dan profil pengguna. Sistem ini menyediakan endpoint untuk publik (melihat event) dan endpoint autentikasi/administrasi (membuat event, mengelola tiket, memproses transaksi) dengan autentikasi menggunakan Laravel Sanctum.

## Cara Menjalankan Sistem
Ikuti langkah-langkah berikut untuk menjalankan aplikasi secara lokal:

1. Clone repository

   git clone https://github.com/witherlangga/API-Sistem-Pemesanan-Tiket-Event-Online.git
   
   cd API-Sistem-Pemesanan-Tiket-Event-Online

2. Install dependency PHP

   composer install

3. Salin file environment dan konfigurasi

   cp .env.example .env
   - Atur koneksi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
   - Sesuaikan variabel lain bila perlu (MAIL_*, JWT, dll.)

4. Generate application key

   php artisan key:generate

5. Jalankan migrasi dan seeder (opsional jika ingin data contoh)

   php artisan migrate
   php artisan db:seed

6. Buat symbolic link untuk storage (agar file publik bisa diakses)

   php artisan storage:link

7. (Frontend assets) install dan jalankan Vite (jika ingin menjalankan UI lokal)

   npm install
   npm run dev

8. Jalankan server lokal

   php artisan serve

> Jika menggunakan lingkungan Windows dengan Laragon, Anda bisa menjalankan server dengan `php artisan serve` atau menggunakan virtual host dari Laragon.

## Dokumentasi API
- Postman public documentation: https://documenter.getpostman.com/view/49032388/2sBXVfiWLE
---

## Anggota Kelompok
- Erlangga Syafutra - 2301010192
  username Github : witherlangga

- Dimas Okta Rizki - 2301010196
  username Github : RexxyKY

- M.Erlangga Ardiansyah - 2301010210
  username Github : Ardian210

