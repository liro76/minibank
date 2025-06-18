<?php
// Pastikan koneksi.php sudah di-include di index.php utama
// atau di awal file ini jika file ini diakses langsung. Contoh: include '../inc/koneksi.php';
// Pastikan juga rupiah.php sudah di-include atau fungsi rupiah tersedia.
// Contoh: include '../inc/rupiah.php';
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
                <i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
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
                            <th>Saldo Saat Ini</th> <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Pastikan $koneksi sudah terdefinisi (dari inc/koneksi.php)
                        // Pastikan juga fungsi rupiah() sudah terdefinisi (dari inc/rupiah.php)
                        $sql = $koneksi->query("SELECT s.nis, s.nama_siswa, s.jekel, s.status, s.th_masuk, s.saldo, k.kelas
                        from tb_siswa s inner join tb_kelas k on s.id_kelas=k.id_kelas
                        order by k.kelas asc, s.nis asc"); // Menambahkan order by untuk tampilan yang lebih rapi
                        while ($data= $sql->fetch_assoc()) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                            <td>
                                <?php echo $data['nis']; ?>
                            </td>
                            <td>
                                <?php echo $data['nama_siswa']; ?>
                            </td>
                            <td>
                                <?php echo $data['jekel']; ?>
                            </td>
                            <td>
                                <?php echo $data['kelas']; ?>
                            </td>
                            <?php $warna = $data['status']  ?>
                            <td>
                                <?php if ($warna == 'Aktif') { ?>
                                <span class="label label-primary">Aktif</span>
                                <?php } elseif ($warna == 'Lulus') { ?>
                                <span class="label label-success">Lulus</span>
                                <?php } elseif ($warna == 'Pindah') { ?>
                                <span class="label label-danger">Pindah</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php echo $data['th_masuk']; ?>
                            </td>
                            <td>
                                <?php echo rupiah($data['saldo']); ?> </td>
                            <td>
                                <a href="?page=MyApp/edit_siswa&kode=<?php echo $data['nis']; ?>" title="Ubah"
                                   class="btn btn-success">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a href="?page=MyApp/del_siswa&kode=<?php echo $data['nis']; ?>" onclick="return confirm('Yakin Hapus Data Ini ?')"
                                   title="Hapus" class="btn btn-danger">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>