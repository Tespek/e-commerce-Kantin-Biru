<?php
// Konfigurasi Database
$host = "localhost";
$user = "root";      // Default user Laragon/XAMPP
$pass = "";          // Default password Laragon/XAMPP biasanya kosong
$db   = "kantin_biru"; // Nama database Anda

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Opsional: Untuk debugging, bisa hapus baris di bawah setelah koneksi berhasil
// echo "Koneksi berhasil!"; 
?>