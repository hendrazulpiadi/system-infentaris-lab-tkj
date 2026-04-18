# Instruksi Unit Testing: Sistem Inventaris Lab TKJ

## 1. Tujuan
Memastikan seluruh fitur sistem berjalan dengan stabil, aman, dan bebas dari error logika sebelum sistem digunakan secara produksi.

## 2. Persiapan Testing
- Gunakan database `db_inventaris_tkj` yang sudah diimpor.
- Gunakan browser (Chrome/Edge) dan aktifkan *Developer Tools* (F12) untuk memantau jika ada error pada console atau network.

## 3. Skenario Pengujian (Simulasi Kemungkinan)

### A. Modul Autentikasi (Login & Session)
1. **Login Berhasil**: Masukkan username `admin` dan password `admin`. Pastikan masuk ke dashboard.
2. **Login Gagal (Wrong Password)**: Masukkan password salah. Pastikan muncul pesan error.
3. **Login Gagal (Wrong Username)**: Masukkan username yang tidak ada. Pastikan muncul pesan error.
4. **Input Kosong**: Klik login tanpa mengisi kolom. Pastikan ada validasi.
5. **Session Protection**: Coba akses `dashboard.php` langsung melalui URL tanpa login. Sistem harus menolak dan me-redirect ke `login.php`.
6. **Logout**: Pastikan setelah klik logout, session hancur dan tidak bisa kembali ke dashboard menggunakan tombol "Back" di browser.

### B. Modul Manajemen Inventaris (CRUD)
1. **Tambah Alat (Valid)**: Isi semua field dengan benar. Pastikan data muncul di tabel inventaris.
2. **Tambah Alat (Kode Duplikat)**: Coba masukkan alat dengan kode yang sudah ada. Pastikan sistem menangani error duplikasi (Database Unique Constraint).
3. **Update Status (Flow Alur)**: Ubah status dari "Gudang" ke "Praktek". Cek di database/tabel log apakah riwayat perubahannya tercatat.
4. **Update Lokasi**: Ubah lokasi perangkat. Pastikan data terupdate di tabel utama.
5. **Hapus Data**: Hapus satu perangkat. Pastikan data benar-benar hilang dari tabel.

### C. Modul Laporan
1. **Filter Bulanan**: Pilih bulan yang memiliki data dan bulan yang kosong. Pastikan tabel menampilkan data yang sesuai atau pesan "Tidak ada data".
2. **Filter Semester**: Pilih Semester Ganjil/Genap. Pastikan data yang muncul hanya yang berada dalam rentang bulan yang ditentukan (Semester 1: Juli-Des, Semester 2: Jan-Jun).
3. **Fitur Cetak**: Klik tombol "Cetak". Pastikan tampilan print bersih (tanpa sidebar) dan data terbaca jelas.

## 4. Pelaporan Error
Jika ditemukan ketidaksesuaian (bug):
- Catat file mana yang bermasalah.
- Catat pesan error yang muncul (jika ada).
- Deskripsikan langkah-langkah untuk memunculkan bug tersebut (Steps to Reproduce).

---
*Instruksi ini dibuat untuk memastikan kualitas sistem tetap terjaga seiring dengan penambahan fitur di masa depan.*
