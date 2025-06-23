<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../inc/koneksi.php';

/* token CSRF */
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];
?>
<section class="content-header">
  <h1>Master Data <small>Kelas</small></h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border">
      <a href="?page=MyApp/add_kelas" class="btn btn-primary">
        <i class="glyphicon glyphicon-plus"></i> Tambah Data
      </a>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Kelas</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $no  = 1;
              $sql = $koneksi->query("SELECT id_kelas, kelas FROM tb_kelas ORDER BY kelas ASC");
              while ($d = $sql->fetch_assoc()):
            ?>
            <tr>
              <td data-order="<?= $no; ?>"><?= $no++; ?></td>
              <td><?= htmlspecialchars($d['kelas']); ?></td>
              <td>
                <a class="btn btn-success btn-sm"
                   href="?page=MyApp/edit_kelas&kode=<?= $d['id_kelas']; ?>"
                   title="Ubah"><i class="glyphicon glyphicon-edit"></i></a>

                <button class="btn btn-danger btn-sm btn-del"
                        data-id="<?= $d['id_kelas']; ?>"
                        data-token="<?= $csrf; ?>"
                        title="Hapus"><i class="glyphicon glyphicon-trash"></i></button>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- SweetAlert & hapus dengan token -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-del').forEach(btn=>{
  btn.onclick=()=>{
    const id   = btn.dataset.id;
    const tok  = btn.dataset.token;
    Swal.fire({
      title:'Hapus kelas?', text:`ID ${id}`, icon:'warning',
      showCancelButton:true, confirmButtonText:'Ya, hapus'
    }).then(res=>{
      if(res.isConfirmed){
        location=`?page=MyApp/del_kelas&kode=${id}&csrf=${tok}`;
      }
    });
  };
});
</script>
