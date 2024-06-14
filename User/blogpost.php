<?php
include '../connection.php'; // Include the connection file

// Get the article ID from the URL
$id_artikel = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to get the article
$query = "SELECT artikel.*, kategori.nama_kategori 
          FROM artikel 
          JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
          WHERE artikel.id_artikel = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_artikel);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();
$stmt->close();

if (!$article) {
    die("Artikel tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= htmlspecialchars($article['judul']) ?></title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .post-content {
            text-align: justify;
        }

        .card-body {
            text-align: justify;
        }
    </style>
</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="bloghome.php">Seputar Sepeda</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="bloghome.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Page content-->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Post content-->
                <article>
                    <!-- Post header-->
                    <header class="mb-4">
                        <!-- Post title-->
                        <h1 class="fw-bolder mb-1"><?= htmlspecialchars($article['judul']) ?></h1>
                        <!-- Post meta content-->
                        <div class="text-muted fst-italic mb-2">Posted on
                            <?= htmlspecialchars($article['tanggal']) ?> by
                            <?= htmlspecialchars($article['penulis']) ?>
                        </div>
                        <!-- Post categories-->
                        <a class="badge bg-secondary text-decoration-none link-light"
                            href="#!"><?= htmlspecialchars($article['nama_kategori']) ?></a>
                    </header>
                    <!-- Preview image figure-->
                    <figure class="mb-4"><img class="img-fluid rounded"
                            src="data:image/jpeg;base64,<?= base64_encode($article['gambar']) ?>" alt="..." /></figure>
                    <!-- Post content-->
                    <section class="post-content mb-5">
                        <p class="fs-5 mb-4"><?= nl2br(htmlspecialchars($article['isi'])) ?></p>
                    </section>
                </article>
                <!-- Comments section-->
                <section class="mb-5">
                </section>
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Enter search term..."
                                aria-label="Enter search term..." aria-describedby="button-search" />
                            <button class="btn btn-primary" id="button-search" type="button">Go!</button>
                        </div>
                    </div>
                </div>


                <!-- Side widget-->
                <div class="card mb-4">
                    <div class="card-header">About</div>
                    <div class="card-body">Selamat datang di Website Kami, destinasi online terbaik untuk para
                        penggemar sepeda! Kami hadir untuk memberikan informasi, ulasan, dan panduan lengkap seputar
                        dunia sepeda. Baik Anda seorang pemula yang baru saja memulai hobi bersepeda atau seorang
                        profesional yang mencari tips dan trik terbaru, kami memiliki sesuatu untuk Anda.</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>