<?php
require_once "../inc/koneksi.php";
require_once "../inc/rupiah.php";

date_default_timezone_set('Asia/Jakarta');          // (1)

$tanggal_hari_ini = date('Y-m-d');

/* ---- data profil sekolah ---- */
$profil = $koneksi->query("SELECT nama_sekolah FROM tb_profil LIMIT 1")
                  ->fetch_assoc();
$namaSekolah = $profil ? htmlspecialchars($profil['nama_sekolah'])
                       : 'Nama Sekolah';

/* ---- prepared statement transaksi ---- */
$stmt = $koneksi->prepare(
    "SELECT t.nis, s.nama_siswa, t.jenis, t.setor, t.tarik,
            t.saldo_awal, t.saldo_akhir
     FROM tb_tabungan t
     JOIN tb_siswa s ON t.nis = s.nis
     WHERE t.tgl = ?
     ORDER BY t.id_tabungan"
);
$stmt->bind_param('s', $tanggal_hari_ini);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laporan Transaksi Harian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 95%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h3, .header p { margin: 2px 0; }
        .transaction-table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        .transaction-table th, .transaction-table td { border: 1px solid #000; padding: 6px; text-align: center; }
        .transaction-table th { background-color: #f2f2f2; }
        .text-right { text-align: right !important; }
        .text-left { text-align: left !important; }
        .footer-summary { margin-top: 20px; width: 40%; float: right; }
        .footer-summary table { width: 100%; border-collapse: collapse; }
        .footer-summary td { padding: 5px; border: 1px solid #000; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>LAPORAN TRANSAKSI HARIAN</h3>
            <?php
                // Mengambil data profil sekolah
                $sql_profil = $koneksi->query("SELECT * from tb_profil LIMIT 1");
                $data_profil = $sql_profil->fetch_assoc();
            ?>
            <h3><?php echo htmlspecialchars($data_profil['nama_sekolah']); ?></h3>
            <p>Tanggal: <?php echo date("d M Y", strtotime($tanggal_hari_ini)); ?></p>
        </div>

        <table class="transaction-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th class="text-left">Nama Siswa</th>
                    <th class="text-left">Keterangan</th>
                    <th class="text-right">Saldo Awal</th>
                    <th class="text-right">Setoran</th>
                    <th class="text-right">Penarikan</th>
                    <th class="text-right">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 1;
                    $total_setor_harian = 0;
                    $total_tarik_harian = 0;

                    // Query yang diperbaiki untuk mengambil data yang diperlukan
                    $sql = $koneksi->query("SELECT t.nis, s.nama_siswa, t.jenis, t.setor, t.tarik, t.saldo_awal, t.saldo_akhir 
                                           FROM tb_tabungan t JOIN tb_siswa s ON t.nis = s.nis 
                                           WHERE t.tgl = '$tanggal_hari_ini' 
                                           ORDER BY t.id_tabungan ASC");

                    if(mysqli_num_rows($sql) > 0) {
                        while ($data = $sql->fetch_assoc()) {
                            $total_setor_harian += $data['setor'];
                            $total_tarik_harian += $data['tarik'];
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $data['nis']; ?></td>
                    <td class="text-left"><?php echo htmlspecialchars($data['nama_siswa']); ?></td>
                    <td class="text-left"><?php echo ($data['jenis'] == 'ST') ? 'Setoran Tunai' : 'Penarikan Tunai'; ?></td>
                    <td class="text-right"><?php echo rupiah($data['saldo_awal']); ?></td>
                    <td class="text-right"><?php echo rupiah($data['setor']); ?></td>
                    <td class="text-right"><?php echo rupiah($data['tarik']); ?></td>
                    <td class="text-right"><b><?php echo rupiah($data['saldo_akhir']); ?></b></td>
                </tr>
                <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>Tidak ada transaksi pada hari ini.</td></tr>";
                    }
                ?>
            </tbody>
        </table>

        <div class="footer-summary">
            <table>
                <tr>
                    <td><strong>Total Setoran Hari Ini</strong></td>
                    <td class="text-right"><?php echo rupiah($total_setor_harian); ?></td>
                </tr>
                <tr>
                    <td><strong>Total Penarikan Hari Ini</strong></td>
                    <td class="text-right"><?php echo rupiah($total_tarik_harian); ?></td>
                </tr>
                <tr>
                    <td><strong>Selisih Kas Hari Ini</strong></td>
                    <td class="text-right"><strong><?php echo rupiah($total_setor_harian - $total_tarik_harian); ?></strong></td>
                </tr>
            </table>
        </div>
    </div>
    <script>
        // Otomatis menjalankan dialog print saat halaman dimuat
        window.print();
    </script>
</body>
</html>