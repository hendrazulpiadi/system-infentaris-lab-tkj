# Sistem Inventaris Perangkat Lab TKJ

Sistem Inventaris Perangkat Lab TKJ adalah aplikasi berbasis web yang dirancang khusus untuk mengelola lifecycle perangkat di laboratorium komputer sekolah (khususnya jurusan Teknik Komputer dan Jaringan). Aplikasi ini membantu admin dalam mendata pengajuan alat, proses belanja, inventaris gudang, hingga pemantauan alat yang sedang digunakan untuk praktek atau rusak.

## 🚀 Fitur Utama
- **Dashboard Statistik**: Tampilan ringkasan jumlah perangkat berdasarkan status (Pengajuan, Belanja, Gudang, Praktek, Rusak).
- **Manajemen Inventaris (CRUD)**: Kelola data perangkat mulai dari nama, kode unik, deskripsi, hingga lokasi.
- **Log Riwayat Alur**: Setiap perubahan status perangkat dicatat secara otomatis untuk kebutuhan audit.
- **Modul Laporan**: Generate laporan bulanan atau semesteran yang siap dicetak (Print-friendly).
- **Sistem Autentikasi**: Login aman dengan enkripsi password (Bcrypt).
- **Desain Modern**: Antarmuka berbasis Dark Mode dengan estetika *Glassmorphism*.

## 🛠️ Teknologi yang Digunakan
- **Bahasa Pemrograman**: PHP Native (7.4+)
- **Database**: MariaDB / MySQL (Interaksi via PDO untuk keamanan SQL Injection)
- **Frontend**: Vanilla CSS (Custom Glassmorphism UI), Google Fonts (Outfit & Inter), FontAwesome 6.
- **Server Environment**: Direkomendasikan menggunakan XAMPP atau Laragon.

## 📦 Cara Instalasi

Jika Anda ingin mencoba atau menginstal sistem ini secara mandiri, ikuti langkah-langkah berikut:

### 1. Persiapan Environment
Pastikan Anda sudah menginstal **XAMPP** (Apache & MySQL).

### 2. Download Project
Download atau Clone repositori ini dan letakkan di dalam folder `htdocs` Anda:
```bash
git clone https://github.com/hendrazulpiadi/system-infentaris-lab-tkj.git
```
Atau ekstrak file zip ke: `C:\xampp\htdocs\infentaris-tkj`

### 3. Setup Database
1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Buat database baru dengan nama `db_inventaris_tkj`.
3. Pilih database tersebut, lalu klik menu **Import**.
4. Pilih file `database.sql` yang ada di dalam folder proyek, lalu klik **Go/Import**.

### 4. Konfigurasi Koneksi
Buka file `koneksi.php` menggunakan text editor (Notepad++/VS Code) dan sesuaikan kredensial database Anda:
```php
$host = 'localhost';
$db   = 'db_inventaris_tkj';
$user = 'root'; // Default XAMPP
$pass = '';     // Kosongkan jika default
```

### 5. Jalankan Aplikasi
Buka browser Anda dan akses:
`http://localhost/infentaris-tkj/login.php`

**Akun Login Default:**
- **Username**: `admin`
- **Password**: `admin`

## 📄 Lisensi
Proyek ini dilisensikan di bawah **MIT License**. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

---
Dikembangkan dengan ❤️ untuk kemajuan pendidikan IT.
