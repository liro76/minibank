<section class="content-header">
    <h1>
        Master Data
        <small>Siswa</small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>Bank Mini</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Siswa</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            <i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form action="" method="post">
                    <div class="box-body">
                        <div class="form-group">
                            <label>NIS</label>
                            <input type="number" name="nis" class="form-control" placeholder="NIS" required min="1" step="1">
                        </div>

                        <div class="form-group">
                            <label>Saldo Awal (Rp.)</label>
                            <input type="number" name="saldo_awal_input" class="form-control" placeholder="Contoh: 100000" min="0" step="1" value="0" required>
                            <small class="form-text text-muted">Nilai ini akan disimpan sebagai saldo awal siswa.</small>
                        </div>

                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <input type="text" name="nama_siswa" class="form-control" placeholder="Nama Siswa" required>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jekel" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="LK">Laki-laki</option>
                                <option value="PR">Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="id_kelas" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                $hasil = $koneksi->query("SELECT * FROM tb_kelas ORDER BY kelas ASC");
                                while ($row = $hasil->fetch_assoc()) {
                                    echo "<option value='{$row['id_kelas']}'>".htmlspecialchars($row['kelas'])."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tahun Masuk</label>
                            <input type="number" name="th_masuk" class="form-control" placeholder="Th Masuk" min="1900" max="<?= date('Y'); ?>" required>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
                        <a href="?page=MyApp/data_siswa" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<?php
require_once '../inc/koneksi.php';
require_once '../inc/rupiah.php';

if (isset($_POST['Simpan'])) {
    $nis     = trim($_POST['nis']);
    $nama    = trim($_POST['nama_siswa']);
    $jekel   = $_POST['jekel'] ?? '';
    $kelas   = intval($_POST['id_kelas']);
    $thMasuk = intval($_POST['th_masuk']);
    $saldo   = max(0, intval($_POST['saldo_awal_input']));

    // Validasi sederhana server-side
    if (!ctype_digit($nis) || !$nama || !in_array($jekel, ['LK', 'PR'])) {
        echo "<script>
                Swal.fire('Data tidak valid', 'Periksa kembali inputan.', 'error');
              </script>";
        return;
    }

    // Cek duplikasi NIS
    $cek = $koneksi->prepare("SELECT 1 FROM tb_siswa WHERE nis = ? LIMIT 1");
    $cek->bind_param('s', $nis);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
        echo "<script>
                Swal.fire('Gagal', 'NIS sudah terpakai', 'error');
              </script>";
        return;
    }
    $cek->close();

    // Eksekusi INSERT menggunakan prepared statement
    $stmt = $koneksi->prepare("
        INSERT INTO tb_siswa (nis, nama_siswa, jekel, id_kelas, status, th_masuk, saldo)
        VALUES (?, ?, ?, ?, 'Aktif', ?, ?)
    ");
    $stmt->bind_param('sssiii', $nis, $nama, $jekel, $kelas, $thMasuk, $saldo);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire('Sukses','Data tersimpan','success')
                    .then(() => location='index.php?page=MyApp/data_siswa');
              </script>";
    } else {
        echo "<script>
                Swal.fire('Gagal','".htmlspecialchars($stmt->error)."','error')
                    .then(() => location='index.php?page=MyApp/add_siswa');
              </script>";
    }

    $stmt->close();
}
?>
