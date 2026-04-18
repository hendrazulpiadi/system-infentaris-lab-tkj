<?php
// Konfigurasi Database
$host = "localhost";
$dbname = "db_inventaris_tkj";
$username = "root";
$password = "@Mongsidialok01"; // Password dari user

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set mode error PDO ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode ke associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Jika koneksi gagal
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>
