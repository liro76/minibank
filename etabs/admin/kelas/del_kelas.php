<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['kode'])) {
    $id_kelas = mysqli_real_escape_string($koneksi, $_GET['kode']);

    // Cek apakah kelas ini digunakan oleh siswa lain
    $cek_relasi = mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM tb_siswa WHERE id_kelas='$id_kelas'");
    $relasi = mysqli_fetch_assoc($cek_relasi);

    if ($relasi['jml'] > 0) {
        echo "<script>
        Swal.fire({title: 'Tidak Bisa Dihapus',text: 'Kelas masih digunakan oleh siswa.',icon: 'warning',confirmButtonText: 'OK'})
        .then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_kelas';
            }
        });
        </script>";
    } else {
        $sql_hapus = "DELETE FROM tb_kelas WHERE id_kelas='$id_kelas'";
        $query_hapus = mysqli_query($koneksi, $sql_hapus);

        if ($query_hapus) {
            echo "<script>
            Swal.fire({title: 'Hapus Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'})
            .then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=MyApp/data_kelas';
                }
            });
            </script>";
        } else {
            echo "<script>
            Swal.fire({title: 'Hapus Data Gagal',text: 'Terjadi kesalahan: " . htmlspecialchars(mysqli_error($koneksi)) . "',icon: 'error',confirmButtonText: 'OK'})
            .then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=MyApp/data_kelas';
                }
            });
            </script>";
        }
    }
}
?>
