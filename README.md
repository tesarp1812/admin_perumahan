
# Admin Perumahan

Selamat datang di proyek **Admin Perumahan**. Ini adalah API backend yang dibangun dengan menggunakan Laravel Lumen untuk mengelola data perumahan dan pembayaran.

## Table of Contents

1. [Prasyarat](#prasyarat)
2. [Instalasi](#instalasi)
3. [Migrasi Database](#migrasi-database)
4. [Konfigurasi .env](#konfigurasi-env)
5. [Struktur Proyek](#struktur-proyek)
6. [Fitur](#fitur)
7. [Kontribusi](#kontribusi)
8. [Lisensi](#lisensi)

## Prasyarat

Sebelum memulai, pastikan Anda telah menginstal:

- [PHP](https://www.php.net/) (versi 7.2 atau lebih baru)
- [Composer](https://getcomposer.org/) (versi 1.0 atau lebih baru)
- [MySQL](https://www.mysql.com/) atau database lain yang didukung

## Instalasi

1. **Clone repositori ini:**

   ```bash
   git clone https://github.com/tesarp1812/admin_perumahan.git
   ```

2. **Masuk ke direktori proyek:**

   ```bash
   cd admin_perumahan
   ```

3. **Instal dependensi menggunakan Composer:**

   ```bash
   composer install
   ```

4. **Salin berkas `.env.example` menjadi `.env`:**

   ```bash
   cp .env.example .env
   ```

5. **Konfigurasi koneksi database di berkas `.env`:**

   Edit berkas `.env` untuk menyesuaikan pengaturan database Anda. Berikut adalah contoh konfigurasi untuk MySQL:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=admin_rumah
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Pastikan untuk mengganti `DB_PASSWORD` sesuai dengan password database Anda, jika ada.

## Migrasi Database

Untuk menjalankan migrasi dan mengisi data awal (seeding), gunakan perintah berikut:

```bash
php artisan migrate --seed
```

Perintah ini akan menjalankan semua migrasi yang belum dijalankan dan mengisi database dengan data awal sesuai dengan seeder yang telah disediakan.

## Struktur Proyek

Berikut adalah struktur direktori utama dalam proyek ini:

```
admin_perumahan/
├── app/                 # Kode sumber aplikasi
│   ├── Http/            # Controller, middleware, dan request
│   ├── Models/          # Model Eloquent
│   ├── Database/        # Seeder dan migration
│   └── ...
├── bootstrap/           # Bootstrap aplikasi
├── config/              # File konfigurasi
├── database/            # File migrasi dan seeder
├── routes/              # Rute aplikasi
├── .env                 # Pengaturan environment
├── .gitignore           # File untuk mengabaikan file yang tidak perlu
├── composer.json        # File konfigurasi Composer
└── README.md            # Dokumentasi proyek
```

## Fitur

- Mengelola data perumahan dan pembayaran
- API untuk CRUD data perumahan
- Fitur untuk mengelola pembayaran dan detail pembayaran

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan lakukan fork dan buat pull request. Pastikan untuk mengikuti pedoman pengembangan yang ada.

## Lisensi

Proyek ini dilisensikan di bawah MIT License. Lihat berkas [LICENSE](LICENSE) untuk informasi lebih lanjut.

