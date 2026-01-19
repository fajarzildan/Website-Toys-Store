# 🧸 Toys Store - E-Commerce Website

Website penjualan mainan online berbasis web native PHP. Project ini dibuat untuk memenuhi tugas mata kuliah.

## 🚀 Fitur Utama

### Halaman Pengunjung (User)
* **Pendaftaran & Login:** Sistem member area yang aman.
* **Katalog Produk:** Menampilkan produk dengan filter kategori dan rekomendasi umur.
* **Pencarian:** Fitur cari mainan berdasarkan nama.
* **Keranjang Belanja:** Real-time stock check (tidak bisa order melebihi stok).
* **Checkout WhatsApp:** Integrasi pemesanan langsung ke WhatsApp Admin.
* **Manajemen Profil:** Edit foto profil dan data diri.

### Halaman Admin
* **Dashboard Statistik:** Ringkasan total produk, member, dan stok menipis.
* **Manajemen Produk:** Tambah, Edit, dan Hapus data mainan (termasuk upload foto).
* **Notifikasi Stok:** Peringatan otomatis jika stok barang kurang dari 3.
* **Cetak Laporan:** Fitur print data stok barang.

## 🛠️ Teknologi yang Digunakan
* **Backend:** PHP Native (No Framework)
* **Database:** MySQL
* **Frontend:** Bootstrap 5 (Responsive Design)
* **Icons:** FontAwesome 6
* **Alerts:** SweetAlert2 (Popup interaktif)
* **Animation:** AOS (Animate On Scroll)

## 📦 Cara Instalasi

1.  **Download Source Code**
    Clone repository ini atau download sebagai ZIP lalu ekstrak di folder `htdocs` (jika menggunakan XAMPP).

2.  **Import Database**
    * Buka PHPMyAdmin atau HeidiSQL.
    * Buat database baru dengan nama `db_tokomainan`.
    * Import file `db_tokomainan.sql` yang ada di dalam folder project ini.

3.  **Konfigurasi Koneksi**
    Buka file `koneksi.php` dan sesuaikan settingan database kamu:
    ```php
    $host = "localhost";
    $user = "root";     // Default XAMPP
    $pass = "";         // Kosongkan jika default
    $db   = "db_tokomainan";
    ```

4.  **Jalankan Website**
    Buka browser dan akses `http://localhost/nama_folder_project`.

## ⚙️ Konfigurasi Penting (Privasi)

Demi keamanan dan privasi, beberapa data kredensial telah disensor/diganti dengan dummy data. Silakan sesuaikan file berikut agar fitur berjalan 100%:

1.  **Nomor WhatsApp Admin (Checkout)**
    * Buka file `proses_order.php`.
    * Cari variabel `$nomor_toko` dan ubah menjadi nomor WhatsApp kamu (format: 628xxx).

2.  **Link Chat WhatsApp (Sidebar & Detail)**
    * Buka file `profil.php` dan `detail.php`.
    * Cari link `wa.me/...` dan sesuaikan nomornya.

3.  **Email Sender (Fitur Lupa Password/Register)**
    * Buka file `daftar.php` (jika menggunakan PHPMailer).
    * Masukkan email dan password aplikasi (App Password) milikmu pada bagian konfigurasi SMTP.

## 👤 Author
Dibuat dengan ❤️ oleh **Fajar Zildan Ananta**
Teknik Informatika - Universitas Islam As-Syafi'iyah
