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

$ambil = mysqli_query($conn, "SELECT stok FROM produk WHERE id='$id_produk'");
$pecah = mysqli_fetch_array($ambil);

if (!$pecah) {
    echo "<script>window.location='index.php';</script>";
    exit();
}

$stok_database = $pecah['stok'];

$qty_sekarang = isset($_SESSION['keranjang'][$id_produk]) ? $_SESSION['keranjang'][$id_produk] : 0;

if($qty_sekarang + 1 <= $stok_database){
    
    if(isset($_SESSION['keranjang'][$id_produk])){
        $_SESSION['keranjang'][$id_produk] += 1;
    } else {
        $_SESSION['keranjang'][$id_produk] = 1;
    }

    if(isset($_SESSION['status_login']) && $_SESSION['role'] == 'member'){
        $uid = $_SESSION['user_id'];
        $keranjang_json = json_encode($_SESSION['keranjang']);
        mysqli_query($conn, "UPDATE users SET data_keranjang = '$keranjang_json' WHERE id = '$uid'");
    }

    echo "<script>
        Swal.fire({
            title: 'Berhasil',
            text: 'Produk masuk keranjang',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location = 'keranjang.php';
        });
    </script>";

} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Stok Tidak Cukup',
            text: 'Stok barang ini hanya tersisa $stok_database pcs.',
            confirmButtonText: 'Oke'
        }).then(() => {
            window.history.back();
        });
    </script>";
}
?>

</body>
</html>