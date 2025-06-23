<?php
// Validasi dan sanitasi parameter GET
if (isset($_GET['kode'])) {
    $id_kelas = mysqli_real_escape_string($koneksi, $_GET['kode']);
    $sql_cek = "SELECT * FROM tb_kelas WHERE id_kelas='$id_kelas'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_cek = mysqli_fetch_array($query_cek, MYSQLI_BOTH);

    if (!$data_cek) {
        echo "<script>
        Swal.fire({title: 'Data Tidak Ditemukan',text: 'ID Kelas tidak valid.',icon: 'error',confirmButtonText: 'OK'})
        .then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_kelas';
            }
        });
        </script>";
        exit;
    }
}
?>

<section class="content-header">
    <h1>
        Master Data
        <small>Kelas</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Kelas</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form action="" method="post">
                    <div class="box-body">
                        <input type="hidden" class="form-control" name="id_kelas" value="<?= htmlspecialchars($data_cek['id_kelas']) ?>" readonly />

                        <div class="form-group">
                            <label>Kelas</label>
                            <input class="form-control" name="kelas" value="<?= htmlspecialchars($data_cek['kelas']) ?>" required />
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
                        <a href="?page=MyApp/data_kelas" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
if (isset($_POST['Ubah'])) {
    $id_kelas = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);

    $sql_ubah = "UPDATE tb_kelas SET kelas='$kelas' WHERE id_kelas='$id_kelas'";
    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'})
        .then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_kelas';
            }
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: 'Terjadi kesalahan: " . htmlspecialchars(mysqli_error($koneksi)) . "',icon: 'error',confirmButtonText: 'OK'})
        .then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_kelas';
            }
        });
        </script>";
    }
}
?>
