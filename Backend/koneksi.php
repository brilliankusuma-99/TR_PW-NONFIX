<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_tr"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    $koneksi_berhasil = false;
    $success = false; 
} else {
    $koneksi_berhasil = true;
    $success = true; 
}
?>