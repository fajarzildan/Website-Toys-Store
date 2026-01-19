<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Toys Store</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="foto_produk/logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; display: flex; flex-direction: column; min-height: 100vh; }
        .container-flex { flex: 1; }

        .navbar-custom { background: linear-gradient(135deg, #028BEF 0%, #00223B 100%); padding: 15px 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .search-container { border: none; border-radius: 50px; overflow: hidden; background: white; padding: 2px; }
        .search-input { border: none; font-size: 14px; }
        .search-input:focus { box-shadow: none; }

        .about-header {
            background: linear-gradient(135deg, #028BEF 0%, #00223B 100%);
            color: white;
            padding: 60px 0 80px 0;
            margin-bottom: -40px;
            border-radius: 0 0 50px 50px;
            text-align: center;
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
                        <input type="text" name="cari" class="form-control search-input ps-3" placeholder="Mau cari mainan apa hari ini?">
                        <button type="submit" class="btn btn-light rounded-pill px-3 text-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <a href="tentang.php" class="text-white text-decoration-none small fw-bold me-2"><i class="fas fa-store me-1"></i> Info Toko</a>

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
                            <a href="admin.php" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark"><i class="fas fa-tachometer-alt"></i> Dash</a>
                            <a href="#" class="btn btn-outline-light text-primary bg-white btn-sm rounded-pill px-3 shadow-sm fw-bold" onclick="logoutConfirm(event)">Logout</a>
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
                            <a href="#" class="btn btn-outline-light text-primary bg-white btn-sm rounded-pill px-3 shadow-sm fw-bold" onclick="logoutConfirm(event)">Logout</a>
                        <?php } ?>
                    <?php } else { ?>
                        <a href="login.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Masuk</a>
                        <a href="daftar.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Daftar</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="about-header" data-aos="zoom-in">
        <h1 class="fw-bold">Tentang Kami</h1>
        <p class="opacity-75">Mewujudkan Kebahagiaan di Setiap Rumah</p>
    </div>

    <div class="container container-flex mb-5">
        
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="margin-top: -30px;" data-aos="fade-up">
            <div class="row align-items-center">
                <div class="col-md-6 text-center">
                    <img src="https://img.freepik.com/free-vector/team-goals-concept-illustration_114360-5183.jpg" class="img-fluid" style="max-height: 250px;">
                </div>
                <div class="col-md-6">
                    <h3 class="fw-bold text-primary mb-3">Misi Kami</h3>
                    <p class="text-muted" style="line-height: 1.8;">
                        Toys Store bukan sekadar toko mainan, kami adalah jembatan menuju kreativitas dan edukasi anak. 
                        Kami berkomitmen menyediakan mainan berkualitas tinggi yang aman, mendidik, dan terjangkau bagi seluruh keluarga di Indonesia.
                    </p>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt text-success fs-3 me-3"></i>
                                <div><h6 class="fw-bold m-0">Aman & SNI</h6><small class="text-muted">Teruji klinis</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck-fast text-warning fs-3 me-3"></i>
                                <div><h6 class="fw-bold m-0">Pengiriman Kilat</h6><small class="text-muted">Se-Indonesia</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-5" data-aos="fade-up">
            <h3 class="fw-bold">Hubungi Kami</h3>
            <p class="text-muted">Punya pertanyaan seputar produk? Yuk ngobrol!</p>
            
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="#" class="btn btn-outline-dark rounded-circle p-3 shadow-sm" style="width: 50px; height: 50px;"><i class="fab fa-instagram fs-5"></i></a>
                <a href="#" class="btn btn-outline-dark rounded-circle p-3 shadow-sm" style="width: 50px; height: 50px;"><i class="fab fa-facebook-f fs-5"></i></a>
                <a href="#" class="btn btn-outline-dark rounded-circle p-3 shadow-sm" style="width: 50px; height: 50px;"><i class="fab fa-tiktok fs-5"></i></a>
                <a href="https://wa.me/No Admin" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center">
                    <i class="fab fa-whatsapp fs-4 me-2"></i> Chat Admin
                </a>
            </div>
        </div>

    </div>

    <footer class="bg-white border-top py-3 mt-auto">
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
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
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
</body>
</html>