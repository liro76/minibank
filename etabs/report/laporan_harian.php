<?php
    include "../inc/koneksi.php";
    include "../inc/rupiah.php";

    // Get today's date automatically
    $dt1 = date('Y-m-d');
    $dt2 = date('Y-m-d');
?>

<?php
  $sql = $koneksi->query("SELECT SUM(setor) as tot_masuk from tb_tabungan where jenis='ST' and tgl BETWEEN '$dt1' AND '$dt2'");
  $masuk = 0;
  while ($data= $sql->fetch_assoc()) {
    $masuk=$data['tot_masuk'] ?? 0;
  }

  $sql = $koneksi->query("SELECT SUM(tarik) as tot_keluar from tb_tabungan where jenis='TR' and tgl BETWEEN '$dt1' AND '$dt2'");
  $keluar = 0;
  while ($data= $sql->fetch_assoc()) {
    $keluar=$data['tot_keluar'] ?? 0;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bank Mini - Laporan Tabungan Harian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .report-title {
            margin-bottom: 5px;
        }
        .report-date {
            margin-bottom: 15px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .report-table th {
            background-color: #f2f2f2;
            padding: 8px 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .report-table td {
            padding: 6px 10px;
            border: 1px solid #ddd;
        }
        .number-col {
            text-align: right;
            padding-right: 15px !important;
        }
        .center-col {
            text-align: center;
        }
        .summary-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .divider {
            border-top: 1px solid #000;
            margin: 10px 0;
        }
        .no-print {
            display: none;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div style="text-align: center;">
        <h2 class="report-title">Laporan Tabungan Siswa Harian</h2>
        <h3 class="report-title">Sekolah</h3>
        <p class="report-date">Tanggal: <?php echo date("d-M-Y", strtotime($dt1)); ?></p>
        <div class="divider"></div>

        <table class="report-table">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="12%">Tanggal</th>
                    <th width="15%">Petugas</th>
                    <th width="20%">Nama Siswa</th>
                    <th width="10%">Kelas</th>
                    <th width="12%">Saldo Awal</th>
                    <th width="12%">Setor</th>
                    <th width="12%">Tarik</th>
                    <th width="12%">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_tampil = "SELECT * FROM tb_tabungan 
                             INNER JOIN tb_siswa ON tb_tabungan.nis=tb_siswa.nis 
                             INNER JOIN tb_kelas ON tb_siswa.id_kelas=tb_kelas.id_kelas 
                             WHERE tgl BETWEEN '$dt1' AND '$dt2' 
                             ORDER BY tgl ASC";
                $query_tampil = mysqli_query($koneksi, $sql_tampil);
                $no = 1;
                
                if(mysqli_num_rows($query_tampil) > 0) {
                    while ($data = mysqli_fetch_array($query_tampil, MYSQLI_BOTH)) {
                        $saldo_akhir = ($data['saldo_sebelum'] ?? 0) + $data['setor'] - $data['tarik'];
                ?>
                <tr>
                    <td class="center-col"><?php echo $no; ?></td>
                    <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                    <td><?php echo $data['petugas']; ?></td>
                    <td><?php echo $data['nama_siswa']; ?></td>
                    <td class="center-col"><?php echo $data['kelas']; ?></td>
                    <td class="number-col"><?php echo rupiah($data['saldo_sebelum'] ?? 0); ?></td>
                    <td class="number-col"><?php echo rupiah($data['setor']); ?></td>
                    <td class="number-col"><?php echo rupiah($data['tarik']); ?></td>
                    <td class="number-col"><?php echo rupiah($saldo_akhir); ?></td>
                </tr>
                <?php
                        $no++;
                    }
                } else {
                ?>
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada transaksi hari ini</td>
                </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    <td colspan="5">Total Setoran</td>
                    <td colspan="4" class="number-col"><?php echo rupiah($masuk); ?></td>
                </tr>
                <tr class="summary-row">
                    <td colspan="5">Total Penarikan</td>
                    <td colspan="4" class="number-col"><?php echo rupiah($keluar); ?></td>
                </tr>
                <tr class="summary-row">
                    <td colspan="5">Total Kas Hari Ini</td>
                    <td colspan="4" class="number-col"><?php echo rupiah($masuk - $keluar); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 8px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">
            Cetak Laporan
        </button>
    </div>

    <script>
        // Auto-print when page loads
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>
</html>