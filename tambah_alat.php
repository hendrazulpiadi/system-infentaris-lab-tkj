<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_alat'];
    $kode = $_POST['kode_alat'];
    $desc = $_POST['deskripsi'];
    $status = $_POST['status'];
    $lokasi = $_POST['lokasi'];

    try {
        $stmt = $pdo->prepare("INSERT INTO perangkat (nama_alat, kode_alat, deskripsi, status, lokasi) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $kode, $desc, $status, $lokasi]);
        
        header("Location: inventaris.php");
        exit();
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Alat - Lab TKJ</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #6366f1; --bg: #f8fafc; --sidebar: #1e293b; --text-dark: #1e293b; --text-light: #64748b; --white: #ffffff; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); display: flex; }
        .sidebar { width: 260px; background-color: var(--sidebar); height: 100vh; position: fixed; color: white; padding: 1.5rem; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 2rem; }
        .form-card { background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); max-width: 600px; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: 500; color: var(--text-light); }
        input, select, textarea { width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: inherit; }
        .btn-save { background: var(--primary); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="font-family: 'Outfit'; margin-bottom: 2rem;">Lab TKJ</h2>
        <a href="inventaris.php" style="color: white; text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="main-content">
        <h1 style="font-family: 'Outfit'; margin-bottom: 1.5rem;">Tambah Perangkat Baru</h1>
        <?php if ($message): ?><p style="color: red;"><?php echo $message; ?></p><?php endif; ?>
        <div class="form-card">
            <form method="POST">
                <div class="form-group">
                    <label>Kode Alat</label>
                    <input type="text" name="kode_alat" placeholder="Contoh: PC-01" required>
                </div>
                <div class="form-group">
                    <label>Nama Perangkat</label>
                    <input type="text" name="nama_alat" placeholder="Contoh: PC Server" required>
                </div>
                <div class="form-group">
                    <label>Status Awal</label>
                    <select name="status">
                        <option value="pengajuan">Pengajuan</option>
                        <option value="belanja">Proses Belanja</option>
                        <option value="gudang">Di Gudang</option>
                        <option value="praktek">Dipakai Praktek</option>
                        <option value="rusak">Rusak / Dihapus</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" value="Gudang">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3"></textarea>
                </div>
                <button type="submit" class="btn-save">Simpan Perangkat</button>
            </form>
        </div>
    </div>
</body>
</html>
