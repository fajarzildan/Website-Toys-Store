<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status_login']) || empty($_SESSION['keranjang'])){
    
    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <title>Dialihkan...</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>body { font-family: sans-serif; background: #f0f2f5; }</style>
    </head>
    <body>';

    if(!isset($_SESSION['status_login'])){
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Kamu Belum Login',
                text: 'Silakan login dulu untuk melanjutkan checkout ya!',
                confirmButtonText: 'Login Sekarang',
                confirmButtonColor: '#028BEF'
            }).then(() => { window.location='login.php'; });
        </script>";
    } 
    else if(empty($_SESSION['keranjang'])){
        echo "<script>
            Swal.fire({
                icon: 'question',
                title: 'Keranjang Kosong',
                text: 'Wah, kamu belum belanja mainan apapun nih.',
                confirmButtonText: 'Mulai Belanja',
                confirmButtonColor: '#028BEF'
            }).then(() => { window.location='index.php'; });
        </script>";
    }

    echo '</body></html>';
    exit();
}

$uid = $_SESSION['user_id'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id='$uid'");
$user = mysqli_fetch_array($query_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Toys Store Fajar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }</style>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5 mb-5">
        <h2 class="fw-bold mb-4 text-center">Checkout Pengiriman</h2>
        <div class="row">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 rounded-4 mb-4" data-aos="fade-right">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">📦 Informasi Penerima</h4>
                        
                        <form action="proses_order.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?php echo $user['nama']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nomor WhatsApp</label>
                                <input type="number" name="no_hp" class="form-control" value="<?php echo $user['no_hp']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3" required><?php echo $user['alamat']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan (Opsional)</label>
                                <input type="text" name="catatan" class="form-control" placeholder="Cth: Jangan dibanting ya, pagar hitam...">
                            </div>
                            <div class="mb-3 form-check bg-light p-3 rounded border border-dashed">
                                <input type="checkbox" class="form-check-input" name="bungkus_kado" value="Ya" id="kado">
                                <label class="form-check-label fw-bold" for="kado">
                                    🎁 Request Bungkus Kado? (+ Rp 5.000)
                                </label>
                                <div class="form-text text-muted">Kami akan bungkus dengan kertas kado motif lucu</div>
                            </div>
                            
                            <button type="submit" name="checkout" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow mt-3">
                                <i class="fab fa-whatsapp me-2"></i> Lanjut ke Pembayaran
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 rounded-4" data-aos="fade-left">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Ringkasan Belanja</h5>
                        <ul class="list-group list-group-flush mb-3">
                            <?php
                            $total_belanja = 0;
                            foreach ($_SESSION['keranjang'] as $id_produk => $jumlah):
                                $ambil = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'");
                                $pecah = mysqli_fetch_array($ambil);
                                $subtotal = $pecah['harga'] * $jumlah;
                                $total_belanja += $subtotal;
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <small class="fw-bold"><?php echo $pecah['nama_produk']; ?></small><br>
                                    <small class="text-muted"><?php echo $jumlah; ?> x Rp <?php echo number_format($pecah['harga']); ?></small>
                                </div>
                                <span class="fw-bold text-primary">Rp <?php echo number_format($subtotal); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-3">
                            <span>Total Barang</span>
                            <span class="text-success">Rp <?php echo number_format($total_belanja); ?></span>
                        </div>
                        <small class="text-muted fst-italic">*Belum termasuk ongkir & biaya kado</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> AOS.init({ duration: 800, once: true }); </script>
</body>
</html>