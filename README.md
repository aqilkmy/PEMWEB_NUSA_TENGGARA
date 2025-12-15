# WonderfulNTT - Platform Wisata Nusa Tenggara Timur

## Deskripsi Proyek

WonderfulNTT adalah platform web informasi wisata yang dirancang untuk memperkenalkan keindahan alam, budaya, dan kekayaan kuliner Nusa Tenggara Timur. Platform ini menyediakan informasi lengkap tentang destinasi wisata, memfasilitasi interaksi komunitas melalui sistem komentar, dan memberikan rekomendasi perjalanan terpercaya.

## Fitur Utama

### Untuk Pengunjung
- Jelajahi destinasi wisata dengan kategori lengkap
- Pencarian destinasi berdasarkan nama dan lokasi
- Detail informasi destinasi dengan galeri foto
- Integrasi Google Maps untuk lokasi destinasi
- Sistem komentar dan ulasan
- Formulir kontak untuk pertanyaan

### Untuk Admin
- Dashboard manajemen destinasi
- Upload dan kelola gambar destinasi (utama + 3 sub gambar)
- CRUD destinasi wisata
- Manajemen kategori destinasi
- Validasi dan keamanan file upload

### Keamanan
- Autentikasi berbasis session
- Password hashing dengan SHA-256
- Sanitasi input untuk mencegah SQL Injection
- Validasi file upload dengan whitelist
- Proteksi direktori dengan .htaccess
- Role-based access control (Admin/User)

## Teknologi yang Digunakan

### Backend
- PHP 7.4+
- MySQL 5.7+
- Session-based Authentication

### Frontend
- HTML5
- CSS3 (Custom styling)
- Vanilla JavaScript

### Database
- MySQL dengan 5 tabel utama:
  - users (autentikasi dan role)
  - kategori (kategori destinasi)
  - destinasi (data destinasi wisata)
  - komentar (ulasan pengguna)
  - kontak (pesan kontak)

## Struktur Direktori

```
wonderfulntt/
├── admin/
│   ├── dashboard.php
│   ├── auth_check.php
│   ├── logout.php
│   └── .htaccess
├── assets/
│   ├── css/
│   │   └── style.css
│   └── images/
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── config/
│   ├── database.php
│   └── config.php
├── includes/
│   ├── functions.php
│   ├── header.php
│   └── footer.php
├── pages/
│   ├── destination.php
│   ├── detail.php
│   ├── about.php
│   └── contact.php
├── uploads/
│   └── .htaccess
├── index.php
└── database.sql
```

## Instalasi

### Prasyarat
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server Apache atau PHP built-in server

### Langkah Instalasi

1. Clone atau download repository
```bash
git clone <repository-url>
cd wonderfulntt
```

2. Import database
```bash
mysql -u root -p < database.sql
```
Atau import manual melalui phpMyAdmin

3. Konfigurasi database
Edit file `config/database.php`:
```php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ntt_db';
```

4. Konfigurasi base URL
Edit file `config/config.php`:
```php
define('IS_DEV', true); // Set FALSE untuk production
```

5. Set permission folder uploads
```bash
chmod 755 uploads/
```

6. Jalankan aplikasi

Menggunakan PHP built-in server:
```bash
php -S localhost:3000
```

Atau akses melalui web server (Apache):
```
http://localhost/wonderfulntt/
```

## Penggunaan

### Untuk Pengunjung
1. Buka halaman beranda
2. Jelajahi destinasi melalui menu "Destinasi"
3. Gunakan fitur pencarian untuk menemukan lokasi spesifik
4. Klik "Detail" untuk melihat informasi lengkap
5. Login/Register untuk memberikan komentar
6. Gunakan menu "Kontak" untuk mengirim pesan

### Untuk Admin
1. Login dengan akun admin
2. Akses dashboard admin
3. Kelola destinasi (Tambah/Edit/Hapus)
4. Upload gambar destinasi (maks 15MB per file)
5. Set kategori dan lokasi Google Maps
6. Logout setelah selesai

## Konfigurasi Tambahan

### Upload File
Edit `config/config.php` untuk mengubah batas ukuran file:
```php
define('MAX_FILE_SIZE', 15 * 1024 * 1024); // 15MB
```

### Informasi Kontak
Edit `config/config.php`:
```php
define('CONTACT_EMAIL', 'email@example.com');
define('CONTACT_PHONE', '0812-xxxx-xxxx');
define('CONTACT_ADDRESS', 'Alamat lengkap');
```

## Tim Pengembang

| Nama | Role | Jobdesk |
|------|------|---------|
| **Muhammad Aqil Karomy** | Frontend Developer (Ketua) | - Implementasi UI/UX design<br>- Pengembangan halaman frontend<br>- Integrasi API dan backend<br>- Testing fungsionalitas frontend<br>- Koordinasi tim dan project management |
| **Rahmadani Hafsari** | Backend Developer | - Struktur database dan relasi<br>- Pengembangan logic bisnis<br>- Implementasi autentikasi dan otorisasi<br>- Validasi dan sanitasi data<br>- File upload handling |
| **Dzikry Naufal Permana** | UI/UX Designer | - Riset user experience<br>- Desain mockup dan wireframe<br>- Pemilihan color scheme dan typography<br>- Desain layout halaman<br>- User flow optimization |