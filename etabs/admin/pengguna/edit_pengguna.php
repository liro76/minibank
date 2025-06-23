<?php
if (isset($_GET['kode'])) {
    $id_pengguna = mysqli_real_escape_string($koneksi, $_GET['kode']);
    $sql_cek = "SELECT * FROM tb_pengguna WHERE id_pengguna='$id_pengguna'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_cek = mysqli_fetch_array($query_cek, MYSQLI_BOTH);
}
?>

<section class="content-header">
    <h1>Pengguna Sistem</h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i><b>Bank Mini</b></a></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Pengguna</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>

                <form action="" method="post">
                    <div class="box-body">

                        <input type="hidden" name="id_pengguna" value="<?= $data_cek['id_pengguna']; ?>" />

                        <div class="form-group">
                            <label>Nama Pengguna</label>
                            <input class="form-control" name="nama_pengguna" value="<?= $data_cek['nama_pengguna']; ?>" required />
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input class="form-control" name="username" value="<?= $data_cek['username']; ?>" required />
                        </div>

                        <div class="form-group">
                            <label>Password (Isi jika ingin mengubah)</label>
                            <input type="password" class="form-control" name="password" id="pass" placeholder="Kosongkan jika tidak diubah" />
                            <input type="checkbox" onclick="togglePassword()"> Lihat Password
                        </div>

                        <div class="form-group">
                            <label>Level</label>
                            <select name="level" class="form-control" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="Administrator" <?= $data_cek['level'] == 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                                <option value="Petugas" <?= $data_cek['level'] == 'Petugas' ? 'selected' : '' ?>>Petugas</option>
                            </select>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
                        <a href="?page=MyApp/data_pengguna" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
if (isset($_POST['Ubah'])) {
    $id_pengguna = mysqli_real_escape_string($koneksi, $_POST['id_pengguna']);
    $nama_pengguna = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    
    // Cek apakah password diisi
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);
        // Gunakan password_hash di sistem nyata
        $sql_ubah = "UPDATE tb_pengguna SET 
            nama_pengguna='$nama_pengguna', 
            username='$username', 
            password='$password', 
            level='$level' 
            WHERE id_pengguna='$id_pengguna'";
    } else {
        $sql_ubah = "UPDATE tb_pengguna SET 
            nama_pengguna='$nama_pengguna', 
            username='$username', 
            level='$level' 
            WHERE id_pengguna='$id_pengguna'";
    }

    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_pengguna';
            }
        })</script>";
    } else {
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_pengguna';
            }
        })</script>";
    }
}
?>

<script>
function togglePassword() {
    var pass = document.getElementById("pass");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>
