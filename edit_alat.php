<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: inventaris.php"); exit(); }

// Ambil data lama
$stmt = $pdo->prepare("SELECT * FROM perangkat WHERE id = ?");
$stmt->execute([$id]);
$perangkat = $stmt->fetch();

if (!$perangkat) { header("Location: inventaris.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_alat'];
    $status_baru = $_POST['status'];
    $lokasi = $_POST['lokasi'];
    $status_lama = $perangkat['status'];

    try {
        $pdo->beginTransaction();

        // 1. Update data perangkat
        $stmt = $pdo->prepare("UPDATE perangkat SET nama_alat = ?, status = ?, lokasi = ? WHERE id = ?");
        $stmt->execute([$nama, $status_baru, $lokasi, $id]);

        // 2. Catat di log_status jika status berubah
        if ($status_baru !== $status_lama) {
            $stmt = $pdo->prepare("INSERT INTO log_status (id_perangkat, status_lama, status_baru, id_user, keterangan) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id, $status_lama, $status_baru, $_SESSION['user_id'], "Perubahan status manual oleh admin"]);
        }

        $pdo->commit();
        header("Location: inventaris.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Alat - Lab TKJ</title>
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
        input, select { width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; }
        .btn-save { background: var(--primary); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="font-family: 'Outfit'; margin-bottom: 2rem;">Lab TKJ</h2>
        <a href="inventaris.php" style="color: white; text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="main-content">
        <h1 style="font-family: 'Outfit'; margin-bottom: 1.5rem;">Update Status & Data Alat</h1>
        <div class="form-card">
            <form method="POST">
                <div class="form-group">
                    <label>Kode Alat (Read Only)</label>
                    <input type="text" value="<?php echo htmlspecialchars($perangkat['kode_alat']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Nama Perangkat</label>
                    <input type="text" name="nama_alat" value="<?php echo htmlspecialchars($perangkat['nama_alat']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Status Saat Ini</label>
                    <select name="status">
                        <option value="pengajuan" <?php echo $perangkat['status'] == 'pengajuan' ? 'selected' : ''; ?>>Pengajuan</option>
                        <option value="belanja" <?php echo $perangkat['status'] == 'belanja' ? 'selected' : ''; ?>>Proses Belanja</option>
                        <option value="gudang" <?php echo $perangkat['status'] == 'gudang' ? 'selected' : ''; ?>>Di Gudang</option>
                        <option value="praktek" <?php echo $perangkat['status'] == 'praktek' ? 'selected' : ''; ?>>Dipakai Praktek</option>
                        <option value="rusak" <?php echo $perangkat['status'] == 'rusak' ? 'selected' : ''; ?>>Rusak / Dihapus</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" value="<?php echo htmlspecialchars($perangkat['lokasi']); ?>">
                </div>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
