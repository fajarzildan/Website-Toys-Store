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

    $pesan  = "*Halo Admin Toys Store, Saya mau Checkout Orderan* 🛒\n\n";
    $pesan .= "*DATA PENERIMA:*\n";
    $pesan .= "👤 Nama: $nama\n";
    $pesan .= "📱 No HP: $no_hp\n";
    $pesan .= "🏠 Alamat: $alamat\n";
    $pesan .= "📝 Catatan: $catatan\n";
    $pesan .= "🎁 Bungkus Kado: $status_kado\n\n";
    
    $pesan .= "*DETAIL BARANG:*\n";

    $total_belanja = 0;

    foreach ($_SESSION['keranjang'] as $id_produk => $jumlah){
        $ambil = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'");
        $pecah = mysqli_fetch_array($ambil);
        $subtotal = $pecah['harga'] * $jumlah;
        
        $pesan .= "- " . $pecah['nama_produk'] . " (" . $jumlah . "x) = Rp " . number_format($subtotal,0,',','.') . "\n";
        $total_belanja += $subtotal;

        mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah, terjual = terjual + $jumlah WHERE id='$id_produk'");
    }

    $grand_total = $total_belanja + $biaya_kado;

    $pesan .= "\n💰 Subtotal Barang: Rp " . number_format($total_belanja,0,',','.');
    if($biaya_kado > 0){
        $pesan .= "\n🎀 Biaya Kado: Rp " . number_format($biaya_kado,0,',','.');
    }
    $pesan .= "\n\n*TOTAL BAYAR: Rp " . number_format($grand_total,0,',','.') . "*\n";
    $pesan .= "----------------------------------\n";
    $pesan .= "_Mohon info nomor rekening & ongkirnya ya min_ 🙏";

    $nomor_toko = "NOMOR_ADMIN_DISINI";
    
    unset($_SESSION['keranjang']);
    
    if(isset($_SESSION['user_id'])){
        $uid = $_SESSION['user_id'];
        mysqli_query($conn, "UPDATE users SET data_keranjang = NULL WHERE id = '$uid'");
    }
    
    header("location:https://wa.me/$nomor_toko?text=".urlencode($pesan));
}
?>