<?php

// untuk menghubungkan database
$servername = "localhost";
$username = "root";
$password = "";
$db = "db_form_karyawan";

$conn = new mysqli($servername, $username, $password, $db);

// buat fungsi untuk menutup koneksi database
function tutupKoneksi($stmt = null, $conn = null)
{
    // cek apakah parameter statement dan koneksi tidak null
    if ($stmt !== null && $conn !== null) {
        // tutup statement dan koneksi database
        $stmt->close();
        $conn->close();
    }
}
