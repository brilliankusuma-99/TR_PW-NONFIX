<?php
session_start();
require_once __DIR__ . "/../../Backend/koneksi.php"; 

// --- BAGIAN API (BACKEND) ---
if (isset($_GET['action'])) {
    header("Content-Type: application/json; charset=UTF-8");
    $input = json_decode(file_get_contents("php://input"), true);
    
    if ($_GET['action'] == 'read') {
        $sql = "SELECT * FROM favorites ORDER BY id DESC";
        $result = $conn->query($sql);
        $movies = [];
        while($row = $result->fetch_assoc()) { $movies[] = $row; }
        echo json_encode($movies);
        exit();
    }

    if ($_GET['action'] == 'create' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $imdbID = $input['imdbID'];
        $check = $conn->query("SELECT id FROM favorites WHERE imdbID = '$imdbID'");
        if($check->num_rows > 0) {
            echo json_encode(["message" => "Film sudah ada di favorit!"]);
        } else {
            $stmt = $conn->prepare("INSERT INTO favorites (title, poster_url, rating, imdbID) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $input['title'], $input['poster'], $input['rating'], $input['imdbID']);
            if($stmt->execute()) echo json_encode(["message" => "Berhasil disimpan!"]);
            else echo json_encode(["error" => "Gagal menyimpan."]);
        }
        exit();
    }

    if ($_GET['action'] == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $conn->prepare("UPDATE favorites SET rating = ? WHERE id = ?");
        $stmt->bind_param("si", $input['rating'], $input['id']);
        if($stmt->execute()) echo json_encode(["message" => "Rating berhasil diupdate!"]);
        else echo json_encode(["error" => "Gagal update."]);
        exit();
    }

    if ($_GET['action'] == 'delete') {
        $id = $_GET['id'];
        $conn->query("DELETE FROM favorites WHERE id = $id");
        echo json_encode(["message" => "Film dihapus."]);
        exit();
    }
}

// --- BAGIAN FRONTEND ---
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeknikTix - Favorite Collection</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="../Style/style.css"> 

    <style>
        /* CSS Tambahan khusus halaman ini untuk merapikan layout */
        body {
            background-color: #0f4c9c; /* Warna background gelap */
            color: white;
            font-family: 'Poppins', sans-serif;
            margin-top: 100px; /* Memberi jarak agar tidak tertutup Navbar Fixed */
        }

        /* Styling Kartu Film */
        .movie-card-fav {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
            cursor: pointer;
            background: #16213e;
        }
        .movie-card-fav:hover {
            transform: scale(1.03);
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.5);
        }
        .movie-poster-fav {
            width: 100%;
            height: 320px;
            object-fit: cover;
        }

        /* Overlay Tombol (Edit/Hapus) */
        .action-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.8);
            display: none; /* Sembunyi default */
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .movie-card-fav:hover .action-overlay {
            display: flex; /* Muncul saat hover */
        }

        /* Navbar Override (Agar sesuai tema TekCinema) */
        header {
            position: fixed;
            top: 0; left: 0; width: 100%;
            background-color: #0f4c9c;
            z-index: 1000;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .header-left h2 { 
            font-size: 24px;
            font-weight: 800;; }
        
        .nav-links { display: flex; gap: 20px; list-style: none; margin: 0; padding: 0; }
        .nav-links a { color: white; text-decoration: none; font-weight: 500; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { color: #e50914; }

        .header-right { display: flex; align-items: center; gap: 20px; }
        .btn-logout { 
            background: #e50914; color: white; padding: 8px 20px; 
            border-radius: 5px; text-decoration: none; font-size: 14px; 
        }
    </style>
</head>
<body>

    <header>
        <div class="header-left">
            <h2>TekCinema</h2>
        </div>
        
        <ul class="nav-links">
            <li><a href="../landingpage.php">Home</a></li>
            <li><a href="../Jadwal_film.php">Jadwal Film</a></li>
            <li><a href="favorit.php" class="active">Favorite</a></li>
            <li><a href="rating.php">Rating Film</a></li>
            <li><a href="pesanan.php">Pesanan Saya</a></li>
        </ul>

        <div class="header-right">
            <div class="user-info">
                <i class="fa-solid fa-user"></i> <span><?= htmlspecialchars($username) ?></span>
            </div>
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <div class="container pb-5">
        <div class="row align-items-center mb-5 p-4 rounded" style="background: rgba(255,255,255,0.05);">
            <div class="col-md-6">
                <h2 class="fw-bold mb-1">Koleksi Favorit Saya</h2>
                <p class="text-white-50 m-0">Simpan film favoritmu dari OMDb Database disini.</p>
            </div>
            <div class="col-md-6 d-flex gap-2">
                <input type="text" id="searchInput" class="form-control bg-dark text-white border-secondary" placeholder="Cari judul film (Contoh: Avengers)...">
                <button class="btn btn-primary" onclick="searchOMDb()">Cari</button>
                <button class="btn btn-outline-light" onclick="loadFavorites()">Koleksiku</button>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4" id="movieContainer">
            <div class="text-center w-100 mt-5">
                <div class="spinner-border text-danger" role="status"></div>
                <p>Memuat data...</p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="movieDetailModal" tabindex="-1" style="color: black;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: #1f1f1f; color: white;">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold" id="modalDetailTitle">Detail Film</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="modalDetailPoster" src="" class="img-fluid rounded shadow" style="max-height: 400px;">
                        </div>
                        <div class="col-md-8">
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Rilis:</strong> <span id="modalReleased">-</span></li>
                                <li class="mb-2"><strong>Durasi:</strong> <span id="modalRuntime">-</span></li>
                                <li class="mb-2"><strong>Genre:</strong> <span id="modalGenre">-</span></li>
                                <li class="mb-2"><strong>Rating IMDB:</strong> <span class="text-warning">★ <span id="modalImdbRating">0</span></span></li>
                            </ul>
                            <hr class="border-secondary">
                            <h6 class="text-warning fw-bold">Sinopsis:</h6>
                            <p id="modalPlot" class="small" style="line-height: 1.6;">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" style="color: black;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Edit Rating Pribadi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Judul Film</label>
                        <input type="text" id="editTitle" class="form-control bg-secondary text-white border-0" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating Kamu (1-10)</label>
                        <input type="number" id="editRating" class="form-control" min="1" max="10">
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="saveEdit()">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_KEY = 'd9ec1408'; // API KEY OMDb
        const CURRENT_FILE = 'favorit.php'; 

        const movieContainer = document.getElementById('movieContainer');
        const detailModal = new bootstrap.Modal(document.getElementById('movieDetailModal'));
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));

        // 1. LOAD FAVORITE (Dari Database Lokal)
        async function loadFavorites() {
            document.getElementById('searchInput').value = ""; 
            movieContainer.innerHTML = '<div class="text-center w-100 mt-5"><div class="spinner-border text-light"></div></div>';
            
            try {
                const res = await fetch(`${CURRENT_FILE}?action=read`);
                const movies = await res.json();
                
                movieContainer.innerHTML = '';
                if(movies.length === 0) {
                    movieContainer.innerHTML = '<div class="text-center w-100 mt-5"><i class="fa-solid fa-film fa-3x mb-3 text-secondary"></i><p>Belum ada koleksi film.</p></div>';
                    return;
                }

                movies.forEach(m => renderCard(m, true));
            } catch (error) {
                console.error(error);
                movieContainer.innerHTML = '<p class="text-center text-danger w-100">Gagal memuat data.</p>';
            }
        }

        // 2. SEARCH OMDb
        async function searchOMDb() {
            const query = document.getElementById('searchInput').value;
            if(!query) return alert("Ketik judul film!");
            
            movieContainer.innerHTML = '<div class="text-center w-100 mt-5"><div class="spinner-border text-light"></div></div>';

            try {
                const res = await fetch(`https://www.omdbapi.com/?s=${query}&apikey=${API_KEY}`);
                const data = await res.json();

                movieContainer.innerHTML = '';
                if(data.Response === "True") {
                    data.Search.forEach(m => {
                        const movieObj = {
                            id: null,
                            title: m.Title,
                            poster_url: m.Poster !== "N/A" ? m.Poster : "https://via.placeholder.com/300x450?text=No+Image",
                            rating: '?',
                            imdbID: m.imdbID
                        };
                        renderCard(movieObj, false);
                    });
                } else {
                    movieContainer.innerHTML = '<p class="text-center w-100 mt-5">Film tidak ditemukan.</p>';
                }
            } catch (err) { alert("Gagal koneksi internet."); }
        }

        // RENDER KARTU FILM
        function renderCard(m, isFavorite) {
            const safeTitle = m.title.replace(/'/g, "");
            let buttons = '';

            if (isFavorite) {
                buttons = `
                    <button class="btn btn-warning btn-sm w-75 mb-2" onclick="openEdit(${m.id}, '${safeTitle}', '${m.rating}')">
                        <i class="fa-solid fa-pen"></i> Edit Rating
                    </button>
                    <button class="btn btn-danger btn-sm w-75" onclick="deleteFav(${m.id})">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                `;
            } else {
                buttons = `
                    <button class="btn btn-success btn-sm w-75" onclick="addFav('${safeTitle}', '${m.poster_url}', '${m.imdbID}')">
                        <i class="fa-solid fa-heart"></i> Simpan
                    </button>
                `;
            }

            const html = `
                <div class="col">
                    <div class="movie-card-fav h-100">
                        <img src="${m.poster_url}" class="movie-poster-fav" onclick="showDetail('${safeTitle}')">
                        <div class="action-overlay">${buttons}</div>
                        <div class="p-3 text-center">
                             <h6 class="fw-bold text-white text-truncate" title="${m.title}">${m.title}</h6>
                             ${isFavorite ? `<small class="text-warning"><i class="fa-solid fa-star"></i> ${m.rating}</small>` : ''}
                        </div>
                    </div>
                </div>`;
            movieContainer.innerHTML += html;
        }

        // FUNGSI CRUD (Create, Update, Delete)
        async function addFav(title, poster, imdbID) {
            const res = await fetch(`${CURRENT_FILE}?action=create`, {
                method: 'POST',
                body: JSON.stringify({ title, poster, rating: "8.0", imdbID })
            });
            const result = await res.json();
            alert(result.message);
        }

        function openEdit(id, title, rating) {
            document.getElementById('editId').value = id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editRating').value = rating;
            editModal.show();
        }

        async function saveEdit() {
            const id = document.getElementById('editId').value;
            const rating = document.getElementById('editRating').value;
            const res = await fetch(`${CURRENT_FILE}?action=update`, {
                method: 'POST',
                body: JSON.stringify({ id, rating })
            });
            alert((await res.json()).message);
            editModal.hide();
            loadFavorites();
        }

        async function deleteFav(id) {
            if(confirm("Hapus dari favorit?")) {
                await fetch(`${CURRENT_FILE}?action=delete&id=${id}`);
                loadFavorites();
            }
        }

        // SHOW DETAIL
        async function showDetail(title) {
            detailModal.show();
            // Reset konten lama
            document.getElementById('modalDetailPoster').src = "https://via.placeholder.com/300x450?text=Loading...";
            
            const res = await fetch(`https://www.omdbapi.com/?t=${encodeURIComponent(title)}&apikey=${API_KEY}`);
            const data = await res.json();
            
            if(data.Response === "True") {
                document.getElementById('modalDetailTitle').innerText = data.Title;
                document.getElementById('modalDetailPoster').src = data.Poster !== "N/A" ? data.Poster : "https://via.placeholder.com/300x450";
                document.getElementById('modalReleased').innerText = data.Released;
                document.getElementById('modalRuntime').innerText = data.Runtime;
                document.getElementById('modalGenre').innerText = data.Genre;
                document.getElementById('modalImdbRating').innerText = data.imdbRating;
                document.getElementById('modalPlot').innerText = data.Plot;
            }
        }

        // Jalankan saat pertama load
        loadFavorites();
    </script>
</body>
</html>