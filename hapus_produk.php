<?php 
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status_login']) || $_SESSION['role'] != 'admin'){ 
    header("location:login.php"); 
    exit(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
$id = mysqli_real_escape_string($conn, $_GET['id']);

$ambil = mysqli_query($conn, "SELECT foto FROM produk WHERE id = '$id'");
if(mysqli_num_rows($ambil) > 0){
    $pecah = mysqli_fetch_array($ambil);
    $foto_produk = $pecah['foto'];

    if (file_exists("foto_produk/$foto_produk")) {
        unlink("foto_produk/$foto_produk");
    }

    $hapus = mysqli_query($conn, "DELETE FROM produk WHERE id = '$id'");

    if ($hapus) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: 'Data mainan berhasil dihapus.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = 'admin.php';
            });
        </script>";
    } else {
        echo "<script>Swal.fire({ icon: 'error', title: 'Gagal', text: 'Database error.' }).then(() => { window.location = 'admin.php'; });</script>";
    }
} else {
    echo "<script>window.location = 'admin.php';</script>";
}
?>

</body>
</html>