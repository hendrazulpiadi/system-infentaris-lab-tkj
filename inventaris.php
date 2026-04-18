<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data inventaris
try {
    $stmt = $pdo->query("SELECT * FROM perangkat ORDER BY created_at DESC");
    $inventaris = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}

// Helper badge status
function getStatusBadge($status) {
    $colors = [
        'pengajuan' => '#3b82f6', // blue
        'belanja' => '#eab308',   // yellow
        'gudang' => '#22c55e',    // green
        'praktek' => '#6366f1',   // indigo
        'rusak' => '#ef4444'      // red
    ];
    $color = $colors[$status] ?? '#64748b';
    return "<span style='background-color: {$color}; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize;'>{$status}</span>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris - Lab TKJ</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
            --sidebar: #1e293b;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); color: var(--text-dark); display: flex; }

        /* Sidebar reuse */
        .sidebar { width: 260px; background-color: var(--sidebar); height: 100vh; position: fixed; color: white; padding: 1.5rem; display: flex; flex-direction: column; }
        .sidebar-header { display: flex; align-items: center; gap: 10px; margin-bottom: 2.5rem; }
        .sidebar-header i { font-size: 1.5rem; color: var(--primary); }
        .sidebar-header h2 { font-family: 'Outfit', sans-serif; font-size: 1.25rem; }
        .nav-menu { list-style: none; flex-grow: 1; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 0.8rem 1rem; color: #94a3b8; text-decoration: none; border-radius: 0.75rem; transition: all 0.2s; }
        .nav-link:hover, .nav-link.active { background-color: rgba(255, 255, 255, 0.1); color: white; }
        .nav-link.active { background-color: var(--primary); }
        .sidebar-footer { padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1); }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 2rem; }
        .header-main { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header-main h1 { font-family: 'Outfit', sans-serif; font-size: 1.5rem; }

        /* Table Style */
        .table-container { background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #f1f5f9; }
        .btn-add { background: var(--primary); color: white; padding: 0.6rem 1.2rem; border-radius: 0.75rem; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-add:hover { background: #4f46e5; transform: translateY(-1px); }

        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        th { text-align: left; padding: 1rem; border-bottom: 2px solid #f1f5f9; color: var(--text-light); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        tr:hover { background-color: #f8fafc; }

        .action-btns { display: flex; gap: 8px; }
        .btn-icon { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; }
        .btn-edit { background: #e0e7ff; color: #4338ca; }
        .btn-delete { background: #fee2e2; color: #dc2626; }
        .btn-icon:hover { transform: scale(1.1); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><i class="fas fa-microchip"></i><h2>Lab TKJ</h2></div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="nav-item"><a href="inventaris.php" class="nav-link active"><i class="fas fa-box"></i> Inventaris</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-history"></i> Riwayat Alur</a></li>
            <li class="nav-item"><a href="laporan.php" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a></li>
        </ul>
        <div class="sidebar-footer"><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Keluar</a></div>
    </div>

    <div class="main-content">
        <div class="header-main">
            <h1>Daftar Inventaris</h1>
            <a href="tambah_alat.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Alat Baru</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Perangkat</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inventaris)): ?>
                        <tr><td colspan="5" style="text-align: center; color: var(--text-light);">Belum ada data perangkat.</td></tr>
                    <?php else: ?>
                        <?php foreach ($inventaris as $item): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--primary);"><?php echo htmlspecialchars($item['kode_alat']); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($item['nama_alat']); ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-light);"><?php echo htmlspecialchars($item['deskripsi']); ?></div>
                            </td>
                            <td><?php echo getStatusBadge($item['status']); ?></td>
                            <td><?php echo htmlspecialchars($item['lokasi']); ?></td>
                            <td>
                                <div class="action-btns">
                                    <a href="edit_alat.php?id=<?php echo $item['id']; ?>" class="btn-icon btn-edit" title="Edit/Update Status"><i class="fas fa-edit"></i></a>
                                    <a href="hapus_alat.php?id=<?php echo $item['id']; ?>" class="btn-icon btn-delete" onclick="return confirm('Hapus perangkat ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
