<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
$id_produk = mysqli_real_escape_string($conn, $_GET['id']);

if (isset($_SESSION['keranjang'][$id_produk])) {
    unset($_SESSION['keranjang'][$id_produk]);

    if(isset($_SESSION['status_login']) && $_SESSION['role'] == 'member'){
        $uid = $_SESSION['user_id'];
        
        if(empty($_SESSION['keranjang'])){
            mysqli_query($conn, "UPDATE users SET data_keranjang = NULL WHERE id = '$uid'");
        } else {
            $keranjang_json = json_encode($_SESSION['keranjang']);
            mysqli_query($conn, "UPDATE users SET data_keranjang = '$keranjang_json' WHERE id = '$uid'");
        }
    }

    echo "<script>
        Swal.fire({
            title: 'Dihapus',
            text: 'Barang dibuang dari keranjang',
            icon: 'warning',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location = 'keranjang.php';
        });
    </script>";
} else {
    echo "<script>window.location = 'keranjang.php';</script>";
}
?>

</body>
</html>