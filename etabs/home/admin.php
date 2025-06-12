<?php
    $sql = $koneksi->query("SELECT count(nis) as siswa FROM tb_siswa WHERE status='Aktif'");
    $siswa = 0;
    if ($data = $sql->fetch_assoc()) {
        $siswa = $data['siswa'];
    }

    $sql = $koneksi->query("SELECT SUM(setor) as Tsetor FROM tb_tabungan WHERE jenis='ST'");
    $setor = 0;
    if ($data = $sql->fetch_assoc()) {
        $setor = $data['Tsetor'];
    }

    $sql = $koneksi->query("SELECT SUM(tarik) as Ttarik FROM tb_tabungan WHERE jenis='TR'");
    $tarik = 0;
    if ($data = $sql->fetch_assoc()) {
        $tarik = $data['Ttarik'];
    }

    // Query untuk total saldo seluruh siswa
    $sql = $koneksi->query("SELECT SUM(saldo) as total_saldo FROM tb_siswa");
    $saldo = 0;
    if ($data = $sql->fetch_assoc()) {
        $saldo = $data['total_saldo'];
    }

    // Total kas = total setoran - total penarikan
    $total_kas = $setor - $tarik;
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
        <small>Administrator</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-yellow" style="min-height:170px;">
                <div class="inner" style="text-align:center;">
                    <h4 style="margin-bottom: 0;">
                        <?= $siswa; ?>
                    </h4>
                    <p style="margin-bottom: 10px;">Siswa Aktif</p>
                    <hr style="margin:10px 0;">
                    <h4 style="margin-bottom: 0;">
                        <?= rupiah($saldo); ?>
                    </h4>
                    <p style="margin-bottom: 0;">Total Saldo</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="?page=petugas" class="small-box-footer">More info
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-aqua" style="min-height:170px;">
                <div class="inner" style="text-align:center;">
                    <h4 style="margin-bottom: 0;">
                        <?= rupiah($setor); ?>
                    </h4>
                    <p style="margin-bottom: 0;">Total Setoran</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="?page=data_setor" class="small-box-footer">More info
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-red" style="min-height:170px;">
                <div class="inner" style="text-align:center;">
                    <h4 style="margin-bottom: 0;">
                        <?= rupiah($tarik); ?>
                    </h4>
                    <p style="margin-bottom: 0;">Total Penarikan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="?page=data_tarik" class="small-box-footer">More info
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

    </div>

    <!-- Box Total Kas -->
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="small-box bg-green" style="min-height:170px;">
                <div class="inner" style="text-align:center;">
                    <h4 style="margin-bottom: 0;">
                        <?= rupiah($total_kas); ?>
                    </h4>
                    <p style="margin-bottom: 0;">Total Kas</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <section class="content">
        <div class="row">
            <div class="box box-primary">
                <div class="box-header">
                    <strong>Profil Sekolah</strong>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            <i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Sekolah</th>
                                    <th>Alamat</th>
                                    <th>Akreditasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = $koneksi->query("SELECT * FROM tb_profil");
                                    while ($data = $sql->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $data['nama_sekolah']; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['alamat']; ?>
                                    </td>
                                    <td>
                                        Akreditasi
                                        <?php echo $data['akreditasi']; ?>
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
        </div>
    </section>
</section>