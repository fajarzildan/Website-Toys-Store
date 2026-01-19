<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status_login']) || $_SESSION['role'] != 'admin'){
    header("location:login.php");
    exit();
}

$jml_produk = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produk"));
$jml_user   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$stok_dikit = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produk WHERE stok < 3"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin - Toys Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="foto_produk/logo.png" type="image/png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; overflow-x: hidden; }
        
        .sidebar { background: white; min-height: 100vh; padding: 30px 20px; border-right: 1px solid #e9ecef; position: fixed; width: 260px; z-index: 100; }
        .brand-logo { font-size: 22px; font-weight: 700; color: #028BEF; text-decoration: none; display: flex; align-items: center; margin-bottom: 40px; white-space: nowrap; }
        .menu-label { font-size: 12px; text-transform: uppercase; color: #adb5bd; font-weight: 700; margin-bottom: 10px; padding-left: 10px; letter-spacing: 1px; }
        .menu-link { text-decoration: none; color: #495057; display: flex; align-items: center; padding: 12px 15px; border-radius: 12px; transition: all 0.3s ease; font-weight: 500; margin-bottom: 5px; }
        .menu-link i { width: 25px; font-size: 18px; margin-right: 10px; text-align: center; }
        .menu-link:hover, .menu-link.active { background: #e7f1ff; color: #028BEF; transform: translateX(5px); }
        .menu-link.logout:hover { background: #ffeaea; color: #dc3545; }

        .main-content { margin-left: 260px; padding: 40px; }

        .card-stat { 
            border: none; 
            border-radius: 16px; 
            color: white; 
            overflow: hidden; 
            position: relative; 
            transition: transform 0.3s; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.05); 
        }
        .card-stat:hover { transform: translateY(-5px); }
        .card-stat h3 { font-weight: 700; font-size: 2.5rem; margin-bottom: 0; }
        .card-stat p { margin: 0; opacity: 0.9; font-size: 14px; }
        .card-stat .icon-bg { 
            position: absolute; 
            right: 20px; 
            bottom: -10px; 
            font-size: 5rem; 
            opacity: 0.2; 
            transform: rotate(-15deg); 
        }

        .img-tabel { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
        
        @media print {
            .sidebar, .navbar, .btn, .card-header { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
        }

        .modal-content { border-radius: 15px; border: none; }
        .modal-header { border-bottom: 1px solid #eee; background: #f8f9fa; border-radius: 15px 15px 0 0; }
        .list-group-item { border: none; border-bottom: 1px solid #f0f0f0; padding: 15px; }
        .list-group-item:last-child { border-bottom: none; }
        .avatar-small { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; }
   </style>
</head>
<body>

    <div class="sidebar">
        <a href="index.php" class="brand-logo">
            <i class="fas fa-rocket me-2"></i> <span>Toys Admin</span>
        </a>
        
        <div class="menu-label">Menu Utama</div>
        <a href="admin.php" class="menu-link active"><i class="fas fa-home"></i> <span>Dashboard</span></a>
        <a href="index.php" class="menu-link"><i class="fas fa-store"></i> <span>Lihat Toko</span></a>
        <a href="tambah_produk.php" class="menu-link"><i class="fas fa-box-open"></i> <span>Upload Produk</span></a>
        
        <div class="menu-label mt-4">Akun</div>
        <a href="#" class="menu-link logout text-danger" onclick="logoutConfirm(event)">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
    </div>

    <div class="main-content">
        <h2 class="fw-bold mb-4">Dashboard Overview</h2>
        
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card-stat p-4" onclick="document.getElementById('tabelProduk').scrollIntoView({behavior: 'smooth'})" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <p>Total Produk</p>
                    <h3><?php echo $jml_produk; ?></h3>
                    <i class="fas fa-box icon-bg"></i>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-stat p-4" data-bs-toggle="modal" data-bs-target="#modalMember" style="background: linear-gradient(135deg, #2af598 0%, #009efd 100%);">
                    <p>Member Terdaftar</p>
                    <h3><?php echo $jml_user; ?></h3>
                    <i class="fas fa-users icon-bg"></i>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-stat p-4" data-bs-toggle="modal" data-bs-target="#modalStok" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); color: #880e4f;">
                    <p>Stok Menipis</p>
                    <h3><?php echo $stok_dikit; ?></h3>
                    <i class="fas fa-exclamation-circle icon-bg"></i>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4" id="tabelProduk" data-aos="fade-up">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0"><i class="fas fa-boxes me-2 text-primary"></i> Manajemen Produk</h5>
                <a href="tambah_produk.php" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="fas fa-plus me-2"></i> Tambah</a>
            </div>
            <button onclick="window.print()" class="btn btn-secondary btn-sm rounded-pill px-3 ms-4 mb-3" style="width: fit-content;">
                <i class="fas fa-print me-2"></i> Cetak Laporan
            </button>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light"><tr><th class="ps-4">No</th><th>Produk</th><th>Harga</th><th class="text-center">Stok</th><th class="text-center">Aksi</th></tr></thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query_produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
                        while($p = mysqli_fetch_array($query_produk)){
                        ?>
                        <tr>
                            <td class="ps-4 text-muted"><?php echo $no++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="foto_produk/<?php echo $p['foto']; ?>" class="img-tabel me-3">
                                    <div class="fw-bold text-dark"><?php echo $p['nama_produk']; ?></div>
                                </div>
                            </td>
                            <td class="fw-bold text-primary">Rp <?php echo number_format($p['harga']); ?></td>
                            <td class="text-center">
                                <?php if($p['stok'] < 3) { ?> <span class="badge bg-danger rounded-pill"><?php echo $p['stok']; ?></span>
                                <?php } else { ?> <span class="badge bg-success bg-opacity-10 text-success rounded-pill"><?php echo $p['stok']; ?></span> <?php } ?>
                            </td>
                            <td class="text-center">
                                <a href="edit_produk.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning text-white rounded-pill px-3 me-1"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="hapusProduk(<?php echo $p['id']; ?>)" class="btn btn-sm btn-danger rounded-pill px-3"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMember" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-users text-primary me-2"></i>Daftar Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="list-group list-group-flush">
                        <?php
                        $q_user = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                        if(mysqli_num_rows($q_user) > 0){
                            while($u = mysqli_fetch_array($q_user)){
                                $foto_u = $u['foto'] ? $u['foto'] : 'default.png';
                        ?>
                        <div class="list-group-item d-flex align-items-center">
                            <img src="foto_produk/<?php echo $foto_u; ?>" class="avatar-small me-3 border">
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold"><?php echo $u['nama']; ?></h6>
                                <small class="text-muted"><?php echo $u['email']; ?></small>
                            </div>
                            <a href="https://wa.me/<?php echo $u['no_hp']; ?>" target="_blank" class="btn btn-sm btn-success rounded-circle"><i class="fab fa-whatsapp"></i></a>
                        </div>
                        <?php 
                            }
                        } else {
                            echo "<div class='p-4 text-center text-muted'>Belum ada member yang daftar.</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalStok" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-danger bg-opacity-10">
                    <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Stok Menipis (< 3)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php
                        $q_stok = mysqli_query($conn, "SELECT * FROM produk WHERE stok < 3 ORDER BY stok ASC");
                        if(mysqli_num_rows($q_stok) > 0){
                            while($s = mysqli_fetch_array($q_stok)){
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="foto_produk/<?php echo $s['foto']; ?>" class="rounded me-3" width="40" height="40" style="object-fit:cover;">
                                <span class="fw-bold"><?php echo $s['nama_produk']; ?></span>
                            </div>
                            <span class="badge bg-danger rounded-pill fs-6 px-3">Sisa: <?php echo $s['stok']; ?></span>
                        </li>
                        <?php 
                            }
                        } else {
                            echo "<div class='p-4 text-center text-success'><i class='fas fa-check-circle me-2'></i>Aman! Stok semua produk aman.</div>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Oke, Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function logoutConfirm(e) {
            e.preventDefault(); Swal.fire({ title: 'Logout?', icon: 'warning', showCancelButton: true }).then((res) => { if(res.isConfirmed) window.location.href='logout.php'; }); 
        }
        function hapusProduk(id) {
            Swal.fire({ title: 'Hapus?', text: "Data hilang permanen", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' }).then((res) => {
                if(res.isConfirmed) window.location.href='hapus_produk.php?id='+id;
            });
        }
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>