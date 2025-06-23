<?php
    // Memasukkan file koneksi dan fungsi rupiah
    include "../inc/koneksi.php";
    include "../inc/rupiah.php";

    // Validasi dan bersihkan NIS dari parameter GET untuk keamanan
    if (isset($_GET['nis'])) {
        $nis = mysqli_real_escape_string($koneksi, $_GET['nis']);

        // Query untuk mengambil data siswa dan kelasnya
        $sql_siswa = "SELECT s.nis, s.nama_siswa, k.kelas 
                      FROM tb_siswa s 
                      JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
                      WHERE s.nis = '$nis'";
        $query_siswa = mysqli_query($koneksi, $sql_siswa);
        $data_siswa = mysqli_fetch_array($query_siswa);

        // Jika siswa dengan NIS tersebut tidak ditemukan, hentikan proses
        if (!$data_siswa) {
            die("Siswa dengan NIS tersebut tidak ditemukan.");
        }
    } else {
        // Jika parameter NIS tidak ada di URL, hentikan proses
        die("NIS tidak disediakan. Proses dibatalkan.");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Tabungan - <?php echo htmlspecialchars($data_siswa['nama_siswa']); ?></title>
    <style>
        /* Gaya CSS untuk tampilan cetak yang rapi */
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 95%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h3, .header p { margin: 2px 0; }
        .student-info table { width: 60%; margin-bottom: 20px; }
        .student-info td { padding: 2px; }
        .transaction-table { width: 100%; border-collapse: collapse; }
        .transaction-table th, .transaction-table td { border: 1px solid #000; padding: 6px; text-align: center; }
        .transaction-table th { background-color: #f2f2f2; }
        .text-right { text-align: right !important; }
        .text-left { text-align: left !important; }
        .footer-summary { margin-top: 20px; width: 40%; float: right; }
        .footer-summary table { width: 100%; }
        .footer-summary td { padding: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>BUKU TABUNGAN SISWA</h3>
            <?php
                // Mengambil data profil sekolah
                $sql_profil = $koneksi->query("SELECT * from tb_profil LIMIT 1");
                $data_profil = $sql_profil->fetch_assoc();
            ?>
            <h3><?php echo htmlspecialchars($data_profil['nama_sekolah']); ?></h3>
            <p><?php echo htmlspecialchars($data_profil['alamat']); ?></p>
        </div>

        <div class="student-info">
            <table>
                <tr>
                    <td width="30%"><strong>NIS</strong></td>
                    <td width="2%">:</td>
                    <td><?php echo htmlspecialchars($data_siswa['nis']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Siswa</strong></td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($data_siswa['nama_siswa']); ?></td>
                </tr>
                 <tr>
                    <td><strong>Kelas</strong></td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($data_siswa['kelas']); ?></td>
                </tr>
            </table>
        </div>

        <table class="transaction-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Saldo Awal</th>
                    <th>Setoran</th>
                    <th>Penarikan</th>
                    <th>Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 1;
                    // Query diurutkan untuk memastikan urutan transaksi benar
                    $sql_transaksi = $koneksi->query("SELECT * FROM tb_tabungan WHERE nis='$nis' ORDER BY tgl ASC, id_tabungan ASC");
                    while ($data_trx = $sql_transaksi->fetch_assoc()) {
                        $keterangan = ($data_trx['jenis'] == 'ST') ? 'Setoran Tunai' : 'Penarikan Tunai';
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($data_trx['tgl'])); ?></td>
                    <td class="text-left"><?php echo $keterangan; ?></td>
                    <td class="text-right"><?php echo rupiah($data_trx['saldo_awal']); ?></td>
                    <td class="text-right"><?php echo rupiah($data_trx['setor']); ?></td>
                    <td class="text-right"><?php echo rupiah($data_trx['tarik']); ?></td>
                    <td class="text-right"><b><?php echo rupiah($data_trx['saldo_akhir']); ?></b></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>

        <div class="footer-summary">
             <table>
                <tr>
                    <td><strong>Total Setoran</strong></td>
                    <td class="text-right">
                        <?php
                            $sql_setor = $koneksi->query("SELECT SUM(setor) as Tsetor from tb_tabungan where nis='$nis'");
                            $data_setor = $sql_setor->fetch_assoc();
                            echo rupiah($data_setor['Tsetor']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Total Penarikan</strong></td>
                    <td class="text-right">
                        <?php
                            $sql_tarik = $koneksi->query("SELECT SUM(tarik) as Ttarik from tb_tabungan where nis='$nis'");
                            $data_tarik = $sql_tarik->fetch_assoc();
                            echo rupiah($data_tarik['Ttarik']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Saldo Akhir</strong></td>
                    <td class="text-right">
                        <strong>
                            <?php
                                // Mengambil saldo akhir yang paling akurat dari tabel siswa
                                $sql_saldo_akhir = $koneksi->query("SELECT saldo FROM tb_siswa WHERE nis='$nis'");
                                $data_saldo_akhir = $sql_saldo_akhir->fetch_assoc();
                                echo rupiah($data_saldo_akhir['saldo']);
                            ?>
                        </strong>
                    </td>
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