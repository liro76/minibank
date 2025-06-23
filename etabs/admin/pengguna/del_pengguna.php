<?php
session_start();
require_once '../inc/koneksi.php';

// Validasi parameter & CSRF token
if (isset($_GET['kode'], $_GET['csrf']) && $_GET['csrf'] === ($_SESSION['csrf'] ?? '')) {
    $id = mysqli_real_escape_string($koneksi, $_GET['kode']);

    // Eksekusi DELETE
    $sql_hapus = "DELETE FROM tb_pengguna WHERE id_pengguna = '$id'";
    $query_hapus = mysqli_query($koneksi, $sql_hapus);

    $success = $query_hapus;
    $msg = $success ? 'Hapus Data Berhasil' : 'Hapus Data Gagal';
    $icon = $success ? 'success' : 'error';
    $redirect = 'index.php?page=MyApp/data_pengguna';

} else {
    // CSRF tidak valid atau parameter hilang
    $msg = 'Permintaan tidak valid.';
    $icon = 'error';
    $redirect = 'index.php?page=MyApp/data_pengguna';
}
?>

<!-- Output JavaScript (SweetAlert) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    title: <?= json_encode($msg) ?>,
    icon: <?= json_encode($icon) ?>,
    confirmButtonText: 'OK'
}).then(result => {
    if (result.isConfirmed) {
        window.location.href = <?= json_encode($redirect) ?>;
    }
});
</script>
