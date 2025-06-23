<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../inc/koneksi.php';   // $koneksi (mysqli)

/* --- buat token CSRF jika belum ada --- */
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

/* ---------- proses simpan ---------- */
if (isset($_POST['Simpan'])) {
    /* 1. CSRF check */
    if ($_POST['csrf'] !== $csrf) {
        echo "<script>Swal.fire('Gagal','CSRF token salah','error')</script>"; exit;
    }

    /* 2. Validasi & sanitasi */
    $kelas = trim($_POST['kelas'] ?? '');
    if ($kelas === '') {
        echo "<script>Swal.fire('Gagal','Nama kelas tidak boleh kosong','error')</script>"; exit;
    }

    /* 3. Cek duplikasi */
    $cek = $koneksi->prepare("SELECT 1 FROM tb_kelas WHERE kelas = ? LIMIT 1");
    $cek->bind_param('s', $kelas);
    $cek->execute();
    if ($cek->get_result()->num_rows) {
        echo "<script>
            Swal.fire('Gagal','Kelas sudah ada','error')
                 .then(()=>location='index.php?page=MyApp/add_kelas');
        </script>"; exit;
    }
    $cek->close();

    /* 4. Simpan (prepared-statement) */
    $stmt = $koneksi->prepare("INSERT INTO tb_kelas (kelas) VALUES (?)");
    $stmt->bind_param('s', $kelas);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire('Berhasil','Data tersimpan','success')
                 .then(()=>location='index.php?page=MyApp/data_kelas');
        </script>";
    } else {
        echo "<script>
            Swal.fire('Gagal','".htmlspecialchars($stmt->error)."','error')
                 .then(()=>location='index.php?page=MyApp/add_kelas');
        </script>";
    }
    $stmt->close();
}
?>

<!-- ====== Form HTML ====== -->
<section class="content">
  <div class="row"><div class="col-md-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Tambah Kelas</h3>
      </div>

      <form method="post">
        <input type="hidden" name="csrf" value="<?= $csrf; ?>">
        <div class="box-body">
          <div class="form-group">
            <label>Kelas</label>
            <input type="text" name="kelas" class="form-control" placeholder="Kelas" required>
          </div>
        </div>
        <div class="box-footer">
          <button type="submit" name="Simpan" class="btn btn-info">Simpan</button>
          <a href="?page=MyApp/data_kelas" class="btn btn-warning">Batal</a>
        </div>
      </form>
    </div>
  </div></div>
</section>
