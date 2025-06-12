<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Laporan
        <small>Harian</small>
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
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Laporan Harian</h3>
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
                <!-- form start -->
                <form action="./report/laporan_harian.php" method="post" enctype="multipart/form-data" target="_blank">
                    <div class="box-body">
                        <p>Laporan ini akan menampilkan data transaksi untuk hari ini (<?php echo date('d-M-Y'); ?>).</p>
                        <?php
                        // Total kas hari ini: jumlah setoran hari ini dikurang jumlah penarikan hari ini
                        $today = date('Y-m-d');
                        $sql_setor = $koneksi->query("SELECT SUM(setor) as total_setor FROM tb_tabungan WHERE jenis='ST' AND tgl = '$today'");
                        $total_setor = 0;
                        if ($row = $sql_setor->fetch_assoc()) {
                            $total_setor = $row['total_setor'];
                        }

                        $sql_tarik = $koneksi->query("SELECT SUM(tarik) as total_tarik FROM tb_tabungan WHERE jenis='TR' AND tgl = '$today'");
                        $total_tarik = 0;
                        if ($row = $sql_tarik->fetch_assoc()) {
                            $total_tarik = $row['total_tarik'];
                        }

                        $total_kas_hari_ini = $total_setor - $total_tarik;
                        ?>
                        <div class="alert alert-info">
                            <b>Total kas hari ini:</b>
                            <?php echo rupiah($total_kas_hari_ini); ?>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" target="_blank" class="btn btn-info" name="btnCetak">Cetak Laporan Harian</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>