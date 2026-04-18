<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$laporan = [];
$filter_type = $_GET['type'] ?? 'bulanan';
$selected_month = $_GET['bulan'] ?? date('m');
$selected_year = $_GET['tahun'] ?? date('Y');
$selected_semester = $_GET['semester'] ?? '1'; // 1: Ganjil, 2: Genap

try {
    if ($filter_type == 'bulanan') {
        $stmt = $pdo->prepare("SELECT * FROM perangkat WHERE MONTH(created_at) = ? AND YEAR(created_at) = ? ORDER BY created_at DESC");
        $stmt->execute([$selected_month, $selected_year]);
    } else {
        // Semester
        if ($selected_semester == '1') {
            // Ganjil: Juli (7) - Desember (12)
            $stmt = $pdo->prepare("SELECT * FROM perangkat WHERE MONTH(created_at) BETWEEN 7 AND 12 AND YEAR(created_at) = ? ORDER BY created_at DESC");
        } else {
            // Genap: Januari (1) - Juni (6)
            $stmt = $pdo->prepare("SELECT * FROM perangkat WHERE MONTH(created_at) BETWEEN 1 AND 6 AND YEAR(created_at) = ? ORDER BY created_at DESC");
        }
        $stmt->execute([$selected_year]);
    }
    $laporan = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni',
    '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris - Lab TKJ</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #6366f1; --bg: #f8fafc; --sidebar: #1e293b; --text-dark: #1e293b; --text-light: #64748b; --white: #ffffff; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); display: flex; margin: 0; }
        
        .sidebar { width: 260px; background-color: var(--sidebar); height: 100vh; position: fixed; color: white; padding: 1.5rem; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 2rem; }
        
        .filter-card { background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; }
        .filter-group { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .form-control { display: flex; flex-direction: column; gap: 5px; }
        select, input { padding: 0.6rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: inherit; }
        
        .btn { padding: 0.6rem 1.2rem; border-radius: 0.5rem; border: none; font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-outline { border: 1px solid var(--primary); color: var(--primary); background: transparent; }
        
        .report-table { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th { text-align: left; padding: 1rem; border-bottom: 2px solid #f1f5f9; color: var(--text-light); font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }

        @media print {
            .sidebar, .filter-card, .btn { display: none !important; }
            .main-content { margin-left: 0; width: 100%; padding: 0; }
            body { background: white; }
            .report-table { box-shadow: none; border: none; }
            .print-header { display: block !important; text-align: center; margin-bottom: 2rem; }
        }
        .print-header { display: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="font-family: 'Outfit'; margin-bottom: 2rem;">Lab TKJ</h2>
        <ul style="list-style: none;">
            <li style="margin-bottom: 0.5rem;"><a href="dashboard.php" style="color: #94a3b8; text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 0.8rem;"><i class="fas fa-home"></i> Dashboard</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="inventaris.php" style="color: #94a3b8; text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 0.8rem;"><i class="fas fa-box"></i> Inventaris</a></li>
            <li style="margin-bottom: 0.5rem;"><a href="laporan.php" style="color: white; background: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 0.8rem; border-radius: 0.75rem;"><i class="fas fa-file-alt"></i> Laporan</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="print-header">
            <h1 style="font-family: 'Outfit';">LAPORAN INVENTARIS LAB TKJ</h1>
            <p>Periode: <?php echo $filter_type == 'bulanan' ? $nama_bulan[$selected_month] . " " . $selected_year : "Semester " . ($selected_semester == '1' ? 'Ganjil' : 'Genap') . " " . $selected_year; ?></p>
            <hr style="margin: 1rem 0;">
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h1 style="font-family: 'Outfit'; font-size: 1.5rem;">Laporan Perangkat</h1>
            <button onclick="window.print()" class="btn btn-outline"><i class="fas fa-print"></i> Cetak Laporan</button>
        </div>

        <div class="filter-card">
            <form method="GET" class="filter-group">
                <div class="form-control">
                    <label>Jenis Laporan</label>
                    <select name="type" onchange="this.form.submit()">
                        <option value="bulanan" <?php echo $filter_type == 'bulanan' ? 'selected' : ''; ?>>Bulanan</option>
                        <option value="semester" <?php echo $filter_type == 'semester' ? 'selected' : ''; ?>>Per Semester</option>
                    </select>
                </div>

                <?php if ($filter_type == 'bulanan'): ?>
                <div class="form-control">
                    <label>Bulan</label>
                    <select name="bulan">
                        <?php foreach ($nama_bulan as $m => $nama): ?>
                            <option value="<?php echo $m; ?>" <?php echo $selected_month == $m ? 'selected' : ''; ?>><?php echo $nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else: ?>
                <div class="form-control">
                    <label>Semester</label>
                    <select name="semester">
                        <option value="1" <?php echo $selected_semester == '1' ? 'selected' : ''; ?>>Ganjil (Jul - Des)</option>
                        <option value="2" <?php echo $selected_semester == '2' ? 'selected' : ''; ?>>Genap (Jan - Jun)</option>
                    </select>
                </div>
                <?php endif; ?>

                <div class="form-control">
                    <label>Tahun</label>
                    <input type="number" name="tahun" value="<?php echo $selected_year; ?>" style="width: 100px;">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            </form>
        </div>

        <div class="report-table">
            <h3>Daftar Perangkat Periode Ini</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Masuk</th>
                        <th>Kode</th>
                        <th>Nama Perangkat</th>
                        <th>Status Terakhir</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporan)): ?>
                        <tr><td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-light);">Tidak ada data untuk periode ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($laporan as $row): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['kode_alat']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_alat']); ?></td>
                            <td style="text-transform: capitalize;"><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['lokasi']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
