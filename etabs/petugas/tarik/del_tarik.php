<?php
if(isset($_GET['kode'])){
    // Ambil data tarikan yang akan dihapus
    $sql_get = $koneksi->query("SELECT nis, tarik FROM tb_tabungan WHERE id_tabungan='".$_GET['kode']."'");
    $data = $sql_get->fetch_assoc();
    $nis = $data['nis'];
    $tarik = $data['tarik'];

    // Kembalikan saldo siswa
    $sql_update = $koneksi->query("UPDATE tb_siswa SET saldo = saldo + $tarik WHERE nis = '$nis'");

    // Hapus data tarikan
    $sql_hapus = "DELETE FROM tb_tabungan WHERE id_tabungan='".$_GET['kode']."'";
    $query_hapus = mysqli_query($koneksi, $sql_hapus);

    if ($query_hapus) {
        echo "<script>
        Swal.fire({title: 'Hapus Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_tarik';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Hapus Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_tarik';
            }
        })</script>";
    }
}
?>