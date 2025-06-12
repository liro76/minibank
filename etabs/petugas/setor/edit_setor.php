<?php


if(isset($_GET['kode'])){
    $sql_cek = "select s.nis, s.nama_siswa, t.id_tabungan, t.setor, t.tgl, t.petugas from 
    tb_siswa s join tb_tabungan t on s.nis=t.nis 
    where jenis ='ST' and id_tabungan='".$_GET['kode']."'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
}

$tanggal = date("Y-m-d");
?>

<!-- ...existing HTML code... -->

<?php

if (isset ($_POST['Ubah'])){

    //menangkap post setor
    $setor=$_POST['setor'];
    //membuang Rp dan Titik
    $setor_hasil=preg_replace("/[^0-9]/", "", $setor);

    // Ambil data setor lama dan nis lama
    $sql_get = "SELECT nis, setor FROM tb_tabungan WHERE id_tabungan='".$_POST['id_tabungan']."'";
    $query_get = mysqli_query($koneksi, $sql_get);
    $data_lama = mysqli_fetch_assoc($query_get);

    // Jika siswa tidak diganti
    if ($data_lama['nis'] == $_POST['nis']) {
        // Update saldo: saldo = saldo - setor_lama + setor_baru
        $sql_update_saldo = "UPDATE tb_siswa SET saldo = saldo - ".$data_lama['setor']." + ".$setor_hasil." WHERE nis = '".$data_lama['nis']."'";
        mysqli_query($koneksi, $sql_update_saldo);
    } else {
        // Jika siswa diganti, saldo siswa lama dikurangi, saldo siswa baru ditambah
        $sql_kurang_lama = "UPDATE tb_siswa SET saldo = saldo - ".$data_lama['setor']." WHERE nis = '".$data_lama['nis']."'";
        mysqli_query($koneksi, $sql_kurang_lama);
        $sql_tambah_baru = "UPDATE tb_siswa SET saldo = saldo + ".$setor_hasil." WHERE nis = '".$_POST['nis']."'";
        mysqli_query($koneksi, $sql_tambah_baru);
    }

    $sql_ubah = "UPDATE tb_tabungan SET
        nis='".$_POST['nis']."',
        setor='".$setor_hasil."',
        tgl='".$tanggal."'
        WHERE id_tabungan='".$_POST['id_tabungan']."'";
    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    mysqli_close($koneksi);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_setor';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_setor';
            }
        })</script>";
    }
}

?>