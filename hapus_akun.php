<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status_login'])){
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Hapus Akun</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
$id = $_SESSION['user_id'];

$ambil = mysqli_query($conn, "SELECT foto FROM users WHERE id = '$id'");
$pecah = mysqli_fetch_array($ambil);
$foto_user = $pecah['foto'];

if ($foto_user != 'default.png' && file_exists("foto_produk/$foto_user")) {
    unlink("foto_produk/$foto_user");
}

$hapus = mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");

if($hapus){
    session_destroy();
    
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Dihapus',
            text: 'Akun kamu sudah terhapus permanen. Sampai jumpa',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location = 'index.php';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Terjadi kesalahan saat menghapus akun.',
        }).then(() => {
            window.location = 'profil.php';
        });
    </script>";
}
?>

</body>
</html>