<?php
// FILE: data_kas.php (Form dan Laporan dalam Satu File)

// 1. PENGATURAN & INISIALISASI VARIABEL
date_default_timezone_set('Asia/Jakarta');
$profil_sekolah = $koneksi->query("SELECT * FROM tb_profil LIMIT 1")->fetch_assoc();
$namaSekolah = $profil_sekolah ? htmlspecialchars($profil_sekolah['nama_sekolah']) : 'Bank Mini Sekolah';
$alamatSekolah = $profil_sekolah ? htmlspecialchars($profil_sekolah['alamat']) : 'Alamat Sekolah';

$show_report = false;
$dt1 = $_POST['tgl_1'] ?? date('Y-m-01');
$dt2 = $_POST['tgl_2'] ?? date('Y-m-d');
$setor_periode = 0;
$tarik_periode = 0;

// 2. PROSES FORM JIKA DISUBMIT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnCetak'])) {
    $show_report = true;
    $dt1 = date('Y-m-d', strtotime($dt1));
    $dt2 = date('Y-m-d', strtotime($dt2));
    if ($dt1 > $dt2) {
        [$dt1, $dt2] = [$dt2, $dt1];
    }

    $stmt_periode = $koneksi->prepare("
        SELECT 
            COALESCE(SUM(CASE WHEN jenis='ST' THEN setor END), 0) AS total_setor,
            COALESCE(SUM(CASE WHEN jenis='TR' THEN tarik END), 0) AS total_tarik
        FROM tb_tabungan
        WHERE tgl BETWEEN ? AND ?
    ");
    $stmt_periode->bind_param('ss', $dt1, $dt2);
    $stmt_periode->execute();
    $data_periode = $stmt_periode->get_result()->fetch_assoc();
    $stmt_periode->close();

    $setor_periode = $data_periode['total_setor'];
    $tarik_periode = $data_periode['total_tarik'];
}

// 3. QUERY DATA KESELURUHAN
$result_total = $koneksi->query("SELECT COALESCE(SUM(saldo), 0) AS total_saldo FROM tb_siswa");
$data_total = $result_total->fetch_assoc();
$saldo_total_sistem = $data_total['total_saldo'];
$arus_kas_periode = $setor_periode - $tarik_periode;
?>

<section class="content-header">
    <h1>Info Kas<small>Laporan Kas Per Periode</small></h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
        <li class="active">Info Kas</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Pilih Periode Laporan</h3>
        </div>
        <form action="?page=data_kas" method="post">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="tgl_1">Tanggal Awal</label>
                            <input type="date" name="tgl_1" id="tgl_1" class="form-control" value="<?= htmlspecialchars($dt1); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="tgl_2">Tanggal Akhir</label>
                            <input type="date" name="tgl_2" id="tgl_2" class="form-control" value="<?= htmlspecialchars($dt2); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" name="btnCetak" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Tampilkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php if ($show_report): ?>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-bar-chart"></i> Hasil Laporan Periode 
                <strong><?= date('d M Y', strtotime($dt1)) ?></strong> s/d <strong><?= date('d M Y', strtotime($dt2)) ?></strong>
            </h3>
        </div>
        <div class="box-body">
            
            <h4>Ringkasan Transaksi Periode Ini</h4>
            <div class="row">
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?= rupiah($setor_periode); ?></h3>
                            <p>Total Setoran</p>
                        </div>
                        <div class="icon"><i class="fa fa-arrow-down"></i></div>
                        <a href="?page=data_setor" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3><?= rupiah($tarik_periode); ?></h3>
                            <p>Total Tarikan</p>
                        </div>
                        <div class="icon"><i class="fa fa-arrow-up"></i></div>
                        <a href="?page=data_tarik" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?= rupiah($arus_kas_periode); ?></h3>
                            <p>Arus Kas Periode</p>
                        </div>
                        <div class="icon"><i class="fa fa-balance-scale"></i></div>
                        <a href="#" class="small-box-footer" style="cursor: default;">Setoran - Tarikan</a>
                    </div>
                </div>
            </div>

            <hr>

            <h4>Ringkasan Sistem Keseluruhan</h4>
            <div class="row">
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?= rupiah($saldo_total_sistem); ?></h3>
                             <p>Total Dana Tersimpan</p>
                        </div>
                        <div class="icon"><i class="fa fa-university"></i></div>
                        <a href="?page=data_tabungan" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
             </div>
    </div>
    <?php endif; ?>

</section>