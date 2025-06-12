<?php
if(isset($_GET['kode'])){
    // Ambil data setor dan nis sebelum hapus
    $sql_get = "SELECT nis, setor FROM tb_tabungan WHERE id_tabungan='".$_GET['kode']."'";
    $query_get = mysqli_query($koneksi, $sql_get);
    $data = mysqli_fetch_assoc($query_get);

    // Kurangi saldo siswa
    if ($data) {
        $sql_update_saldo = "UPDATE tb_siswa SET saldo = saldo - ".$data['setor']." WHERE nis = '".$data['nis']."'";
        mysqli_query($koneksi, $sql_update_saldo);
    }

    // Hapus data tabungan
    $sql_hapus = "DELETE FROM tb_tabungan WHERE id_tabungan='".$_GET['kode']."'";
    $query_hapus = mysqli_query($koneksi, $sql_hapus);

    if ($query_hapus) {
        echo "<script>
        Swal.fire({title: 'Hapus Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_setor';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Hapus Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_setor';
            }
        })</script>";
    }
}
?>
<!-- selesai -->