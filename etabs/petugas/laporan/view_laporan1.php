<?php
/* ------------------------------------------------------------------
 |   laporan_tabungan_rekap.php
 | ------------------------------------------------------------------
 |   Menampilkan laporan tabungan yang sudah DIREKAP per siswa
 |   antara tanggal tgl_1 – tgl_2. 1 baris = 1 siswa.
 | ------------------------------------------------------------------
 */

require_once '../inc/koneksi.php';
require_once '../inc/rupiah.php';

date_default_timezone_set('Asia/Jakarta');

/* ===== 1. Ambil & validasi input tanggal ===== */
$dt1 = filter_input(INPUT_POST, 'tgl_1', FILTER_SANITIZE_STRING);
$dt2 = filter_input(INPUT_POST, 'tgl_2', FILTER_SANITIZE_STRING);

$dt1 = date('Y-m-d', strtotime($dt1 ?: 'today'));
$dt2 = date('Y-m-d', strtotime($dt2 ?: 'today'));
if ($dt1 > $dt2) { [$dt1, $dt2] = [$dt2, $dt1]; }

/* ===== 2. Query rekap langsung di MySQL =====
   Kita pilih:
   • saldo_awal  = saldo_awal transaksi paling awal
   • saldo_akhir = saldo_akhir transaksi paling akhir
   Menggunakan sub-query MIN/MAX id_tabungan agar akurat.            */
$sql = "
SELECT
    r.nis,
    s.nama_siswa,
    r.saldo_awal,
    r.total_setor,
    r.total_tarik,
    r.saldo_akhir
FROM (
    SELECT
        nis,
        /* ----- saldo_awal transaksi pertama ----- */
        ( SELECT saldo_awal
          FROM tb_tabungan t2
          WHERE t2.nis   = t1.nis
            AND t2.tgl  BETWEEN ? AND ?
          ORDER BY t2.tgl ASC, t2.id_tabungan ASC
          LIMIT 1 ) AS saldo_awal,

        /* ----- saldo_akhir transaksi terakhir ---- */
        ( SELECT saldo_akhir
          FROM tb_tabungan t3
          WHERE t3.nis   = t1.nis
            AND t3.tgl  BETWEEN ? AND ?
          ORDER BY t3.tgl DESC, t3.id_tabungan DESC
          LIMIT 1 ) AS saldo_akhir,

        SUM(setor)             AS total_setor,
        SUM(tarik)             AS total_tarik
    FROM tb_tabungan t1
    WHERE t1.tgl BETWEEN ? AND ?
    GROUP BY nis
) r
JOIN tb_siswa s ON s.nis = r.nis
ORDER BY s.nama_siswa;
";

$stmt = $koneksi->prepare($sql);
/* bind_param urut sesuai ? di query:  dt1, dt2, dt1, dt2, dt1, dt2 */
$stmt->bind_param('ssssss', $dt1, $dt2, $dt1, $dt2, $dt1, $dt2);
$stmt->execute();
$rekap = $stmt->get_result();

/* ===== 3. Hitung total keseluruhan periode ===== */
$total_setor  = 0;
$total_tarik  = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Laporan Tabungan Per Siswa</title>
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
  <h2>LAPORAN TABUNGAN PER SISWA</h2>
  <h3>Periode:
      <?= date('d-M-Y', strtotime($dt1)); ?> s/d
      <?= date('d-M-Y', strtotime($dt2)); ?>
  </h3>
</center>

<table>
<thead>
  <tr>
    <th>No</th>
    <th>NIS</th>
    <th>Nama Siswa</th>
    <th class="text-r">Saldo Awal</th>
    <th class="text-r">Total Setor</th>
    <th class="text-r">Total Tarik</th>
    <th class="text-r">Saldo Akhir</th>
  </tr>
</thead>
<tbody>
<?php
$no = 1;
if ($rekap->num_rows) {
    while ($row = $rekap->fetch_assoc()) {
        $total_setor += $row['total_setor'];
        $total_tarik += $row['total_tarik']; ?>
  <tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($row['nis']); ?></td>
    <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
    <td class="text-r"><?= rupiah($row['saldo_awal']); ?></td>
    <td class="text-r"><?= rupiah($row['total_setor']); ?></td>
    <td class="text-r"><?= rupiah($row['total_tarik']); ?></td>
    <td class="text-r"><?= rupiah($row['saldo_akhir']); ?></td>
  </tr>
<?php }
} else { ?>
  <tr><td colspan="7" style="text-align:center">Tidak ada transaksi pada periode ini.</td></tr>
<?php } ?>
</tbody>
<tfoot>
  <tr class="tot">
    <td colspan="4">TOTAL SETORAN</td>
    <td class="text-r"><?= rupiah($total_setor); ?></td>
    <td colspan="2"></td>
  </tr>
  <tr class="tot">
    <td colspan="5">TOTAL PENARIKAN</td>
    <td class="text-r"><?= rupiah($total_tarik); ?></td>
    <td></td>
  </tr>
  <tr class="tot">
    <td colspan="4">SELISIH KAS (Setoran − Penarikan)</td>
    <td colspan="3" class="text-r"><?= rupiah($total_setor - $total_tarik); ?></td>
  </tr>
</tfoot>
</table>

</body>
</html>
<?php
$stmt->close();
$koneksi->close();
?>
