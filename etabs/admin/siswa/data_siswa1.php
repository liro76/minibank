<?php
// Pastikan koneksi dan fungsi rupiah sudah tersedia
// include '../inc/koneksi.php';
// include '../inc/rupiah.php';
?>

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
    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="?page=MyApp/add_siswa" title="Tambah Data" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Tambah Data
            </a>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-remove"></i>
                </button>
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
                            <th>Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = $koneksi->query("SELECT s.nis, s.nama_siswa, s.jekel, s.status, s.th_masuk, s.saldo, k.kelas 
                            FROM tb_siswa s 
                            INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
                            ORDER BY k.kelas ASC, s.nis ASC");

                        while ($data = $sql->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($data['nis']); ?></td>
                                <td><?= htmlspecialchars($data['nama_siswa']); ?></td>
                                <td><?= ($data['jekel'] === 'LK') ? 'Laki-laki' : 'Perempuan'; ?></td>
                                <td><?= htmlspecialchars($data['kelas']); ?></td>
                                <td>
                                    <?php
                                    $status = $data['status'];
                                    $labelClass = match ($status) {
                                        'Aktif' => 'label-primary',
                                        'Lulus' => 'label-success',
                                        'Pindah' => 'label-danger',
                                        default => 'label-default',
                                    };
                                    ?>
                                    <span class="label <?= $labelClass; ?>"><?= $status; ?></span>
                                </td>
                                <td><?= htmlspecialchars($data['th_masuk']); ?></td>
                                <td class="text-right" data-order="<?= (int)$data['saldo']; ?>">
                                    <?= rupiah($data['saldo']); ?>
                                </td>
                                <td>
                                    <a href="?page=MyApp/edit_siswa&kode=<?= $data['nis']; ?>" title="Ubah"
                                       class="btn btn-success btn-sm">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <!-- 
                                    <a href="?page=MyApp/del_siswa&kode=<?= $data['nis']; ?>" onclick="return confirm('Yakin Hapus Data Ini ?')"
                                       title="Hapus" class="btn btn-danger btn-sm">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                    -->
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
