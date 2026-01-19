<?php 
include 'koneksi.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body { font-family: 'Poppins', sans-serif; background: #f0f3f7; display: flex; align-items: center; justify-content: center; min-height: 100vh; } .card-daftar { width: 100%; max-width: 500px; border-radius: 15px; border: none; }</style>
</head>
<body>

    <?php
        if(isset($_POST['daftar'])){
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $hp = mysqli_real_escape_string($conn, $_POST['hp']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

        $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($cek) > 0){
            echo "<script>Swal.fire({ icon: 'warning', title: 'Gagal Daftar', text: 'Email ini sudah terdaftar, coba pakai email lain ya' });</script>";
        } else {
            $query_insert = "INSERT INTO users (nama, email, password, no_hp, alamat, foto) 
                            VALUES ('$nama', '$email', '$password', '$hp', '$alamat', 'default.png')";
            
            if(mysqli_query($conn, $query_insert)){
                
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = ''; //Masukan email kamu 
                    $mail->Password   = ''; //Masukan password code yang sudah dibuat
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    $mail->setFrom($mail->Username, 'Toys Store Fajar');
                    $mail->addAddress($email, $nama);

                    $mail->isHTML(true);
                    $mail->Subject = 'Selamat Datang di Keluarga Toys Store';
                    
                    $mail->Body = "
                    <div style='font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 40px;'>
                        <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden;'>
                            <div style='background-color: #028BEF; padding: 30px; text-align: center;'>
                                <h1 style='color: #ffffff; margin: 0;'>Toys Store Fajar</h1>
                            </div>
                            <div style='padding: 40px;'>
                                <h2>Halo, $nama</h2>
                                <p>Akunmu berhasil dibuat! Selamat bergabung.</p>
                                <a href='http://localhost/Website%20E-Commerce/login.php' style='background-color: #ffc107; color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Login Sekarang</a>
                            </div>
                        </div>
                    </div>";

                    $mail->send();

                } catch (Exception $e) {
                }

                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Daftar',
                        text: 'Silakan login untuk mulai belanja',
                        confirmButtonText: 'Login'
                    }).then(() => { window.location = 'login.php'; });
                </script>";

            } else {
                echo "<script>Swal.fire({ icon: 'error', title: 'Error Database', text: 'Gagal mendaftar, coba lagi nanti' });</script>";
            }
        }
    }
    ?>

    <div class="card card-daftar shadow p-4 my-5">
        <h3 class="text-center fw-bold mb-4 text-primary">Daftar Akun</h3>
        <form method="POST">
            <div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="mb-3"><label>No. HP</label><input type="number" name="hp" class="form-control" required></div>
            <div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" required></textarea></div>
            <button type="submit" name="daftar" class="btn btn-primary w-100 rounded-pill">Daftar Sekarang</button>
            <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login</a></p>
        </form>
    </div>

</body>
</html>