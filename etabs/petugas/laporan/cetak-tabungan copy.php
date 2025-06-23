<?php
/* ===============================================================
 | CETAK BUKU TABUNGAN SISWA
 | Versi Perbaikan: Efisien, Aman, dan Rapi
 =============================================================== */

// 1. INISIALISASI & KONEKSI
require_once dirname(__DIR__, 1) . '/inc/koneksi.php';
require_once dirname(__DIR__, 1) . '/inc/rupiah.php';

date_default_timezone_set('Asia/Jakarta');

// 2. VALIDASI INPUT NIS DENGAN AMAN
if (!isset($_GET['nis']) || !ctype_digit($_GET['nis'])) {
    die('Akses tidak sah. NIS tidak valid atau tidak disediakan.');
}
$nis = $_GET['nis'];

// 3. AMBIL DATA MASTER (Siswa & Sekolah) - HANYA 2 QUERY
// Mengambil data siswa, kelas, dan saldo akhir resmi dalam satu query
$stmt_siswa = $koneksi->prepare("
    SELECT s.nis, s.nama_siswa, s.saldo, k.kelas
    FROM tb_siswa s
    JOIN tb_kelas k ON s.id_kelas = k.id_kelas
    WHERE s.nis = ?
");
$stmt_siswa->bind_param('s', $nis);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();

if ($result_siswa->num_rows === 0) {
    die('Siswa dengan NIS tersebut tidak ditemukan.');
}
$data_siswa = $result_siswa->fetch_assoc();
$stmt_siswa->close();

// Mengambil profil sekolah
$profil = $koneksi->query("SELECT nama_sekolah, alamat FROM tb_profil LIMIT 1")->fetch_assoc();
$namaSekolah = $profil['nama_sekolah'] ?? 'Bank Mini Sekolah';
$alamatSekolah = $profil['alamat'] ?? 'Alamat Sekolah Anda';


// 4. AMBIL DETAIL TRANSAKSI - HANYA 1 QUERY
$stmt_trx = $koneksi->prepare("
    SELECT tgl, jenis, setor, tarik, saldo_akhir
    FROM tb_tabungan
    WHERE nis = ?
    ORDER BY tgl ASC, id_tabungan ASC
");
$stmt_trx->bind_param('s', $nis);
$stmt_trx->execute();
$transactions = $stmt_trx->get_result();
$stmt_trx->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Tabungan - <?= htmlspecialchars($data_siswa['nama_siswa']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 95%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h3, .header p { margin: 2px 0; }
        .student-info, .summary-table { width: 100%; margin-bottom: 20px; }
        .student-info td { padding: 3px 0; }
        .transaction-table { width: 100%; border-collapse: collapse; }
        .transaction-table th, .transaction-table td { border: 1px solid #000; padding: 6px; text-align: center; }
        .transaction-table th { background-color: #f2f2f2; }
        .summary-box { width: 45%; float: right; margin-top: 20px; }
        .summary-box table { width: 100%; }
        .summary-box td { padding: 4px; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
    </style>
</head>
<body onload="window.print()">
<div class="container">
    <div class="header">
        <h3>BUKU TABUNGAN SISWA</h3>
        <h3><?= htmlspecialchars($namaSekolah) ?></h3>
        <p><?= htmlspecialchars($alamatSekolah) ?></p>
    </div>

    <table class="student-info">
        <tr><td width="20%"><b>NIS</b></td><td width="1%">:</td><td><?= htmlspecialchars($data_siswa['nis']) ?></td></tr>
        <tr><td><b>Nama Siswa</b></td><td>:</td><td><?= htmlspecialchars($data_siswa['nama_siswa']) ?></td></tr>
        <tr><td><b>Kelas</b></td><td>:</td><td><?= htmlspecialchars($data_siswa['kelas']) ?></td></tr>
    </table>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th class="text-left">Keterangan</th>
                <th class="text-right">Setoran</th>
                <th class="text-right">Penarikan</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_setor = 0;
            $total_tarik = 0;
            while ($row = $transactions->fetch_assoc()):
                // 5. HITUNG TOTAL TRANSAKSI MELALUI PHP (LEBIH EFISIEN)
                $total_setor += $row['setor'];
                $total_tarik += $row['tarik'];
                $keterangan = $row['jenis'] === 'ST' ? 'Setoran Tunai' : 'Penarikan Tunai';
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d-m-Y', strtotime($row['tgl'])) ?></td>
                <td class="text-left"><?= $keterangan ?></td>
                <td class="text-right"><?= rupiah($row['setor']) ?></td>
                <td class="text-right"><?= rupiah($row['tarik']) ?></td>
                <td class="text-right"><b><?= rupiah($row['saldo_akhir']) ?></b></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="summary-box">
        <table>
            <tr>
                <td><b>Total Setoran</b></td>
                <td class="text-right"><?= rupiah($total_setor) ?></td>
            </tr>
            <tr>
                <td><b>Total Penarikan</b></td>
                <td class="text-right"><?= rupiah($total_tarik) ?></td>
            </tr>
            <tr>
                <td><b>Saldo Akhir</b></td>
                <td class="text-right"><b><?= rupiah($data_siswa['saldo']) ?></b></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>