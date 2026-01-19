<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status_login']) || $_SESSION['role'] != 'admin'){ header("location:login.php"); exit(); }

if (isset($_POST['simpan'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $umur      = mysqli_real_escape_string($conn, $_POST['umur']); 

    $harga     = (int)$_POST['harga']; 
    $stok      = (int)$_POST['stok']; 
    $rating    = $_POST['rating']; 
    $terjual   = $_POST['terjual'];
    
    $foto_nama = $_FILES['foto']['name'];
    $foto_sumber = $_FILES['foto']['tmp_name'];
    $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'gif', 'webp');
    $x = explode('.', $foto_nama);
    $ekstensi = strtolower(end($x));

    if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
        
        $nama_unik = date('dmYHis').$foto_nama; 
        $folder_tujuan = './foto_produk/' . $nama_unik;
        
        if(move_uploaded_file($foto_sumber, $folder_tujuan)){
            $query = mysqli_query($conn, "INSERT INTO produk VALUES (NULL, '$nama', '$kategori', '$umur' , '$harga', '$stok', '$nama_unik', '$deskripsi', '$rating', '$terjual')");
            
            if ($query) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Produk baru siap dijual',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => { window.location.href='admin.php'; });
                </script>";
            } else {
                echo "<script>Swal.fire({ icon: 'error', title: 'Gagal Query', text: 'Ada masalah database' });</script>";
            }
        }
    } else {
        echo "<script>Swal.fire({ icon: 'error', title: 'Format Salah', text: 'Hanya boleh upload foto (JPG, PNG, GIF)' });</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Upload Produk - Toys Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar-custom { background: linear-gradient(135deg, #028BEF 0%, #00223B 100%); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); background: white; overflow: hidden; }
        
        .card-header-custom {
            background: #cfe2ff;
            padding: 20px 30px;
            border-bottom: 1px solid #b6d4fe;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-label { font-weight: 600; font-size: 12px; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; letter-spacing: 0.5px; }
        .form-control, .form-select { border-radius: 10px; padding: 12px 15px; border: 1px solid #e0e0e0; font-size: 14px; background-color: #fcfcfc; }
        .form-control:focus { border-color: #028BEF; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2); background-color: white; }

        .foto-area { background: #f8f9fa; border: 1px dashed #ced4da; border-radius: 12px; padding: 15px; display: flex; align-items: center; margin-bottom: 20px; }
        .img-preview { width: 80px; height: 80px; object-fit: contain; border-radius: 8px; background: white; border: 1px solid #eee; margin-right: 15px; display: none; }
        .img-placeholder { width: 80px; height: 80px; border-radius: 8px; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #adb5bd; margin-right: 15px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="admin.php"><i class="fas fa-rocket me-2"></i> Toys Admin</a>
            <div class="d-flex gap-3">
                <a href="admin.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Dashboard</a>
                <a href="index.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold">Lihat Toko</a>
                <a href="logout.php" class="btn btn-light text-primary btn-sm rounded-pill px-3 fw-bold" onclick="logoutConfirm(event)">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="card card-custom">
                    <div class="card-header-custom">
                        <h4 class="fw-bold text-primary m-0"><i class="fas fa-plus-circle me-2"></i> Upload Produk Baru</h4>
                        <a href="index.php" class="btn btn-light btn-sm rounded-pill px-3 border fw-bold text-secondary">Batal</a>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <form action="" method="POST" enctype="multipart/form-data">
                            
                            <div class="foto-area">
                                <div id="placeholder" class="img-placeholder"><i class="fas fa-image fs-4"></i></div>
                                <img src="" id="imgPreview" class="img-preview">
                                
                                <div class="flex-grow-1">
                                    <label class="form-label mb-1">Pilih Foto Produk</label>
                                    <input type="file" name="foto" class="form-control form-control-sm bg-white" required onchange="preview(this)">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Nama Produk</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Robot Gundam.." required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Action Figure">Action Figure</option>
                                        <option value="Boneka">Boneka</option>
                                        <option value="Edukasi">Edukasi</option>
                                        <option value="Kendaraan">Mobil</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Rekomendasi Umur</label>
                                <select name="umur" class="form-select" required>
                                    <option value="">Pilih Kategori Umur</option>
                                    <option value="3+ Tahun">3+ Tahun (Balita & TK)</option>
                                    <option value="6+ Tahun">6+ Tahun (Anak SD)</option>
                                    <option value="12+ Tahun">12+ Tahun (Remaja & Dewasa)</option>
                                    <option value="Semua Umur">Semua Umur</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" name="harga" class="form-control" placeholder="0" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Stok Awal</label>
                                    <input type="number" name="stok" class="form-control" placeholder="0" required>
                                </div>
                            </div>

                            <div class="row bg-light p-3 rounded-4 mb-4 border border-dashed mx-0">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-muted">Rating Awal (Max 5.0)</label>
                                    <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control bg-white" value="5.0" oninput="if(this.value > 5) this.value = 5;">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-muted">Terjual Awal</label>
                                    <input type="number" name="terjual" class="form-control bg-white" value="0">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan spesifikasi mainan..."></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="simpan" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                    <i class="fas fa-save me-2"></i> Simpan Produk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function preview(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('placeholder').style.display = 'none';
                    document.getElementById('imgPreview').style.display = 'block';
                    document.getElementById('imgPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function logoutConfirm(e) {
            e.preventDefault();
            Swal.fire({ title: 'Logout?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya' }).then((result) => { if(result.isConfirmed) window.location.href='logout.php'; });
        }
    </script>

</body>
</html>