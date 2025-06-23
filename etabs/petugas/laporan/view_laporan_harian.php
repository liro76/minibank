<?php
// FILE: view_laporan_harian.php

// 1. PENGATURAN AWAL
// Baris 'require_once' dihapus karena koneksi dan fungsi sudah dimuat oleh index.php
date_default_timezone_set('Asia/Jakarta');

$tanggal_hari_ini = date('Y-m-d');
$tanggal_terformat = date("d M Y", strtotime($tanggal_hari_ini));

// Mengambil data profil sekolah dengan aman
$profil_sekolah = $koneksi->query("SELECT nama_sekolah, alamat FROM tb_profil LIMIT 1")->fetch_assoc();
$namaSekolah = $profil_sekolah ? htmlspecialchars($profil_sekolah['nama_sekolah']) : 'Bank Mini Sekolah';
$alamatSekolah = $profil_sekolah ? htmlspecialchars($profil_sekolah['alamat']) : 'Alamat Sekolah';


// 2. QUERY AMAN DENGAN PREPARED STATEMENT
$stmt = $koneksi->prepare(
    "SELECT t.nis, s.nama_siswa, t.jenis, t.setor, t.tarik, t.saldo_akhir
     FROM tb_tabungan t
     JOIN tb_siswa s ON t.nis = s.nis
     WHERE t.tgl = ?
     ORDER BY t.id_tabungan ASC"
);
$stmt->bind_param('s', $tanggal_hari_ini);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
    /* Sembunyikan tombol saat mencetak */
    @media print {
        .no-print {
            display: none !important;
        }
        .print-area {
            font-size: 12px;
        }
        .box {
            border: none !important;
        }
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .report-table th, .report-table td {
        border: 1px solid #777;
        padding: 8px;
        text-align: center;
    }
    .report-table thead th {
        background-color: #f2f2f2;
    }
    .text-right { text-align: right !important; }
    .text-left { text-align: left !important; }
    .footer-summary {
        margin-top: 20px;
        width: 45%;
        float: right;
    }
    .footer-summary table {
        width: 100%;
        border-collapse: collapse;
    }
    .footer-summary td {
        padding: 5px;
        border: 1px solid #777;
    }
</style>

<section class="content-header no-print">
    <h1>
        Laporan Harian
        <small>Transaksi Tanggal <?= $tanggal_terformat; ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
        <li class="active">Laporan Harian</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary print-area">
        <div class="box-header with-border">
            <button onclick="window.print();" class="btn btn-primary no-print">
                <i class="fa fa-print"></i> Cetak Laporan
            </button>
        </div>
        <div class="box-body">
            <div style="text-align: center;">
                <h3>LAPORAN TRANSAKSI HARIAN</h3>
                <h4><?php echo $namaSekolah; ?></h4>
                <p>Tanggal: <?php echo $tanggal_terformat; ?></p>
            </div>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th class="text-left">Nama Siswa</th>
                        <th class="text-left">Keterangan</th>
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
                        
                        if($result->num_rows > 0) {
                            while ($data = $result->fetch_assoc()) {
                                $total_setor_harian += $data['setor'];
                                $total_tarik_harian += $data['tarik'];
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['nis']; ?></td>
                        <td class="text-left"><?php echo htmlspecialchars($data['nama_siswa']); ?></td>
                        <td class="text-left"><?php echo ($data['jenis'] == 'ST') ? 'Setoran Tunai' : 'Penarikan Tunai'; ?></td>
                        <td class="text-right"><?php echo rupiah($data['setor']); ?></td>
                        <td class="text-right"><?php echo rupiah($data['tarik']); ?></td>
                        <td class="text-right"><b><?php echo rupiah($data['saldo_akhir']); ?></b></td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center;'>Tidak ada transaksi pada hari ini.</td></tr>";
                        }
                        $stmt->close();
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
            <div style="clear: both;"></div>
        </div>
    </div>
</section>