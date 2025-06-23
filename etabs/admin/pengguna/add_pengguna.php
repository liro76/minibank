<?php
/* ─── initialisation ─── */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../inc/koneksi.php';

/* token CSRF sekali per sesi */
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];
?>
<!-- ────────────────────────────────────────── -->
<section class="content-header">
  <h1>Pengguna Sistem</h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header">
      <a href="?page=MyApp/add_pengguna" class="btn btn-primary">
        <i class="glyphicon glyphicon-plus"></i> Tambah Data
      </a>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Level</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $no  = 1;
            $rs  = $koneksi->query("SELECT id_pengguna, nama_pengguna, username, level
                                    FROM tb_pengguna
                                    ORDER BY nama_pengguna");
            while ($row = $rs->fetch_assoc()):
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
              <td><?= htmlspecialchars($row['username']); ?></td>
              <td><?= htmlspecialchars($row['level']); ?></td>
              <td>
                <a class="btn btn-success btn-sm"
                   href="?page=MyApp/edit_pengguna&kode=<?= $row['id_pengguna']; ?>"
                   title="Ubah"><i class="glyphicon glyphicon-edit"></i></a>

                <button class="btn btn-danger btn-sm btn-del"
                        data-id="<?= $row['id_pengguna']; ?>"
                        data-token="<?= $csrf; ?>"
                        title="Hapus">
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

<!-- SweetAlert untuk konfirmasi hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-del').forEach(btn => {
  btn.addEventListener('click', () => {
    const id   = btn.dataset.id;
    const token= btn.dataset.token;
    Swal.fire({
      title: 'Hapus pengguna?',
      text: 'Data tidak bisa dikembalikan!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus'
    }).then(res => {
      if (res.isConfirmed) {
        location = `?page=MyApp/del_pengguna&kode=${id}&csrf=${token}`;
      }
    });
  });
});
</script>
