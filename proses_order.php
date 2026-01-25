<?php
session_start();
include 'koneksi.php';

if(isset($_POST['checkout'])){
    
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);
    
    $biaya_kado = 0;
    $status_kado = "Tidak";
    
    if(isset($_POST['bungkus_kado'])){
        $status_kado = "Ya (+Rp 5.000)";
        $biaya_kado = 5000;
    }

    $e_cart   = urldecode("%F0%9F%9B%92"); // 🛒
    $e_org    = urldecode("%F0%9F%91%A4"); // 👤
    $e_hp     = urldecode("%F0%9F%93%B1"); // 📱
    $e_rmh    = urldecode("%F0%9F%8F%A0"); // 🏠
    $e_note   = urldecode("%F0%9F%93%9D"); // 📝
    $e_gift   = urldecode("%F0%9F%8E%81"); // 🎁
    $e_money  = urldecode("%F0%9F%92%B0"); // 💰
    $e_pita   = urldecode("%F0%9F%8E%80"); // 🎀
    $e_tangan = urldecode("%F0%9F%99%8F"); // 🙏
    $e_tok    = urldecode("%F0%9F%8E%A4"); // 🎙️ (Toko)

    $pesan  = "*Halo Admin Toys Store, Saya mau Checkout Orderan* $e_cart\n\n";
    $pesan .= "*DATA PENERIMA:*\n";
    $pesan .= "$e_org Nama: $nama\n";
    $pesan .= "$e_hp No HP: $no_hp\n";
    $pesan .= "$e_rmh Alamat: $alamat\n";
    $pesan .= "$e_note Catatan: $catatan\n";
    $pesan .= "$e_gift Bungkus Kado: $status_kado\n\n";
    $pesan .= "*DETAIL BARANG:*\n";

    $total_belanja = 0;

    if (!empty($_SESSION['keranjang'])) {
        foreach ($_SESSION['keranjang'] as $id_produk => $jumlah){
            $ambil = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'");
            $pecah = mysqli_fetch_array($ambil);
            
            $subtotal = $pecah['harga'] * $jumlah;
            
            $pesan .= "• " . $pecah['nama_produk'] . " (" . $jumlah . "x) = Rp " . number_format($subtotal,0,',','.') . "\n";
            $total_belanja += $subtotal;

            mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah, terjual = terjual + $jumlah WHERE id='$id_produk'");
        }
    }

    $grand_total = $total_belanja + $biaya_kado;

    $pesan .= "\n$e_money Subtotal Barang: Rp " . number_format($total_belanja,0,',','.');
    if($biaya_kado > 0){
        $pesan .= "\n$e_pita Biaya Kado: Rp " . number_format($biaya_kado,0,',','.');
    }
    $pesan .= "\n\n*TOTAL BAYAR: Rp " . number_format($grand_total,0,',','.') . "*\n";
    $pesan .= "----------------------------------\n";
    $pesan .= "_Mohon info nomor rekening & ongkirnya ya min_ $e_tangan";

    $nomor_toko = "NOMOR_ADMIN_DISINI";
    
    unset($_SESSION['keranjang']);
    
    if(isset($_SESSION['user_id'])){
        $uid = $_SESSION['user_id'];
        mysqli_query($conn, "UPDATE users SET data_keranjang = NULL WHERE id = '$uid'");
    }
    
    echo "<script>
        window.location = 'https://wa.me/$nomor_toko?text=".urlencode($pesan)."';
    </script>";
}
?>
