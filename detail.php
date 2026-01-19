<?php
session_start();
include 'koneksi.php';

$id = mysqli_real_escape_string($conn, $_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");

if(mysqli_num_rows($query) < 1) {
    header("location:index.php");
    exit();
}

$data = mysqli_fetch_array($query);

$rating_tampil = ($data['rating'] > 0) ? $data['rating'] : number_format(mt_rand(30, 50) / 10, 1);
$terjual_tampil = ($data['terjual'] > 0) ? $data['terjual'] : mt_rand(1, 10);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['nama_produk']; ?> - Toys Store Fajar</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex; 
            flex-direction: column;
            min-height: 100vh; 
        }

        .container.mt-4 {
            flex: 1;
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, #028BEF 0%, #00223B 100%);
            padding: 15px 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .search-container {
            border: none; border-radius: 50px; overflow: hidden; background: white; padding: 2px;
        }
        .search-input { border: none; font-size: 14px; }
        .search-input:focus { box-shadow: none; }

        .card-detail {
            border: none; border-radius: 15px; box-shadow: 0 2px 20px rgba(0,0,0,0.05); overflow: hidden;
        }
        .img-detail-wrapper {
            background: white; padding: 20px; text-align: center; border-right: 1px solid #f0f0f0;
        }
        .img-detail {
            max-width: 100%; height: 400px; object-fit: contain;
        }
        .price-tag {
            font-size: 28px; font-weight: 700; color: #028BEF;
        }
        
        .card-product-sm {
            border: none; border-radius: 12px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: 0.2s;
        }
        .card-product-sm:hover { transform: translateY(-5px); }
        .card-img-sm { height: 150px; object-fit: contain; padding: 10px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 me-4" href="index.php"><i class="fas fa-robot me-1"></i> Toys Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="flex-grow-1 me-4">
                    <form action="index.php" method="GET" class="d-flex search-container shadow-sm">
                        <input type="text" name="cari" class="form-control search-input ps-3" placeholder="Cari mainan lain...">
                        <button type="submit" class="btn btn-light rounded-pill px-3 text-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <?php
                    $total_item = 0;
                    if(isset($_SESSION['keranjang'])){
                        foreach($_SESSION['keranjang'] as $jumlah){ $total_item += $jumlah; }
                    }
                    ?>
                    <a href="keranjang.php" class="btn position-relative text-white">
                        <i class="fas fa-shopping-cart fs-5"></i>
                        <?php if($total_item > 0){ ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-warning text-dark border border-light" style="font-size: 10px;"><?php echo $total_item; ?></span>
                        <?php } ?>
                    </a>
                    <div class="vr h-50 mx-2 bg-white opacity-50"></div> 
                    <?php if(isset($_SESSION['status_login'])) { ?>
                        <a href="logout.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Logout</a>
                    <?php } else { ?>
                        <a href="login.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Masuk</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
            </ol>
        </nav>

        <div class="card card-detail bg-white">
            <div class="row g-0">
                <div class="col-md-5 img-detail-wrapper">
                    <img src="foto_produk/<?php echo $data['foto']; ?>" class="img-detail" alt="">
                </div>

                <div class="col-md-7 p-4 p-md-5">
                    <h2 class="fw-bold mb-2"><?php echo $data['nama_produk']; ?></h2>
                    
                    <div class="mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2">Stok Tersedia: <?php echo $data['stok']; ?></span>
                        <span class="text-muted ms-2 small">
                            <i class="fas fa-star text-warning"></i> <?php echo $rating_tampil; ?> 
                            | Terjual <?php echo $terjual_tampil; ?>+
                        </span>
                    </div>

                    <div class="price-tag mb-4">Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></div>

                    <h6 class="fw-bold">Deskripsi:</h6>
                    <p class="text-muted mb-4" style="line-height: 1.6;">
                        <?php echo nl2br($data['deskripsi']); ?>
                    </p>

                    <div class="d-grid gap-2 d-md-block">
                        <?php if($data['stok'] > 0){ ?>
                            <a href="tambah_keranjang.php?id=<?php echo $data['id']; ?>" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                                <i class="fas fa-plus me-2"></i> Masuk Keranjang
                            </a>
                        <?php } else { ?>
                            <button class="btn btn-secondary btn-lg rounded-pill px-5 shadow-sm" disabled>
                                <i class="fas fa-ban me-2"></i> Stok Habis
                            </button>
                        <?php } ?>

                        <a href="https://wa.me/No Admin?text=Halo Admin, apakah stok <?php echo $data['nama_produk']; ?> masih ada?" target="_blank" class="btn btn-outline-success btn-lg rounded-pill px-4">
                            <i class="fab fa-whatsapp me-2"></i> Tanya Stok
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mt-5 mb-3">Pilihan Lain Buat Kamu</h5>
        
        <div class="swiper mySwiper pb-4">
            <div class="swiper-wrapper">
                <?php
                // Query juga wajib diamankan, tapi karena $id sudah aman di atas, ini aman
                $query_terkait = mysqli_query($conn, "SELECT * FROM produk WHERE id != '$id' ORDER BY RAND() LIMIT 10");
                while($rek = mysqli_fetch_array($query_terkait)){
                ?>
                <div class="swiper-slide">
                    <a href="detail.php?id=<?php echo $rek['id']; ?>" class="text-decoration-none">
                        <div class="card card-product-sm h-100 border shadow-sm">
                            <img src="foto_produk/<?php echo $rek['foto']; ?>" class="card-img-sm mx-auto d-block">
                            <div class="card-body p-3 text-center">
                                <h6 class="text-dark mb-1 text-truncate" style="font-size: 14px;"><?php echo $rek['nama_produk']; ?></h6>
                                <div class="fw-bold text-primary small">Rp <?php echo number_format($rek['harga']); ?></div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>

    </div>

    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0 text-muted small fw-bold">© 2025 Toys Store. <span class="fw-normal">Dibuat sepenuh hati</span></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 2,
            spaceBetween: 15,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
            },
        });
    </script>
</body>
</html>