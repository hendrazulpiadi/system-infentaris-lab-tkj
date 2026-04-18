-- Database: db_inventaris_tkj
CREATE DATABASE IF NOT EXISTS db_inventaris_tkj;
USE db_inventaris_tkj;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin', 'admin') NOT NULL DEFAULT 'admin',
    nama_lengkap VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table: perangkat
CREATE TABLE IF NOT EXISTS perangkat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_alat VARCHAR(150) NOT NULL,
    kode_alat VARCHAR(50) UNIQUE,
    deskripsi TEXT,
    status ENUM('pengajuan', 'belanja', 'gudang', 'praktek', 'rusak') NOT NULL DEFAULT 'pengajuan',
    lokasi VARCHAR(100) DEFAULT 'Gudang',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table: log_status
CREATE TABLE IF NOT EXISTS log_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_perangkat INT NOT NULL,
    status_lama VARCHAR(20),
    status_baru VARCHAR(20),
    keterangan TEXT,
    id_user INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_perangkat) REFERENCES perangkat(id) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id)
) ENGINE=InnoDB;

-- Insert initial data (Superadmin: admin, Password: admin)
-- Password hashed using password_hash('admin', PASSWORD_DEFAULT)
INSERT INTO users (username, password, role, nama_lengkap) 
VALUES ('admin', '$2y$10$oUBJshcenM/9POKsnZ96Jei167QAdTpTA0cpzbifIuL9pKVFV8E5y', 'superadmin', 'Administrator Lab');
