<?php
session_start();
include 'koneksi.php';

if(isset($_GET['id']) && isset($_GET['aksi'])){
    $id_produk = mysqli_real_escape_string($conn, $_GET['id']);
    $aksi = mysqli_real_escape_string($conn, $_GET['aksi']);

    $ambil = mysqli_query($conn, "SELECT stok FROM produk WHERE id='$id_produk'");
    $pecah = mysqli_fetch_array($ambil);
    $stok_database = $pecah['stok'];

    if(isset($_SESSION['keranjang'][$id_produk])){
        $jumlah_di_keranjang = $_SESSION['keranjang'][$id_produk];

        if($aksi == 'tambah'){
            if($jumlah_di_keranjang + 1 <= $stok_database){
                $_SESSION['keranjang'][$id_produk] += 1;
                redirect_back();
            } else {
                echo '<!DOCTYPE html>
                <html>
                <head>
                    <title>Stok Penuh</title>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "warning",
                            title: "Stok Mentok!",
                            text: "Maaf, stok barang ini sudah habis di gudang.",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location = "keranjang.php";
                        });
                    </script>
                </body>
                </html>';
                exit();
            }
        } 
        elseif($aksi == 'kurang'){
            if($jumlah_di_keranjang > 1){
                $_SESSION['keranjang'][$id_produk] -= 1;
            }
            redirect_back();
        }
    }

    if(isset($_SESSION['status_login']) && $_SESSION['role'] == 'member'){
        $uid = $_SESSION['user_id'];
        $keranjang_json = json_encode($_SESSION['keranjang']);
        mysqli_query($conn, "UPDATE users SET data_keranjang = '$keranjang_json' WHERE id = '$uid'");
    }
}

function redirect_back(){
    header("location:keranjang.php");
    exit();
}

redirect_back();
?>