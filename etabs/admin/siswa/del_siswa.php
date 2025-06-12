<?php
if(isset($_GET['kode'])){
    // Hapus semua data tabungan siswa terlebih dahulu
    $sql_hapus_tabungan = "DELETE FROM tb_tabungan WHERE nis='".$_GET['kode']."'";
    mysqli_query($koneksi, $sql_hapus_tabungan);

    // Hapus data siswa
    $sql_hapus = "DELETE FROM tb_siswa WHERE nis='".$_GET['kode']."'";
    $query_hapus = mysqli_query($koneksi, $sql_hapus);

    if ($query_hapus) {
        echo "<script>
        Swal.fire({title: 'Hapus Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_siswa';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Hapus Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_siswa';
            }
        })</script>";
    }
}