# Planning Pembangunan Sistem Inventaris Perangkat Lab TKJ

## 1. Deskripsi Proyek
Sistem Inventaris Perangkat Lab TKJ adalah sebuah aplikasi berbasis web yang dirancang untuk menjadi sistem yang ringan dan cepat. Sistem ini bertujuan untuk mencatat dan melacak alur perangkat/alat di lab, mulai dari tahap pengajuan hingga pemusnahan.

## 2. Persiapan Lingkungan Kerja (Tugas Awal)
Sebelum memulai penulisan kode, pastikan lingkungan pengembangan (XAMPP versi 3.3.0) sudah sesuai dan siap digunakan. Lakukan pengecekan komponen berikut:

- **PHP**: Cek versi PHP yang berjalan di XAMPP. Pastikan menggunakan versi PHP minimal 7.4 atau disarankan PHP 8.x untuk performa dan keamanan terbaik. Jika versi di bawah itu, silakan lakukan update XAMPP ke versi yang lebih baru.
- **MySQL/MariaDB**: Pastikan service database berjalan normal. Cek versinya, pastikan tidak ada isu kompatibilitas.
- **Apache**: Pastikan web server Apache dapat dijalankan tanpa bentrok *port* (biasanya berjalan di port 80 dan 443).
- **Tindakan**: Jika dari hasil pengecekan dirasa komponen tersebut terlalu usang dan tidak mendukung fitur PHP/MySQL modern, segera lakukan pencadangan data lama (jika ada) dan *install ulang/update* XAMPP.

## 3. Fitur Utama & Alur Sistem
Sistem ini berfokus pada pelacakan status perangkat dengan fitur-fitur berikut:
1. **Dashboard Utama**: Menampilkan ringkasan (statistik) seluruh data alat (total alat, alat dalam pengajuan, alat di gudang, alat dipakai, alat rusak). Ini adalah halaman pertama setelah admin *login*.
2. **Manajemen Pengguna (Role-based)**:
   - **Super Admin**: Akses penuh ke semua fitur, termasuk manajemen user admin lainnya.
   - **Admin Biasa**: Akses pengelolaan inventaris (tambah, edit, hapus, dan pindah status alat).
3. **Siklus Status Alat (Workflow)**:
   - Alat dalam Pengajuan
   - Alat dalam Proses Belanja
   - Alat Masuk / Disimpan di Gudang
   - Alat Keluar / Dipakai Praktek
   - Alat Rusak / Dihapuskan (Dimusnahkan)
4. **Fitur Laporan**: Kemampuan untuk mencetak / ekspor laporan inventaris berdasarkan:
   - Laporan Bulanan
   - Laporan Per Semester

## 4. Instruksi Pekerjaan untuk Junior Developer
Berikut adalah tahapan pekerjaan yang harus dilakukan (secara berurutan):

### Tahap 0: Persiapan Awal (Uji Coba Hello World)
Sebelum masuk ke logika yang rumit, pastikan server web (XAMPP) sudah terkonfigurasi dengan benar dan dapat membaca file PHP proyek ini.
1. Buat folder proyek baru bernama `infentaris-tkj` di dalam folder `htdocs` pada instalasi XAMPP Anda.
2. Di dalam folder tersebut, buat file bernama `index.php`.
3. Tuliskan script PHP sederhana yang menampilkan tulisan `Hello World! Sistem Inventaris Perangkat Lab TKJ Siap Dibangun.`.
4. Hidupkan modul Apache pada XAMPP Control Panel.
5. Akses proyek melalui browser dengan membuka URL `http://localhost/infentaris-tkj`.
6. Jika tulisan *Hello World* berhasil tampil tanpa *error*, Anda siap melanjutkan ke Tahap 1.

### Tahap 1: Setup Database & Struktur Lanjutan
- Buat folder proyek baru di `htdocs`.
- Rancang dan buat database (misal: `db_inventaris_tkj`).
- Buat tabel-tabel utama: `users` (untuk login admin & super admin), `perangkat` (data barang), dan `log_status` (untuk mencatat riwayat pergerakan alat).
- Setup koneksi database ke dalam file konfigurasi (misal: `koneksi.php`).

### Tahap 2: Autentikasi (Login System)
- Buat halaman login sederhana namun rapi.
- Implementasikan logika login menggunakan session. Pisahkan akses antara **Super Admin** dan **Admin**.
- Pastikan semua halaman sistem diproteksi sehingga pengguna yang belum login tidak bisa mengaksesnya.

### Tahap 3: Pembuatan Dashboard
- Buat halaman `index.php` (setelah login) yang menjadi Dashboard Utama.
- Buat *query* untuk menghitung jumlah masing-masing status perangkat (Pengajuan, Belanja, Gudang, Praktek, Rusak).
- Tampilkan data tersebut menggunakan *card* atau desain UI yang ringan dan informatif.

### Tahap 4: Modul Manajemen Perangkat (CRUD & Status)
- Buat antarmuka (UI) untuk menampilkan daftar alat dalam bentuk tabel.
- Buat fitur untuk:
  - Menambah alat baru (default status: 'Pengajuan' atau 'Gudang').
  - Mengubah data dan status alat.
  - Memperbarui status alat mengikuti alur: `Pengajuan` -> `Belanja` -> `Gudang` -> `Praktek` -> `Rusak/Hapus`.
- Pastikan pencarian dan penomoran halaman (pagination) berjalan dengan cepat.

### Tahap 5: Modul Laporan
- Buat halaman khusus untuk laporan.
- Tambahkan filter berdasarkan rentang waktu (Bulan dan Semester).
- Buat tampilan tabel laporan yang siap di-print atau diubah menjadi PDF/Excel.

## 5. Catatan Tambahan (Guidelines)
- **Kinerja**: Jangan gunakan framework yang terlalu berat jika tidak diperlukan. Gunakan Vanilla PHP (PHP Native) yang terstruktur, atau micro-framework jika diizinkan, agar sistem berjalan sangat cepat.
- **UI/UX**: Gunakan framework CSS (seperti Bootstrap atau Tailwind) untuk mempercepat pembuatan antarmuka dan memastikannya responsif, bersih, dan modern.
- **Keamanan**: Selalu gunakan *Prepared Statements* (PDO/MySQLi) saat berinteraksi dengan database untuk mencegah *SQL Injection*.
- **Kode**: Tulis komentar singkat pada fungsi-fungsi yang krusial agar mudah dibaca di kemudian hari.
