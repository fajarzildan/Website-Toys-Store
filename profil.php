<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status_login'])){
    header("location:login.php");
    exit();
}

$id = $_SESSION['user_id'];

if(isset($_POST['simpan'])){
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp     = mysqli_real_escape_string($conn, $_POST['hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $password_baru = mysqli_real_escape_string($conn, $_POST['password']);
    
    $query_pass = "";
    if(!empty($password_baru)){
        $query_pass = ", password='$password_baru'";
    }

    $foto_nama = $_FILES['foto']['name'];
    $foto_sumber = $_FILES['foto']['tmp_name'];
    $query_foto = "";

    if($foto_nama != ""){
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $x = explode('.', $foto_nama);
        $ekstensi = strtolower(end($x));

        if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
            $nama_file_baru = date('dmYHis') . '-' . $foto_nama;
            $folder_tujuan = './foto_produk/' . $nama_file_baru;
            
            move_uploaded_file($foto_sumber, $folder_tujuan);
            $query_foto = ", foto='$nama_file_baru'";
        } else {
            echo "<script>Swal.fire({ icon: 'error', title: 'Gagal', text: 'Format foto harus JPG/PNG' });</script>";
        }
    }

    $update = mysqli_query($conn, "UPDATE users SET nama='$nama', no_hp='$hp', alamat='$alamat' $query_pass $query_foto WHERE id='$id'");

    if($update){
        $_SESSION['user_nama'] = $nama;
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Profil Diperbarui',
                text: 'Data kamu sudah tersimpan aman',
                showConfirmButton: false,
                timer: 1500
            }).then(() => { window.location='profil.php'; });
        </script>";
    } else {
        echo "<script>Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan sistem.' });</script>";
    }
}

$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pengaturan Profil - Toys Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="foto_produk/logo.png" type="image/png">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        
        .sidebar { background: white; min-height: 100vh; padding: 30px 20px; position: fixed; width: 260px; border-right: 1px solid #e9ecef; }
        .brand-logo { font-size: 22px; font-weight: 700; color: #028BEF; text-decoration: none; display: flex; align-items: center; margin-bottom: 40px; }
        
        .user-box { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0; }
        .foto-side { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; border: 3px solid #e7f1ff; }
        
        .menu-link { text-decoration: none; color: #555; display: flex; align-items: center; padding: 12px 15px; border-radius: 12px; transition: 0.3s; font-weight: 500; margin-bottom: 5px; }
        .menu-link:hover, .menu-link.active { background: #e7f1ff; color: #028BEF; }
        .menu-link i { width: 25px; font-size: 18px; margin-right: 10px; text-align: center; }
        
        .main-content { margin-left: 260px; padding: 40px; }

        .card-profile { 
            border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; overflow: hidden;
        }
        
        .card-header-profile {
            background: linear-gradient(135deg, #028BEF 0%, #00223B 100%);
            padding: 40px 30px 90px 30px;
            color: white;
            text-align: center;
        }

        .profile-pic-wrapper {
            margin-top: -75px;
            position: relative;
            display: inline-block;
        }
        .foto-preview { 
            width: 140px; height: 140px; 
            object-fit: cover; 
            border-radius: 50%; 
            border: 5px solid white; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: white;
        }
        .btn-upload {
            position: absolute; bottom: 5px; right: 5px;
            background: #ffc107; color: #000; border: 3px solid white;
            border-radius: 50%; width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: 0.2s;
        }
        .btn-upload:hover { transform: scale(1.1); }

        .form-label { font-weight: 600; font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 1px solid #e0e0e0; font-size: 14px; background-color: #f8f9fa; }
        .form-control:focus { box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1); border-color: #028BEF; background-color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="index.php" class="brand-logo"><i class="fas fa-robot me-2"></i> <span>Toys Member</span></a>
        
        <div class="user-box">
            <?php $foto = $data['foto'] ? $data['foto'] : 'default.png'; ?>
            <img src="foto_produk/<?php echo $foto; ?>" class="foto-side">
            <h6 class="fw-bold m-0"><?php echo htmlspecialchars($data['nama']); ?></h6>
            <small class="text-muted">Member Setia</small>
        </div>

        <a href="profil.php" class="menu-link active"><i class="fas fa-user-cog"></i> <span>Pengaturan Profil</span></a>
        <a href="keranjang.php" class="menu-link"><i class="fas fa-shopping-bag"></i> <span>Keranjang Belanja</span></a>
        <a href="index.php" class="menu-link"><i class="fas fa-arrow-left"></i> <span>Kembali ke Toko</span></a>
        
        <hr class="my-3 opacity-25"> <div class="d-grid gap-2 mb-3">
            <a href="https://wa.me/No Admin?text=Halo%20Admin%20Toys%20Store,%20saya%20mau%20request%20mainan%20ini%20dong...%0A%0A(Sebutkan%20nama%20mainan/kirim%20fotonya)" target="_blank" class="btn btn-success rounded-pill fw-bold shadow-sm">
                <i class="fab fa-whatsapp me-2"></i> Request / Bantuan
            </a>
        </div>

        <div class="mt-5 border-top pt-3">
            <a href="logout.php" class="menu-link text-danger" onclick="logoutConfirm(event)">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>

    </div>

    <div class="main-content">
        
        <div class="card-profile">
            <div class="card-header-profile">
                <h3 class="m-0 fw-bold">Halo, <?php echo htmlspecialchars($data['nama']); ?></h3>
                <p class="m-0 opacity-75">Atur informasi pribadimu di sini</p>
            </div>

            <div class="card-body px-5 pb-5">
                <form method="POST" enctype="multipart/form-data">
                    
                    <div class="text-center mb-5">
                        <div class="profile-pic-wrapper">
                            <?php $foto = $data['foto'] ? $data['foto'] : 'default.png'; ?>
                            <img src="foto_produk/<?php echo $foto; ?>" id="tampilFoto" class="foto-preview">
                            
                            <label class="btn-upload" title="Ganti Foto">
                                <i class="fas fa-camera"></i>
                                <input type="file" name="foto" style="display: none;" onchange="preview(this)">
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted ps-3"><i class="fas fa-user"></i></span>
                                <input type="text" name="nama" class="form-control border-start-0 ps-0" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted ps-3"><i class="fab fa-whatsapp"></i></span>
                                <input type="number" name="hp" class="form-control border-start-0 ps-0" value="<?php echo htmlspecialchars($data['no_hp']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Alamat Pengiriman</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted ps-3 pt-2 align-items-start"><i class="fas fa-map-marker-alt mt-1"></i></span>
                            <textarea name="alamat" class="form-control border-start-0 ps-0" rows="3"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
                        </div>
                    </div>

                    <div class="bg-light p-4 rounded-4 mb-4 border border-dashed">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-lock text-warning me-2 fs-5"></i>
                            <h6 class="fw-bold m-0">Keamanan Akun</h6>
                        </div>
                        <input type="password" name="password" class="form-control bg-white" placeholder="Ketik password baru jika ingin mengganti...">
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="simpan" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>

                    <hr class="my-5">

                    <div class="alert alert-danger border-danger">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-danger m-0"><i class="fas fa-exclamation-triangle me-2"></i> Zona Bahaya</h6>
                                <small class="text-muted">Menghapus akun akan menghilangkan semua data belanjaanmu.</small>
                            </div>
                            <a href="hapus_akun.php" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="hapusConfirm(event)">
                                Hapus Akun
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function preview(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('tampilFoto').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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

        function hapusConfirm(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin Hapus Akun?',
                text: "Aksi ini PERMANEN! Data kamu akan hilang selamanya.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Akun Saya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus_akun.php';
                }
            })
        }
    </script>

</body>
</html>