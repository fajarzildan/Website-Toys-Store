<?php
session_start();
include 'koneksi.php';

if (isset($_GET['cari'])) {
    $pencarian = mysqli_real_escape_string($conn, $_GET['cari']);
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE nama_produk LIKE '%$pencarian%'");
} 
elseif (isset($_GET['kategori'])) {
    $kategori = mysqli_real_escape_string($conn, $_GET['kategori']);
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE kategori = '$kategori'");
} 
elseif (isset($_GET['umur'])) {
    $umur = mysqli_real_escape_string($conn, $_GET['umur']);
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE umur = '$umur'");
}
else {
    $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toys Store - Official Store</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="foto_produk/logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex; flex-direction: column; min-height: 100vh;
        }
        .container.mt-4 { flex: 1; }
        
        .navbar-custom {
            background: linear-gradient(135deg, #028BEF 0%, #00223B 100%);
            padding: 15px 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .search-container { border: none; border-radius: 50px; overflow: hidden; background: white; padding: 2px; }
        .search-input { border: none; font-size: 14px; }
        .search-input:focus { box-shadow: none; }

        .carousel-item img {
            border-radius: 15px;
            height: 400px;
            object-fit: cover;
            object-position: center;
        }
        @media (max-width: 768px) { .carousel-item img { height: 200px; } }

        .cat-icon {
            width: 55px; height: 55px; border-radius: 18px; display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 8px; transition: 0.3s; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); color: #028BEF;
        }
        .cat-item:hover .cat-icon { transform: translateY(-5px); background: #028BEF; color: white; }
        .cat-text { font-size: 13px; font-weight: 600; color: #555; }

        .card-product {
            border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.2s; height: 100%; background: white; overflow: hidden;
        }
        .card-product:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        
        .card-img-wrapper {
            height: 180px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #fff; padding: 10px;
        }
        .card-img-top { width: 100%; height: 100%; object-fit: contain; }
        
        .product-title {
            font-size: 14px; line-height: 20px; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; color: #212121; margin-bottom: 5px;
        }
        .product-price { font-size: 16px; font-weight: 700; color: #028BEF; }
        
        .location-info { font-size: 11px; color: #6c757d; display: flex; align-items: center; margin-top: 8px; }
        .badge-toko { background-color: #cce5ff; color: #004085; font-weight: 700; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-right: 5px; }
        .rating-info { font-size: 11px; color: #6c757d; margin-top: 3px; }

        .grayscale { filter: grayscale(100%); opacity: 0.7; }
        .rotate-n15 { transform: rotate(-15deg); }

        #myBtn {
            display: none;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 99;
            border: none;
            outline: none;
            background: linear-gradient(135deg, #028BEF 0%, #00223B 100%);
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 50%;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: 0.3s;
        }
        #myBtn:hover {
            background: #0b5ed7;
            transform: translateY(-5px);
        }
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
                        <input type="text" name="cari" class="form-control search-input ps-3" placeholder="Mau cari mainan apa hari ini?" value="<?php if(isset($_GET['cari'])){ echo htmlspecialchars($_GET['cari']); } ?>">
                        <button type="submit" class="btn btn-light rounded-pill px-3 text-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <a href="tentang.php" class="text-white text-decoration-none small fw-bold me-2">
                        <i class="fas fa-store me-1"></i> Info Toko
                    </a>
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
                        
                        <?php if($_SESSION['role'] == 'admin'){ ?>
                            <a href="admin.php" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="#" class="btn btn-outline-light text-primary bg-white btn-sm rounded-pill px-3 shadow-sm fw-bold" onclick="logoutConfirm(event)">
                                Logout <i class="fas fa-sign-out-alt ms-1"></i>
                            </a>
                        <?php } else { 
                            $uid = $_SESSION['user_id'];
                            $cek_foto = mysqli_query($conn, "SELECT foto FROM users WHERE id='$uid'");
                            $data_user = mysqli_fetch_array($cek_foto);
                            $foto_nav = $data_user['foto'] ? $data_user['foto'] : 'default.png';
                        ?>
                            <a href="profil.php" class="text-decoration-none text-white d-flex align-items-center me-3">
                                <img src="foto_produk/<?php echo $foto_nav; ?>" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%; border: 2px solid white;" class="me-2">
                                <span class="fw-bold d-none d-lg-inline">Hai, <?php echo $_SESSION['user_nama']; ?></span>
                            </a>
                            <a href="#" class="btn btn-outline-light text-primary bg-white btn-sm rounded-pill px-3 shadow-sm fw-bold" onclick="logoutConfirm(event)">
                                Logout <i class="fas fa-sign-out-alt ms-1"></i>
                            </a>
                        <?php } ?>

                    <?php } else { ?>
                        <a href="login.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Masuk</a>
                        <a href="daftar.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Daftar</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <div id="promoCarousel" class="carousel slide shadow rounded-4 mb-5 overflow-hidden" data-bs-ride="carousel" data-aos="fade-down">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="3000">
                    <img src="https://images.pexels.com/photos/163036/mario-luigi-yoschi-figures-163036.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="d-block w-100" alt="Mainan 1">
                </div>
                <div class="carousel-item" data-bs-interval="3000">
                    <img src="https://images.pexels.com/photos/35967/mini-cooper-auto-model-vehicle.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="d-block w-100" alt="Mainan 2">
                </div>
                <div class="carousel-item" data-bs-interval="3000">
                    <img src="https://images.pexels.com/photos/3663060/pexels-photo-3663060.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="d-block w-100" alt="Mainan 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        </div>

        <h5 class="fw-bold mb-3 ms-2">Kategori Pilihan</h5>
        <div class="d-flex justify-content-between text-center mb-5 px-2 py-3 overflow-auto gap-3" data-aos="fade-right">
            <a href="index.php" class="text-decoration-none cat-item flex-fill">
                <div class="cat-icon mx-auto"><i class="fas fa-th-large"></i></div><div class="cat-text">Semua</div>
            </a>
            <a href="index.php?kategori=Action Figure" class="text-decoration-none cat-item flex-fill">
                <div class="cat-icon mx-auto"><i class="fas fa-user-astronaut"></i></div><div class="cat-text">Action Figure</div>
            </a>
            <a href="index.php?kategori=Boneka" class="text-decoration-none cat-item flex-fill">
                <div class="cat-icon mx-auto"><i class="fas fa-paw"></i></div><div class="cat-text">Boneka</div>
            </a>
            <a href="index.php?kategori=Edukasi" class="text-decoration-none cat-item flex-fill">
                <div class="cat-icon mx-auto"><i class="fas fa-brain"></i></div><div class="cat-text">Edukasi</div>
            </a>
            <a href="index.php?kategori=Kendaraan" class="text-decoration-none cat-item flex-fill">
                <div class="cat-icon mx-auto"><i class="fas fa-car"></i></div><div class="cat-text">Mobil</div>
            </a>
        </div>

        <h5 class="fw-bold mb-3 ms-2">Berdasarkan Usia Anak</h5>
        <div class="d-flex gap-2 mb-4 px-2 overflow-auto" style="white-space: nowrap;">
            <a href="index.php" class="btn btn-outline-dark rounded-pill btn-sm px-3 fw-bold">Semua</a>
            <a href="index.php?umur=3%2B Tahun" class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-bold">3+ Tahun (Balita)</a>
            <a href="index.php?umur=6%2B Tahun" class="btn btn-outline-success rounded-pill btn-sm px-3 fw-bold">6+ Tahun (SD)</a>
            <a href="index.php?umur=12%2B Tahun" class="btn btn-outline-danger rounded-pill btn-sm px-3 fw-bold">12+ Tahun (Remaja)</a>
            <a href="index.php?umur=Semua Umur" class="btn btn-outline-warning rounded-pill btn-sm px-3 fw-bold">Semua Umur</a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 px-2" data-aos="fade-right">
            <h5 class="fw-bold mb-0">Rekomendasi Untukmu </h5>
            <a href="#" class="text-decoration-none small fw-bold">Lihat Semua</a>
        </div>
        
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3 mb-5">
            <?php 
            if(mysqli_num_rows($query) > 0){
                while($data = mysqli_fetch_array($query)) { 
                    
                $rating_tampil = ($data['rating'] > 0) ? $data['rating'] : number_format(mt_rand(30, 50) / 10, 1);
                $terjual_tampil = ($data['terjual'] > 0) ? $data['terjual'] : mt_rand(1, 10);
            ?>
            <div class="col d-flex flex-column h-100" data-aos="fade-up" data-aos-delay="100">
                <a href="detail.php?id=<?php echo $data['id']; ?>" class="text-decoration-none flex-grow-1">
                    <div class="card-product position-relative h-100">
                        <?php if($data['stok'] < 1){ ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.1); z-index: 2;">
                                <span class="badge bg-danger fs-6 shadow rotate-n15">STOK HABIS</span>
                            </div>
                        <?php } ?>

                        <div class="card-img-wrapper">
                            <img src="foto_produk/<?php echo $data['foto']; ?>" class="card-img-top <?php echo ($data['stok']<1)?'grayscale':''; ?>" alt="...">
                        </div>
                        <div class="card-body p-2">
                        <div class="mb-1">
                            <span class="badge bg-light text-secondary border" style="font-size: 10px;">
                                <i class="fas fa-child me-1"></i> <?php echo $data['umur']; ?>
                            </span>
                        </div>
                        <div class="product-title"><?php echo $data['nama_produk']; ?></div>
                            <div class="product-price">Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></div>
                            
                            <div class="rating-info">
                                <i class="fas fa-star text-warning"></i> 
                                <b><?php echo $rating_tampil; ?></b> 
                                <span class="mx-1">|</span> 
                                <span>Terjual <?php echo $terjual_tampil; ?>+</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php 
                } 
            } else {
                echo '<div class="col-12 text-center py-5"><p class="text-muted">Produk tidak ditemukan</p></div>';
            }
            ?>
        </div>
    </div>
    
    <footer class="bg-white border-top py-3 mt-4">
        <div class="container text-center">
            <p class="mb-0 text-muted small fw-bold">© 2025 Toys Store. <span class="fw-normal">Dibuat sepenuh hati</span></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function logoutConfirm(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin mau keluar?',
                text: "Kamu harus login lagi nanti lho",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) { window.location.href = 'logout.php'; }
            })
        }
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 800,
        once: true,
    });
    </script>

    <button onclick="topFunction()" id="myBtn" title="Kembali ke Atas">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        let mybutton = document.getElementById("myBtn");
        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }
        function topFunction() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    </script>
</body>
</html>