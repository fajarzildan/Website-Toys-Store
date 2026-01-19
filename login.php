<?php 
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - Toys Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f3f7; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-login { width: 400px; border-radius: 15px; border: none; }
    </style>
</head>
<body>

    <?php
    if(isset($_POST['login'])){
        $user_input = mysqli_real_escape_string($conn, $_POST['user']);
        $password = mysqli_real_escape_string($conn, $_POST['pass']);

        $cek_admin = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$user_input' AND password = '$password'");
        
        if(mysqli_num_rows($cek_admin) > 0){
        $data = mysqli_fetch_array($cek_admin);
        $_SESSION['status_login'] = true;
        $_SESSION['role'] = 'admin';
        $_SESSION['user_nama'] = 'Admin';
        
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Halo Admin',
                text: 'Selamat bekerja kembali',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = 'admin.php';
            });
        </script>";
    } 
    else {
        $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$user_input' AND password = '$password'");
        
        if(mysqli_num_rows($cek_user) > 0){
            $data = mysqli_fetch_array($cek_user);
            $_SESSION['status_login'] = true;
            $_SESSION['role'] = 'member';
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['user_nama'] = $data['nama'];
            
            if(!empty($data['data_keranjang'])){
                $keranjang_db = json_decode($data['data_keranjang'], true);
                
                if(empty($_SESSION['keranjang'])){
                    $_SESSION['keranjang'] = $keranjang_db;
                } else {
                    if(is_array($keranjang_db)){
                        foreach($keranjang_db as $id => $qty){
                            if(isset($_SESSION['keranjang'][$id])){
                                $_SESSION['keranjang'][$id] += $qty;
                            } else {
                                $_SESSION['keranjang'][$id] = $qty;
                            }
                        }
                    }
                }
            }
            
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil',
                    text: 'Selamat Datang, " . $data['nama'] . "',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Username atau Password salah',
                });
            </script>";
        }
    }
}
?>

    <div class="card card-login shadow p-4">
        <h3 class="text-center fw-bold mb-4 text-primary">Masuk Dulu Yuk</h3>
        <form action="" method="POST">
            <div class="mb-3">
                <label>Email / Username</label>
                <input type="text" name="user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 rounded-pill mb-2">Masuk</button>
            <a href="index.php" class="btn btn-outline-secondary w-100 rounded-pill">Kembali</a>
            <div class="text-end mb-3">
                <a href="https://wa.me/62895383041950?text=Halo Admin, saya lupa password akun saya. Mohon bantu reset." target="_blank" class="small text-decoration-none text-muted">Lupa Password?</a>
            </div>
            
            <hr>
            <p class="text-center">Belum punya akun? <a href="daftar.php" class="text-decoration-none fw-bold">Daftar disini</a></p>
        </form>
    </div>

</body>
</html>