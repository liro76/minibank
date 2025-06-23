<?php
session_start();
require_once '../inc/koneksi.php';

// ===== 1. CSRF Token Check (WAJIB) =====
if (!isset($_GET['kode']) || !isset($_GET['csrf']) || $_GET['csrf'] !== ($_SESSION['csrf'] ?? '')) {
    echo "<script>
        Swal.fire({
            title: 'Akses tidak sah!',
            text: 'Token CSRF tidak valid.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location = 'index.php?page=MyApp/data_siswa';
        });
    </script>";
    exit;
}

// ===== 2. Validasi NIS =====
$nis = $_GET['kode'];
if (!ctype_digit($nis)) {
    echo "<script>
        Swal.fire({
            title: 'Parameter tidak valid',
            text: 'NIS harus berupa angka.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location = 'index.php?page=MyApp/data_siswa';
        });
    </script>";
    exit;
}

// ===== 3. Eksekusi Transaksi Penghapusan =====
$koneksi->begin_transaction();

try {
    // Jika tidak menggunakan ON DELETE CASCADE, hapus tabungan dulu
    $stmt_tabungan = $koneksi->prepare("DELETE FROM tb_tabungan WHERE nis = ?");
    $stmt_tabungan->bind_param("s", $nis);
    if (!$stmt_tabungan->execute()) {
        throw new Exception("Gagal hapus tabungan: " . $stmt_tabungan->error);
    }

    // Hapus data siswa
    $stmt_siswa = $koneksi->prepare("DELETE FROM tb_siswa WHERE nis = ?");
    $stmt_siswa->bind_param("s", $nis);
    if (!$stmt_siswa->execute()) {
        throw new Exception("Gagal hapus siswa: " . $stmt_siswa->error);
    }

    $koneksi->commit();

    echo "<script>
        Swal.fire({
            title: 'Hapus Data Berhasil',
            text: '',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location = 'index.php?page=MyApp/data_siswa';
        });
    </script>";

} catch (Exception $e) {
    $koneksi->rollback();
    echo "<script>
        Swal.fire({
            title: 'Hapus Data Gagal',
            text: '" . htmlspecialchars($e->getMessage()) . "',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location = 'index.php?page=MyApp/data_siswa';
        });
    </script>";
}
?>
