<?php 
session_start();
require_once __DIR__ . "/../Backend/koneksi.php";

// --- 1. KEAMANAN HALAMAN (CEK LOGIN) ---
// Jika session username belum ada, tendang ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Opsional: Jika ingin halaman ini KHUSUS untuk role 'user' saja:
// if ($_SESSION['role'] !== 'user') {
//     echo "Akses ditolak.";
//     exit();
// }

$username = $_SESSION['username'];

// --- 2. AMBIL DATA FILM TERBARU ---
$query_terbaru = "SELECT * FROM film ORDER BY id_film DESC LIMIT 5";
$result_terbaru = mysqli_query($conn, $query_terbaru);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeknikTix - Home</title>
    
    <link rel="stylesheet" href="Style/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <h2>TekCinema</h2>
        <div class="nav-container">
            <ul class="nav-links">
                <div class="Menu">
                    <li><a href="landingpage.php" class="active">Home</a></li>
                    <li><a href="Jadwal_film.php">Jadwal Film</a></li>
                    <li><a href="user/favorit.php">Rating Film</a></li>
                    <li><a href="user/pesanan.php">Pesanan Saya</a></li>
                </div>
            </ul>
        </div>
        <div class="header-right">
            <div class="user-info">
                <i class="fa-solid fa-user"></i> <span><?= htmlspecialchars($username) ?></span>
            </div>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="hero-wrapper">
        <section class="hero-section">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
                <p>Selamat Datang di Web Pemesanan Tiket TeknikTix. Nikmati kemudahan pengecekan jadwal film, pemesanan tiket bioskop, dan promo menarik khusus untuk member setia kami.</p>
                <div class="hero-badge">
                    <div class="circle-logo">
                        <h3>TeTix</h3>
                        <i class="fa-solid fa-clapperboard"></i>
                        <p>BIOSKOP TIKET</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="recommendation-section">
            <div class="section-header">
                <h2>Recommended Film</h2>
                <a href="Jadwal_film.php" class="view-all">Lihat Semua &rarr;</a>
            </div>

            <div class="movie-grid">
                <?php 
                if ($result_terbaru && mysqli_num_rows($result_terbaru) > 0) {
                    while($row = mysqli_fetch_assoc($result_terbaru)) { 
                        // Pastikan kolom DB: gambar, judul_film, rating, durasi
                        $gambar = $row['gambar']; 
                        $judul = $row['judul_film'];
                        $rating = isset($row['rating']) ? $row['rating'] : 'N/A';
                        $durasi = isset($row['durasi']) ? $row['durasi'] : '-'; 
                ?>
                    <div class="movie-card">
                        <div class="card-image">
                            <div class="rating-badge">
                                <i class="fa-solid fa-star"></i> <?= $rating ?>
                            </div>
                            <img src="Assets/<?= $gambar ?>" alt="<?= $judul ?>">
                        </div>
                        <div class="card-content">
                            <h3><?= $judul ?></h3>
                            <div class="card-tags">
                                <span class="tag">2D</span>
                                <span class="tag"><?= $durasi ?></span>
                            </div>
                        </div>
                    </div>
                <?php 
                    } 
                } else {
                    echo "<p style='color:white; text-align:center; width:100%;'>Belum ada data film terbaru.</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <div id="popup" class="popup"></div>
</body>
</html>