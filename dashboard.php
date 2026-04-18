<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil statistik data
try {
    // Total Perangkat
    $stmt = $pdo->query("SELECT COUNT(*) FROM perangkat");
    $total_perangkat = $stmt->fetchColumn();

    // Statistik per status
    $stats = [
        'pengajuan' => 0,
        'belanja' => 0,
        'gudang' => 0,
        'praktek' => 0,
        'rusak' => 0
    ];

    $stmt = $pdo->query("SELECT status, COUNT(*) as jumlah FROM perangkat GROUP BY status");
    while ($row = $stmt->fetch()) {
        $stats[$row['status']] = $row['jumlah'];
    }

} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Inventaris Lab TKJ</title>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-dark);
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar);
            height: 100vh;
            position: fixed;
            color: white;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .sidebar-header img {
            width: 80px;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        }

        .sidebar-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            color: white;
        }

        .nav-menu {
            list-style: none;
            flex-grow: 1;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.8rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 0.75rem;
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-link.active {
            background-color: var(--primary);
        }

        .sidebar-footer {
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 2rem;
        }

        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header-main h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            text-align: right;
        }

        .user-info .name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info .role {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f5f9;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .icon-blue { background: #dbeafe; color: #2563eb; }
        .icon-yellow { background: #fef9c3; color: #ca8a04; }
        .icon-green { background: #dcfce7; color: #16a34a; }
        .icon-indigo { background: #e0e7ff; color: #4f46e5; }
        .icon-red { background: #fee2e2; color: #dc2626; }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-light);
            text-transform: capitalize;
        }

        /* Recent Activity / Content area */
        .content-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f5f9;
        }

        .welcome-msg {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-msg h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .welcome-msg p {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-microchip"></i>
            <h2>Lab TKJ</h2>
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard.php" class="nav-link active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="nav-item"><a href="inventaris.php" class="nav-link"><i class="fas fa-box"></i> Inventaris</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-history"></i> Riwayat Alur</a></li>
            <li class="nav-item"><a href="laporan.php" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a></li>
            <?php if ($_SESSION['role'] === 'superadmin'): ?>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-users-cog"></i> Manajemen User</a></li>
            <?php endif; ?>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-main">
            <h1>Ringkasan Data</h1>
            <div class="user-profile">
                <div class="user-info">
                    <div class="name"><?php echo htmlspecialchars($_SESSION['nama']); ?></div>
                    <div class="role"><?php echo ucwords($_SESSION['role']); ?></div>
                </div>
                <div class="stat-icon icon-indigo">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div class="welcome-msg">
            <h2>Halo, <?php echo explode(' ', $_SESSION['nama'])[0]; ?>!</h2>
            <p>Selamat datang kembali di Sistem Inventaris Perangkat Lab TKJ. Berikut adalah ringkasan inventaris hari ini.</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-blue"><i class="fas fa-file-invoice"></i></div>
                </div>
                <div class="stat-value"><?php echo $stats['pengajuan']; ?></div>
                <div class="stat-label">Pengajuan</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-yellow"><i class="fas fa-shopping-cart"></i></div>
                </div>
                <div class="stat-value"><?php echo $stats['belanja']; ?></div>
                <div class="stat-label">Proses Belanja</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-green"><i class="fas fa-warehouse"></i></div>
                </div>
                <div class="stat-value"><?php echo $stats['gudang']; ?></div>
                <div class="stat-label">Di Gudang</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-indigo"><i class="fas fa-tools"></i></div>
                </div>
                <div class="stat-value"><?php echo $stats['praktek']; ?></div>
                <div class="stat-label">Dipakai Praktek</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-red"><i class="fas fa-trash-alt"></i></div>
                </div>
                <div class="stat-value"><?php echo $stats['rusak']; ?></div>
                <div class="stat-label">Rusak / Dihapus</div>
            </div>
        </div>

        <div class="content-card">
            <h3>Total Seluruh Perangkat: <?php echo $total_perangkat; ?></h3>
            <p style="color: var(--text-light); margin-top: 0.5rem;">Gunakan menu di samping untuk mengelola data atau mencetak laporan.</p>
        </div>
    </div>
</body>
</html>
