<?php
// data_kas.php

// inisialisasi
$setor = 0;
$tarik = 0;

// baca dan sanitize input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnCetak'])) {
  $dt1 = $koneksi->real_escape_string($_POST['tgl_1']);
  $dt2 = $koneksi->real_escape_string($_POST['tgl_2']);

  // SUM setoran
  $q1 = "
    SELECT IFNULL(SUM(setor),0) AS Tsetor
    FROM tb_tabungan
    WHERE jenis='ST'
      AND tgl BETWEEN '$dt1' AND '$dt2'
  ";
  $r1 = $koneksi->query($q1);
  $row1 = $r1->fetch_assoc();
  $setor = $row1['Tsetor'];

  // SUM tarikan
  $q2 = "
    SELECT IFNULL(SUM(tarik),0) AS Ttarik
    FROM tb_tabungan
    WHERE jenis='TR'
      AND tgl BETWEEN '$dt1' AND '$dt2'
  ";
  $r2 = $koneksi->query($q2);
  $row2 = $r2->fetch_assoc();
  $tarik = $row2['Ttarik'];
}

// ambil saldo seluruh siswa
$q3 = "SELECT IFNULL(SUM(saldo),0) AS total_saldo FROM tb_siswa";
$r3 = $koneksi->query($q3);
$row3 = $r3->fetch_assoc();
$saldo = $row3['total_saldo'];
?>

<section class="content-header">
  <h1>Transaksi<small>Info Kas</small></h1>
  <ol class="breadcrumb">
    <li>
      <a href="index.php"><i class="fa fa-home"></i><b>Bank Mini</b></a>
    </li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header">
      Saldo Tabungan (Kas)
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
              <th>Total Setoran</th>
              <th>Total Tarikan</th>
              <th>Saldo Tabungan</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <a href="?page=data_setor"
                   class="btn btn-success btn-sm"
                   title="Detail Setoran">
                  <i class="glyphicon glyphicon-info-sign"></i>
                </a>
                <?= rupiah($setor) ?>
              </td>
              <td>
                <a href="?page=data_tarik"
                   class="btn btn-danger btn-sm"
                   title="Detail Tarikan">
                  <i class="glyphicon glyphicon-info-sign"></i>
                </a>
                <?= rupiah($tarik) ?>
              </td>
              <td><?= rupiah($saldo) ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
