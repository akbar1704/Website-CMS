<?php
include '../connection.php'; // Include the connection file

// Initialize category filter
$category_filter = "";
$category_name = "";
if (isset($_GET['kategori'])) {
    $category_id = intval($_GET['kategori']);
    $category_filter = "WHERE artikel.id_kategori = $category_id";

    // Query to get the category name
    $category_name_query = "SELECT nama_kategori FROM kategori WHERE id_kategori = $category_id";
    $category_name_result = $conn->query($category_name_query);
    if ($category_name_result->num_rows > 0) {
        $category_name_row = $category_name_result->fetch_assoc();
        $category_name = $category_name_row['nama_kategori'];
    } else {
        $category_name = "Kategori Tidak Ditemukan";
    }
}

// Query to get articles
$query = "SELECT artikel.*, kategori.nama_kategori 
          FROM artikel 
          JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
          $category_filter
          ORDER BY artikel.tanggal DESC";
$result = $conn->query($query);

// Query to get categories
$category_query = "SELECT * FROM kategori";
$category_result = $conn->query($category_query);

// Check if there are articles in the selected category
$no_articles = $result->num_rows === 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Home</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
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
    <!-- Page header with logo and tagline-->
    <header class="py-5 bg-light border-bottom mb-4">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder">Selamat Datang di Website Kami!</h1>
                <p class="lead mb-0">Website Seputar Sepeda</p>
            </div>
        </div>
    </header>
    <!-- Page content-->
    <div class="container">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8">
                <?php if ($no_articles): ?>
                    <div class="alert alert-warning" role="alert">
                        Artikel dengan kategori "<?= htmlspecialchars($category_name) ?>" tidak ditemukan.
                    </div>
                <?php else: ?>
                    <?php
                    $featured = true;
                    $post_counter = 0;
                    while ($row = $result->fetch_assoc()):
                        if ($featured): ?>
                            <!-- Featured blog post-->
                            <div class="card mb-4">
                                <a href="blogpost.php?id=<?= $row['id_artikel'] ?>"><img class="card-img-top"
                                        src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" alt="..." width="850"
                                        height="350" /></a>
                                <div class="card-body">
                                    <div class="small text-muted"><?= ($row['tanggal']) ?></div>
                                    <h2 class="card-title"><?= $row['judul'] ?></h2>
                                    <p class="card-text"><?= substr($row['isi'], 0, 200) ?>...</p>
                                    <a class="btn btn-primary" href="blogpost.php?id=<?= $row['id_artikel'] ?>">Read more →</a>
                                </div>
                            </div>
                            <!-- Nested row for non-featured blog posts-->
                            <div class="row">
                                <?php
                                $featured = false;
                        else: ?>
                                <div class="col-lg-6">
                                    <!-- Blog post-->
                                    <div class="card mb-4">
                                        <a href="blogpost.php?id=<?= $row['id_artikel'] ?>"><img class="card-img-top"
                                                src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" alt="..."
                                                width="700" height="350" /></a>
                                        <div class="card-body">
                                            <div class="small text-muted"><?= ($row['tanggal']) ?></div>
                                            <h2 class="card-title h4"><?= $row['judul'] ?></h2>
                                            <p class="card-text"><?= substr($row['isi'], 0, 100) ?>...</p>
                                            <a class="btn btn-primary" href="blogpost.php?id=<?= $row['id_artikel'] ?>">Read
                                                more →</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $post_counter++;
                                if ($post_counter % 2 == 0):
                                    echo '</div><div class="row">';
                                endif;
                        endif;
                    endwhile;
                    if ($post_counter % 2 != 0):
                        echo '</div>'; // Close the last row if it's not closed
                    endif;
                    ?>
                    <?php endif; ?>
                </div> <!-- Close the main blog entries column -->
                <!-- Pagination-->
                <nav aria-label="Pagination">
                    <hr class="my-0" />
                    <ul class="pagination justify-content-center my-4">
                        <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"
                                aria-disabled="true">Newer</a></li>
                        <li class="page-item active" aria-current="page"><a class="page-link" href="#!">1</a></li>
                        <li class="page-item"><a class="page-link" href="#!">2</a></li>
                        <li class="page-item"><a class="page-link" href="#!">3</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
                        <li class="page-item"><a class="page-link" href="#!">15</a></li>
                        <li class="page-item"><a class="page-link" href="#!">Older</a></li>
                    </ul>
                </nav>
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
                <!-- Categories widget-->
                <div class="card mb-4">
                    <div class="card-header">Categories</div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            // Initialize a variable to store all categories
                            $categories_html = '';

                            while ($category = $category_result->fetch_assoc()) {
                                // Append each category to the variable
                                $categories_html .= '<li><a href="?kategori=' . $category['id_kategori'] . '">' . $category['nama_kategori'] . '</a></li>';
                            }
                            ?>
                            <div class="col-sm-12">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="bloghome.php">Semua</a></li>
                                    <?= $categories_html ?>
                                </ul>
                            </div>
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
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
</body>

</html>