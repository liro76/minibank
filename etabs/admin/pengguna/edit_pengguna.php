<?php
// FILE: admin/pengguna/edit_pengguna.php

// 1. Logika untuk memproses form saat disubmit (diletakkan di atas)
if (isset($_POST['Ubah'])) {
    // Ambil semua data dari form
    $id_pengguna = $_POST['id_pengguna'];
    $nama_pengguna = $_POST['nama_pengguna'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'] ?? null; // Gunakan null coalescing untuk level yang mungkin di-disable

    // Cek apakah pengguna yang diedit adalah admin, untuk mencegah perubahan level
    $stmt_check = $koneksi->prepare("SELECT level FROM tb_pengguna WHERE id_pengguna = ?");
    $stmt_check->bind_param('i', $id_pengguna);
    $stmt_check->execute();
    $user_being_edited = $stmt_check->get_result()->fetch_assoc();
    $stmt_check->close();

    // Jika yang diedit adalah Administrator, level tidak boleh diubah. Ambil level asli.
    if ($user_being_edited && $user_being_edited['level'] === 'Administrator') {
        $level = 'Administrator';
    }

    // Persiapkan query dasar dan tipe parameter
    $sql_parts = [];
    $types = "";
    $params = [];

    // Tambahkan field yang pasti diubah
    $sql_parts[] = "nama_pengguna = ?";
    $types .= "s";
    $params[] = $nama_pengguna;

    $sql_parts[] = "username = ?";
    $types .= "s";
    $params[] = $username;

    // Jika password diisi, hash dan tambahkan ke query
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_parts[] = "password = ?";
        $types .= "s";
        $params[] = $hashed_password;
    }

    // Tambahkan level ke query
    $sql_parts[] = "level = ?";
    $types .= "s";
    $params[] = $level;

    // Gabungkan semua bagian query menjadi satu
    $sql_ubah = "UPDATE tb_pengguna SET " . implode(', ', $sql_parts) . " WHERE id_pengguna = ?";
    $types .= "i";
    $params[] = $id_pengguna;

    // Eksekusi dengan prepared statement
    $stmt_update = $koneksi->prepare($sql_ubah);
    $stmt_update->bind_param($types, ...$params);

    if ($stmt_update->execute()) {
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
    $stmt_update->close();

} else {
    // 2. Logika untuk menampilkan data awal di form (saat halaman pertama kali dibuka)
    if (isset($_GET['kode'])) {
        $id_pengguna = $_GET['kode'];
        // Mengambil data dengan aman
        $stmt_get = $koneksi->prepare("SELECT * FROM tb_pengguna WHERE id_pengguna = ?");
        $stmt_get->bind_param('i', $id_pengguna);
        $stmt_get->execute();
        $result_get = $stmt_get->get_result();
        $data_cek = $result_get->fetch_assoc();
        $stmt_get->close();
    } else {
        // Redirect jika tidak ada kode
        echo "<script>window.location = 'index.php?page=MyApp/data_pengguna';</script>";
    }
?>

<section class="content-header">
    <h1>Ubah Pengguna Sistem</h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
        <li><a href="?page=MyApp/data_pengguna">Pengguna</a></li>
        <li class="active">Ubah</li>
    </ol>
</section>

<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Ubah Pengguna: <?= htmlspecialchars($data_cek['nama_pengguna']); ?></h3>
        </div>

        <form action="" method="post">
            <div class="box-body">
                <input type="hidden" name="id_pengguna" value="<?= $data_cek['id_pengguna']; ?>" />

                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input class="form-control" name="nama_pengguna" value="<?= htmlspecialchars($data_cek['nama_pengguna']); ?>" required />
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input class="form-control" name="username" value="<?= htmlspecialchars($data_cek['username']); ?>" required />
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" id="pass" placeholder="Kosongkan jika password tidak diubah" />
                    <input type="checkbox" id="lihatpass" onclick="togglePassword()"> Lihat Password
                </div>

                <div class="form-group">
                    <label>Level</label>
                    <?php 
                    // Jika yang diedit adalah Administrator, kunci (disable) dropdown level
                    // Ini mencegah Admin mengubah levelnya sendiri atau diubah oleh admin lain.
                    if ($data_cek['level'] == 'Administrator'): 
                    ?>
                        <input type="hidden" name="level" value="Administrator">
                        <input class="form-control" value="Administrator" readonly title="Level Administrator tidak dapat diubah">
                    <?php else: ?>
                        <select name="level" class="form-control" required>
                            <option value="Petugas" <?= $data_cek['level'] == 'Petugas' ? 'selected' : '' ?>>Petugas</option>
                            <option value="Administrator" <?= $data_cek['level'] == 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <div class="box-footer">
                <input type="submit" name="Ubah" value="Simpan Perubahan" class="btn btn-success">
                <a href="?page=MyApp/data_pengguna" class="btn btn-default">Batal</a>
            </div>
        </form>
    </div>
</section>

<?php
} // Menutup blok 'else' dari bagian paling atas
?>

<script>
function togglePassword() {
    var passField = document.getElementById("pass");
    if (passField.type === "password") {
        passField.type = "text";
    } else {
        passField.type = "password";
    }
}
</script>