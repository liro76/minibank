<?php
// asumsi koneksi & rupiah sudah di-include di layout induk
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ------- buat token CSRF sekali per sesi ------- */
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];
?>
<section class="content-header">
  <h1>Master Data <small>Siswa</small></h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <a href="?page=MyApp/add_siswa" class="btn btn-primary">
        <i class="glyphicon glyphicon-plus"></i> Tambah Data
      </a>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>NIS</th>
              <th>Nama</th>
              <th>JK</th>
              <th>Kelas</th>
              <th>Status</th>
              <th>Th Masuk</th>
              <th>Saldo Saat Ini</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $no   = 1;
            $sql  = $koneksi->query("
                      SELECT s.nis, s.nama_siswa, s.jekel, s.status,
                             s.th_masuk, s.saldo, k.kelas
                      FROM tb_siswa s
                      JOIN tb_kelas k ON s.id_kelas = k.id_kelas
                      ORDER BY k.kelas, s.nis
                    ");
            while ($d = $sql->fetch_assoc()):
              $jkText = $d['jekel'] === 'LK' ? 'Laki-laki' : 'Perempuan';
              $status = $d['status'];
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= htmlspecialchars($d['nis']); ?></td>
              <td><?= htmlspecialchars($d['nama_siswa']); ?></td>
              <td><?= $jkText; ?></td>
              <td><?= htmlspecialchars($d['kelas']); ?></td>
              <td>
                <?php if ($status === 'Aktif'): ?>
                  <span class="label label-primary">Aktif</span>
                <?php elseif ($status === 'Lulus'): ?>
                  <span class="label label-success">Lulus</span>
                <?php else: ?>
                  <span class="label label-danger">Pindah</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($d['th_masuk']); ?></td>
              <td data-order="<?= $d['saldo']; ?>" class="text-right"><?= rupiah($d['saldo']); ?></td>
              <td>
                <a href="?page=MyApp/edit_siswa&kode=<?= $d['nis']; ?>" class="btn btn-success btn-sm" title="Ubah">
                  <i class="glyphicon glyphicon-edit"></i>
                </a>

                <button class="btn btn-danger btn-sm btn-hapus"
                        data-nis="<?= $d['nis']; ?>" data-token="<?= $csrf; ?>" title="Hapus">
                  <i class="glyphicon glyphicon-trash"></i>
                </button>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- SweetAlert2 & JavaScript hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-hapus').forEach(btn => {
  btn.addEventListener('click', () => {
    const nis   = btn.dataset.nis;
    const token = btn.dataset.token;

    Swal.fire({
      title: 'Yakin hapus?',
      text: `Data NIS ${nis} akan dihapus permanen!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(result => {
      if (result.isConfirmed) {
        window.location = `?page=MyApp/del_siswa&kode=${nis}&csrf=${token}`;
      }
    });
  });
});
</script>
