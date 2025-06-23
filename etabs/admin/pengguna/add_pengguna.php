<?php
/* ───────────── Inisialisasi ───────────── */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../inc/koneksi.php';   // objek $koneksi (mysqli)

/* CSRF token */
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

/* ───────────── Proses penyimpanan ───────────── */
if (isset($_POST['Simpan'])) {

    /* 1. CSRF check */
    if ($_POST['csrf'] !== $csrf) {
        echo "<script>Swal.fire('Gagal','Token CSRF salah','error');</script>";
        exit;
    }

    /* 2. Ambil & validasi input */
    $nama   = trim($_POST['nama_pengguna'] ?? '');
    $user   = trim($_POST['username']      ?? '');
    $pass   = $_POST['password']           ?? '';
    $level  = $_POST['level']              ?? '';

    if ($nama==='' || $user==='' || $pass==='' || !in_array($level, ['Administrator','Petugas'])) {
        echo "<script>Swal.fire('Gagal','Lengkapi semua isian','error');</script>";
        exit;
    }

    /* 3. Cek duplikasi username */
    $cek = $koneksi->prepare("SELECT 1 FROM tb_pengguna WHERE username=? LIMIT 1");
    $cek->bind_param('s', $user);
    $cek->execute();
    if ($cek->get_result()->num_rows) {
        echo "<script>
        Swal.fire('Gagal','Username sudah dipakai','error')
             .then(()=>location='index.php?page=MyApp/add_pengguna');
        </script>";
        exit;
    }
    $cek->close();

    /* 4. Hash password */
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    /* 5. Simpan data dengan prepared-statement */
    $stmt = $koneksi->prepare("
        INSERT INTO tb_pengguna (nama_pengguna, username, password, level)
        VALUES (?,?,?,?)
    ");
    $stmt->bind_param('ssss', $nama, $user, $hash, $level);

    if ($stmt->execute()) {
        echo "<script>
        Swal.fire('Berhasil','Data tersimpan','success')
             .then(()=>location='index.php?page=MyApp/data_pengguna');
        </script>";
    } else {
        echo "<script>
        Swal.fire('Gagal','".htmlspecialchars($stmt->error)."','error')
             .then(()=>location='index.php?page=MyApp/add_pengguna');
        </script>";
    }
    $stmt->close();
}
?>

<!-- ───────────── Form HTML ───────────── -->
<section class="content-header">
  <h1>Pengguna Sistem</h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
  </ol>
</section>

<section class="content">
  <div class="row"><div class="col-md-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Tambah Pengguna</h3>
      </div>

      <form method="post">
        <input type="hidden" name="csrf" value="<?= $csrf; ?>">
        <div class="box-body">

          <div class="form-group">
            <label>Nama Pengguna</label>
            <input type="text" name="nama_pengguna" class="form-control"
                   placeholder="Nama Pengguna" required>
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                   placeholder="Username" required>
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control"
                   placeholder="Password" required minlength="6">
          </div>

          <div class="form-group">
            <label>Level</label>
            <select name="level" class="form-control" required>
              <option value="">-- Pilih Level --</option>
              <option value="Administrator">Administrator</option>
              <option value="Petugas">Petugas</option>
            </select>
          </div>

        </div>
        <div class="box-footer">
          <button type="submit" name="Simpan" class="btn btn-info">Simpan</button>
          <a href="?page=MyApp/data_pengguna" class="btn btn-warning">Batal</a>
        </div>
      </form>
    </div>
  </div></div>
</section>
