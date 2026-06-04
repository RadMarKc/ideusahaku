# Sistem Rekomendasi Ide Usaha Mikro

Aplikasi web ini digunakan untuk memberikan rekomendasi ide usaha mikro berdasarkan parameter modal, kategori usaha, dan waktu luang pengguna.

## Metode Rekomendasi

Sistem menggunakan Weighted Product Method (WPM) sebagai inti perhitungan rekomendasi. Setiap ide usaha diberi nilai kecocokan berdasarkan:

- Modal
- Kategori usaha
- Waktu luang

Bobot kriteria yang digunakan:

- Modal: 0.45
- Waktu luang: 0.35
- Kategori usaha: 0.20

Rumus skor:

```text
Skor = ModalFit^0.45 x WaktuFit^0.35 x KategoriFit^0.20
```

Hasil rekomendasi ditampilkan dalam bentuk persentase 0-100% beserta deskripsi usaha.

Kategori usaha yang digunakan:

- Online
- Offline
- Rumahan
- Hybrid (Online + Offline)

## Pendekatan Pembangunan Sistem

Pengembangan sistem menggunakan Rapid Application Development (RAD). Pendekatan ini dipilih karena mendukung proses pembangunan yang cepat, iteratif, dan berfokus pada kebutuhan pengguna.

Tahapan RAD yang digunakan:

1. Requirements Planning

   Mengidentifikasi kebutuhan sistem, parameter rekomendasi, data ide usaha, dan output yang diperlukan pengguna.

2. User Design

   Merancang alur input parameter, tampilan hasil rekomendasi, struktur data ide usaha, dan validasi kebutuhan bersama pengguna.

3. Construction

   Mengimplementasikan aplikasi berbasis Laravel, membuat model data, form input, proses perhitungan Weighted Product Method, dan tampilan hasil rekomendasi.

4. Cutover

   Melakukan pengujian, penyesuaian hasil rekomendasi, migrasi/seed data, dan persiapan aplikasi agar dapat digunakan.

## Teknologi

- Laravel
- PHP
- Blade
- Bootstrap
- SQLite/MySQL sesuai konfigurasi `.env`

## Menjalankan Aplikasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Aplikasi dapat dibuka melalui URL yang ditampilkan oleh `php artisan serve`.

## Akun Default

Setelah menjalankan seeder, pengguna dapat login dengan akun berikut:

- Username: `admin`
- Password: `password`
