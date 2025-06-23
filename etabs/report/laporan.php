<?php
/*
 | Laporan Tabungan Periode
 | --------------------------------------------------------------
 | Cetak semua transaksi antara dua tanggal (tgl_1 – tgl_2)
 */

require_once '../inc/koneksi.php';
require_once '../inc/rupiah.php';

date_default_timezone_set('Asia/Jakarta');          // selalu set TZ

/* ---------- ambil & sanitasi tanggal ---------- */
$dt1 = filter_input(INPUT_POST, 'tgl_1', FILTER_SANITIZE_STRING);
$dt2 = filter_input(INPUT_POST, 'tgl_2', FILTER_SANITIZE_STRING);

$dt1 = date('Y-m-d', strtotime($dt1 ?: 'today'));
$dt2 = date('Y-m-d', strtotime($dt2 ?: 'today'));

if ($dt1 > $dt2) {                                 // tukar bila terbalik
    [$dt1, $dt2] = [$dt2, $dt1];
}

/* ---------- agregat pemasukan & pengeluaran ---------- */
$masuk  = 0;
$keluar = 0;

$stmtAgg = $koneksi->prepare("
    SELECT
        SUM(CASE WHEN jenis='ST' THEN setor  END) AS tot_masuk,
        SUM(CASE WHEN jenis='TR' THEN tarik  END) AS tot_keluar
    FROM tb_tabungan
    WHERE tgl BETWEEN ? AND ?
");
$stmtAgg->bind_param('ss', $dt1, $dt2);
$stmtAgg->execute();
$stmtAgg->bind_result($masuk, $keluar);
$stmtAgg->fetch();
$stmtAgg->close();

/* ---------- saldo total seluruh siswa (opsional) ---------- */
$stmtSaldo = $koneksi->query("SELECT SUM(saldo) AS total_saldo FROM tb_siswa");
$saldoSiswa = $stmtSaldo->fetch_assoc()['total_saldo'] ?? 0;

/* ---------- detail transaksi ---------- */
$sqlDet = "
    SELECT id_tabungan, tgl, petugas, setor, tarik
    FROM tb_tabungan
    WHERE tgl BETWEEN ? AND ?
    ORDER BY tgl ASC, id_tabungan ASC
";
$stmtDet = $koneksi->prepare($sqlDet);
$stmtDet->bind_param('ss', $dt1, $dt2);
$stmtDet->execute();
$detail = $stmtDet->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Bank Mini – Laporan Tabungan</title>
  <style>
      body   {font-family:Arial,Helvetica,sans-serif;font-size:12px}
      table  {border-collapse:collapse;width:100%}
      th,td  {border:1px solid #000;padding:6px}
      th     {background:#f2f2f2}
      .text-r{ text-align:right }
      .tot   {font-weight:bold;background:#fafafa}
  </style>
</head>
<body onload="window.print()">
<center>
  <h2>LAPORAN TABUNGAN SISWA</h2>
  <h3>Periode:
      <?= date('d-M-Y', strtotime($dt1)); ?> s/d
      <?= date('d-M-Y', strtotime($dt2)); ?>
  </h3>
</center>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Tanggal</th>
      <th>Petugas</th>
      <th class="text-r">Pemasukan</th>
      <th class="text-r">Pengeluaran</th>
    </tr>
  </thead>
  <tbody>
<?php
$no = 1;
if ($detail->num_rows) {
    while ($row = $detail->fetch_assoc()) { ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= date('d/M/Y', strtotime($row['tgl'])); ?></td>
        <td><?= htmlspecialchars($row['petugas']); ?></td>
        <td class="text-r"><?= rupiah($row['setor']); ?></td>
        <td class="text-r"><?= rupiah($row['tarik']); ?></td>
      </tr>
<?php }
} else { ?>
      <tr><td colspan="5" style="text-align:center">Tidak ada transaksi pada periode ini.</td></tr>
<?php } ?>
  </tbody>
  <tfoot>
    <tr class="tot">
      <td colspan="3">Total Setoran</td>
      <td class="text-r"><?= rupiah($masuk); ?></td>
      <td></td>
    </tr>
    <tr class="tot">
      <td colspan="4">Total Penarikan</td>
      <td class="text-r"><?= rupiah($keluar); ?></td>
    </tr>
    <tr class="tot">
      <td colspan="3">Saldo Kas (Setoran − Penarikan)</td>
      <td colspan="2" class="text-r"><?= rupiah($masuk - $keluar); ?></td>
    </tr>
    <!-- jika ingin tampilkan total saldo seluruh siswa: -->
    <!--
    <tr class="tot">
      <td colspan="3">Saldo Seluruh Siswa</td>
      <td colspan="2" class="text-r"><?= rupiah($saldoSiswa); ?></td>
    </tr>
    -->
  </tfoot>
</table>

</body>
</html>
<?php
$stmtDet->close();
$koneksi->close();
?>
