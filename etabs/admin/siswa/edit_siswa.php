<?php
session_start();
require_once '../inc/koneksi.php';   // $koneksi (mysqli)
require_once '../inc/rupiah.php';    // fungsi rupiah()

/* ───────────────────────────────────────────────────────────── */
/* 1. Buat CSRF token sekali per sesi                           */
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

/* 2. Validasi & ambil parameter NIS dari URL                   */
if (!isset($_GET['kode']) || !ctype_digit($_GET['kode'])) {
    header('Location: index.php?page=MyApp/data_siswa'); exit;
}
$nis_url = $_GET['kode'];

/* 3. Ambil data siswa + kelas (prepared statement)             */
$stmt = $koneksi->prepare("
    SELECT s.*, k.kelas 
    FROM tb_siswa s
    JOIN tb_kelas k ON s.id_kelas = k.id_kelas
    WHERE s.nis = ?
    LIMIT 1
");
$stmt->bind_param('s', $nis_url);
$stmt->execute();
$data_cek = $stmt->get_result()->fetch_assoc();
if (!$data_cek) {                 // NIS tidak ada
    header('Location: index.php?page=MyApp/data_siswa'); exit;
}
$stmt->close();

/* 4. Saldo awal & saat ini (ditampilkan read-only)             */
$saldo_awal_tampil  = $data_cek['saldo'];
$saldo_saat_ini     = $data_cek['saldo'];
?>
<!-- ──────────────────────────────────────────────────────────── -->
<section class="content-header">
  <h1>Master Data <small>Siswa</small></h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
  </ol>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Ubah Siswa</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div>

    <form method="post">
      <input type="hidden" name="csrf" value="<?= $csrf; ?>">
      <div class="box-body">

        <div class="form-group">
          <label>NIS</label>
          <input type="text" name="nis" class="form-control"
                 value="<?= htmlspecialchars($data_cek['nis']); ?>" readonly>
        </div>

        <div class="form-group">
          <label>Saldo Awal</label>
          <input type="text" class="form-control"
                 value="<?= rupiah($saldo_awal_tampil); ?>" readonly>
        </div>

        <div class="form-group">
          <label>Saldo Saat Ini</label>
          <input type="text" class="form-control"
                 value="<?= rupiah($saldo_saat_ini); ?>" readonly>
        </div>

        <div class="form-group">
          <label>Nama Siswa</label>
          <input type="text" name="nama_siswa" class="form-control"
                 value="<?= htmlspecialchars($data_cek['nama_siswa']); ?>" required>
        </div>

        <div class="form-group">
          <label>Jenis Kelamin</label>
          <select name="jekel" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="LK" <?= $data_cek['jekel']=='LK'?'selected':''; ?>>Laki-laki</option>
            <option value="PR" <?= $data_cek['jekel']=='PR'?'selected':''; ?>>Perempuan</option>
          </select>
        </div>

        <div class="form-group">
          <label>Kelas</label>
          <select name="id_kelas" class="form-control" required>
            <option value="">-- Pilih --</option>
            <?php
            $rs = $koneksi->query("SELECT id_kelas, kelas FROM tb_kelas ORDER BY kelas");
            while ($r = $rs->fetch_assoc()): ?>
              <option value="<?= $r['id_kelas']; ?>"
                      <?= $data_cek['id_kelas']==$r['id_kelas']?'selected':''; ?>>
                <?= htmlspecialchars($r['kelas']); ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Tahun Masuk</label>
          <input type="number" name="th_masuk" class="form-control"
                 min="1900" max="<?= date('Y'); ?>"
                 value="<?= htmlspecialchars($data_cek['th_masuk']); ?>" required>
        </div>

        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control" required>
            <?php
              $st = ['Aktif','Lulus','Pindah'];
              foreach ($st as $s) {
                  $sel = $data_cek['status']===$s ? 'selected':'';
                  echo "<option value=\"$s\" $sel>$s</option>";
              }
            ?>
          </select>
        </div>

      </div><!-- /.box-body -->

      <div class="box-footer">
        <button type="submit" name="ubah" class="btn btn-success">Ubah</button>
        <a href="?page=MyApp/data_siswa" class="btn btn-warning">Batal</a>
      </div>
    </form>
  </div>
</div></div>
</section>

<?php
/* ───────────── 5. Proses update ketika SUBMIT ─────────────── */
if (isset($_POST['ubah'])) {

    /* 5a. CSRF check */
    if ($_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
        echo "<script>Swal.fire('Gagal','Token CSRF salah','error')</script>"; exit;
    }

    /* 5b. Sanitasi & binding */
    $nama_siswa = trim($_POST['nama_siswa']);
    $jekel      = $_POST['jekel'];
    $id_kelas   = intval($_POST['id_kelas']);
    $th_masuk   = intval($_POST['th_masuk']);
    $status     = $_POST['status'];
    $nis_fixed  = $nis_url;       // readonly

    $stmtU = $koneksi->prepare("
        UPDATE tb_siswa SET
            nama_siswa = ?, jekel = ?, id_kelas = ?,
            th_masuk   = ?, status = ?
        WHERE nis = ?
    ");
    $stmtU->bind_param('ssiiss',
        $nama_siswa, $jekel, $id_kelas,
        $th_masuk,   $status, $nis_fixed
    );

    if ($stmtU->execute()) {
        echo "<script>
            Swal.fire('Berhasil','Data diperbarui','success')
                 .then(()=>location='index.php?page=MyApp/data_siswa');
        </script>";
    } else {
        echo "<script>
            Swal.fire('Gagal','".htmlspecialchars($stmtU->error)."','error');
        </script>";
    }
    $stmtU->close();
}
?>
