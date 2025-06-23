<?php
/* ─── Inisialisasi ─── */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Baris ini dinonaktifkan karena koneksi sudah di-handle oleh index.php
// require_once '../inc/koneksi.php';

/* Token CSRF sekali per sesi untuk keamanan */
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];
?>
<section class="content-header">
    <h1>
        Pengguna Sistem
        <small>Kelola Pengguna</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
        <li class="active">Pengguna</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="?page=MyApp/add_pengguna" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Tambah Pengguna
            </a>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover">
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
                        $no = 1;
                        // Query untuk mengambil semua data pengguna
                        $rs = $koneksi->query("SELECT id_pengguna, nama_pengguna, username, level FROM tb_pengguna ORDER BY nama_pengguna ASC");
                        while ($row = $rs->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['level']); ?></td>
                            <td>
							<td>
								<a class="btn btn-success btn-sm"
								href="?page=MyApp/edit_pengguna&kode=<?= $row['id_pengguna']; ?>"
								title="Ubah Data">
								<i class="glyphicon glyphicon-edit"></i>
								</a>

								<?php
								// Logika untuk mengunci tombol hapus:
								// 1. Jika level BUKAN 'Administrator'
								// 2. DAN jika ID pengguna BUKAN ID diri sendiri (mencegah hapus diri)
								if ($row['level'] !== 'Administrator' && $row['id_pengguna'] != $_SESSION['ses_id']) :
								?>
									<button class="btn btn-danger btn-sm btn-del"
											data-id="<?= $row['id_pengguna']; ?>"
											data-nama="<?= htmlspecialchars($row['nama_pengguna']); ?>"
											data-token="<?= $csrf; ?>"
											title="Hapus Data">
										<i class="glyphicon glyphicon-trash"></i>
									</button>
								<?php endif; ?>
							</td>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Menambahkan event listener ke semua tombol dengan class .btn-del
    document.querySelectorAll('.btn-del').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.nama;
            const csrfToken = this.dataset.token;

            Swal.fire({
                title: 'Anda Yakin?',
                html: `Anda akan menghapus pengguna: <br><b>${userName}</b>`, // Menampilkan nama pengguna yang akan dihapus
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Saja!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, redirect ke halaman hapus dengan parameter yang aman
                    window.location.href = `?page=MyApp/del_pengguna&kode=${userId}&csrf=${csrfToken}`;
                }
            });
        });
    });
});
</script>