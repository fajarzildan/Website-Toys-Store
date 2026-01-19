<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Toys Store Fajar</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
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
        .search-container { border: none; border-radius: 50px; overflow: hidden; background: white; padding: 2px; }
        .search-input { border: none; font-size: 14px; }
        .search-input:focus { box-shadow: none; }

        .img-keranjang { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; }
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
                        <input type="text" name="cari" class="form-control search-input ps-3" placeholder="Cari mainan...">
                        <button type="submit" class="btn btn-light rounded-pill px-3 text-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="d-flex align-items-center">
                    <a href="index.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Lanjut Belanja</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <h4 class="fw-bold mb-4"><i class="fas fa-shopping-bag text-primary me-2"></i>Keranjang Belanja</h4>

        <?php if(empty($_SESSION['keranjang']) OR !isset($_SESSION['keranjang'])){ ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="150" class="mb-3 opacity-50">
                <h5 class="text-muted">Keranjang masih kosong nih</h5>
                <a href="index.php" class="btn btn-primary rounded-pill mt-3 px-4">Mulai Belanja</a>
            </div>
        <?php } else { ?>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Produk</th>
                                <th>Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_belanja = 0;
                            foreach ($_SESSION['keranjang'] as $id_produk => $jumlah):
                                $ambil = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'");
                                $pecah = mysqli_fetch_array($ambil);
                                $subtotal = $pecah['harga'] * $jumlah;
                                $total_belanja += $subtotal;
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="foto_produk/<?php echo $pecah['foto']; ?>" class="img-keranjang border me-3">
                                        <div>
                                            <h6 class="mb-0 fw-bold"><?php echo $pecah['nama_produk']; ?></h6>
                                            <small class="text-muted"><?php echo $pecah['kategori']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp <?php echo number_format($pecah['harga']); ?></td>
                                <td class="text-center" style="width: 140px;">
                                    <div class="input-group input-group-sm">
                                        <a href="ubah_keranjang.php?id=<?php echo $id_produk; ?>&aksi=kurang" class="btn btn-outline-secondary"><i class="fas fa-minus"></i></a>
                                        <span class="form-control text-center border-secondary fw-bold"><?php echo $jumlah; ?></span>
                                        <a href="ubah_keranjang.php?id=<?php echo $id_produk; ?>&aksi=tambah" class="btn btn-outline-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                </td>
                                <td class="fw-bold text-primary">Rp <?php echo number_format($subtotal); ?></td>
                                <td>
                                    <a href="hapus_keranjang.php?id=<?php echo $id_produk ?>" class="text-danger fs-5"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row justify-content-end mt-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4" data-aos="fade-up">
                        <div class="d-flex justify-content-between mb-3" data-aos="fade-right">
                            <span class="text-muted">Total Belanja</span>
                            <span class="fw-bold fs-5">Rp <?php echo number_format($total_belanja); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Checkout Sekarang ➡</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <footer class="bg-white border-top py-3 mt-4">
        <div class="container text-center">
            <p class="mb-0 text-muted small fw-bold">© 2025 Toys Store. <span class="fw-normal">Dibuat sepenuh hati</span></p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init({ duration: 800, once: true });
    </script>

</body>
</html>