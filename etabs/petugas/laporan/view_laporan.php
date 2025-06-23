<?php
// FILE: view_laporan.php (Form dan Laporan dalam Satu File)

// 1. PENGATURAN AWAL
date_default_timezone_set('Asia/Jakarta');

// Mengambil data profil sekolah
$profil_sekolah = $koneksi->query("SELECT * FROM tb_profil LIMIT 1")->fetch_assoc();
$namaSekolah = $profil_sekolah ? htmlspecialchars($profil_sekolah['nama_sekolah']) : 'Bank Mini Sekolah';
$alamatSekolah = $profil_sekolah ? htmlspecialchars($profil_sekolah['alamat']) : 'Alamat Sekolah';

// Inisialisasi variabel
$show_report = false;
$jenis_laporan = '';

// Nilai default untuk tanggal (periode bulan ini)
$dt1 = $_POST['tgl_1'] ?? date('Y-m-01');
$dt2 = $_POST['tgl_2'] ?? date('Y-m-d');

// 2. PROSES LOGIKA SAAT FORM DISUBMIT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnCetak'])) {
    $show_report = true;
    $jenis_laporan = $_POST['jenis_laporan'] ?? 'rekap';

    // Normalisasi tanggal untuk query
    $dt1 = date('Y-m-d', strtotime($dt1));
    $dt2 = date('Y-m-d', strtotime($dt2));
    if ($dt1 > $dt2) {
        [$dt1, $dt2] = [$dt2, $dt1];
    }

    // A. LOGIKA UNTUK LAPORAN REKAP (SEMUA TRANSAKSI)
    if ($jenis_laporan === 'rekap') {
        // Query untuk total
        $stmt_totals = $koneksi->prepare("
            SELECT COALESCE(SUM(setor), 0) AS total_masuk, COALESCE(SUM(tarik), 0) AS total_keluar
            FROM tb_tabungan WHERE tgl BETWEEN ? AND ?
        ");
        $stmt_totals->bind_param('ss', $dt1, $dt2);
        $stmt_totals->execute();
        $totals = $stmt_totals->get_result()->fetch_assoc();
        $stmt_totals->close();
        
        // Query untuk detail transaksi
        $stmt_detail = $koneksi->prepare("
            SELECT t.tgl, t.petugas, t.setor, t.tarik, s.nama_siswa
            FROM tb_tabungan t JOIN tb_siswa s ON t.nis = s.nis
            WHERE tgl BETWEEN ? AND ? ORDER BY t.tgl ASC, t.id_tabungan ASC
        ");
        $stmt_detail->bind_param('ss', $dt1, $dt2);
        $stmt_detail->execute();
        $report_data = $stmt_detail->get_result();

    // B. LOGIKA UNTUK LAPORAN DETAIL (PER SISWA)
    } elseif ($jenis_laporan === 'detail') {
        // Query kompleks untuk rekap per siswa
        $stmt_detail = $koneksi->prepare("
            SELECT r.nis, s.nama_siswa, r.saldo_awal, r.total_setor, r.total_tarik, r.saldo_akhir
            FROM (
                SELECT nis,
                    (SELECT saldo_awal FROM tb_tabungan t2 WHERE t2.nis = t1.nis AND t2.tgl BETWEEN ? AND ? ORDER BY t2.tgl ASC, t2.id_tabungan ASC LIMIT 1) AS saldo_awal,
                    (SELECT saldo_akhir FROM tb_tabungan t3 WHERE t3.nis = t1.nis AND t3.tgl BETWEEN ? AND ? ORDER BY t3.tgl DESC, t3.id_tabungan DESC LIMIT 1) AS saldo_akhir,
                    SUM(setor) AS total_setor,
                    SUM(tarik) AS total_tarik
                FROM tb_tabungan t1
                WHERE t1.tgl BETWEEN ? AND ? GROUP BY nis
            ) r JOIN tb_siswa s ON s.nis = r.nis ORDER BY s.nama_siswa
        ");
        $stmt_detail->bind_param('ssssss', $dt1, $dt2, $dt1, $dt2, $dt1, $dt2);
        $stmt_detail->execute();
        $report_data = $stmt_detail->get_result();
    }
}
?>

<style>
    /* CSS untuk tampilan layar */
    .report-table { width: 100%; border-collapse: collapse; margin-top: 20px;}
    .report-table th, .report-table td { border: 1px solid #777; padding: 8px; }
    .report-table thead th { background-color: #f2f2f2; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .header-print { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
    .header-print h3, .header-print h4 { margin: 0; }

    /* CSS KHUSUS UNTUK PENCETAKAN (METODE BARU YANG LEBIH BAIK) */
    @media print {
        /* Atur orientasi dan margin halaman */
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Metode Baru: Sembunyikan elemen yang tidak perlu, bukan semuanya */
        .no-print {
            display: none !important;
        }

        /* Biarkan area cetak mengalir secara alami */
        .print-area {
            width: 100%;
        }

        /* Pastikan tabel tidak terpotong antar halaman secara aneh */
        .report-table {
            page-break-inside: auto;
        }
        .report-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .report-table thead {
            display: table-header-group; /* Ulangi header tabel di setiap halaman baru */
        }
        .report-table tfoot {
            display: table-footer-group;
        }

        /* Atur ulang font dan padding untuk cetak */
        .report-table th, .report-table td {
            font-size: 10pt;
            padding: 5px;
            word-wrap: break-word;
        }
    }
</style>

<div class="no-print">
    <section class="content-header">
        <h1>Laporan Periodik <small>Rekap & Detail</small></h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home"></i> <b>Bank Mini</b></a></li>
            <li class="active">Laporan Periodik</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Laporan</h3>
            </div>
            <form method="post" action="?page=laporan">
                <div class="box-body">
                    <div class="form-group">
                        <label>Jenis Laporan</label>
                        <select name="jenis_laporan" class="form-control" required>
                            <option value="rekap" <?= ($jenis_laporan == 'rekap' ? 'selected' : ''); ?>>Laporan Rekap (Semua Transaksi)</option>
                            <option value="detail" <?= ($jenis_laporan == 'detail' ? 'selected' : ''); ?>>Laporan Detail (Per Siswa)</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tgl_1" class="form-control" value="<?= htmlspecialchars($dt1); ?>">
                        </div>
                        <div class="col-md-5">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tgl_2" class="form-control" value="<?= htmlspecialchars($dt2); ?>">
                        </div>
                         <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" name="btnCetak" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Tampilkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>


<?php if ($show_report): ?>
<div class="print-area">
    <div class="header-print">
        <h3>LAPORAN TABUNGAN SISWA</h3>
        <h4><?php echo $namaSekolah; ?></h4>
        <p style="font-size:12px;"><?php echo $alamatSekolah; ?></p>
        <p>
            <strong>Periode: <?php echo date("d M Y", strtotime($dt1)); ?> s/d <?php echo date("d M Y", strtotime($dt2)); ?></strong>
        </p>
    </div>

    <?php if ($jenis_laporan === 'rekap'): ?>
        <h4 style="text-align:center;">Jenis Laporan: Rekap Semua Transaksi</h4>
        <table class="report-table">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Petugas</th>
                    <th class="text-right">Pemasukan</th>
                    <th class="text-right">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($data = $report_data->fetch_assoc()): ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td class="text-center"><?= date("d/m/Y", strtotime($data['tgl'])); ?></td>
                    <td><?= htmlspecialchars($data['nama_siswa']); ?></td>
                    <td><?= htmlspecialchars($data['petugas']); ?></td>
                    <td class="text-right"><?= rupiah($data['setor']); ?></td>
                    <td class="text-right"><?= rupiah($data['tarik']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr><th colspan="4" class="text-right">Total Pemasukan</th><th colspan="2" class="text-right"><?= rupiah($totals['total_masuk']); ?></th></tr>
                <tr><th colspan="4" class="text-right">Total Pengeluaran</th><th colspan="2" class="text-right"><?= rupiah($totals['total_keluar']); ?></th></tr>
                <tr><th colspan="4" class="text-right">Selisih Kas</th><th colspan="2" class="text-right"><?= rupiah($totals['total_masuk'] - $totals['total_keluar']); ?></th></tr>
            </tfoot>
        </table>

    <?php elseif ($jenis_laporan === 'detail'): ?>
        <h4 style="text-align:center;">Jenis Laporan: Detail Per Siswa</h4>
        <table class="report-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th class="text-right">Saldo Awal</th>
                    <th class="text-right">Total Setor</th>
                    <th class="text-right">Total Tarik</th>
                    <th class="text-right">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
                 <?php $no = 1; $grand_total_setor = 0; $grand_total_tarik = 0; while ($data = $report_data->fetch_assoc()): $grand_total_setor += $data['total_setor']; $grand_total_tarik += $data['total_tarik']; ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nis']); ?></td>
                    <td><?= htmlspecialchars($data['nama_siswa']); ?></td>
                    <td class="text-right"><?= rupiah($data['saldo_awal']); ?></td>
                    <td class="text-right"><?= rupiah($data['total_setor']); ?></td>
                    <td class="text-right"><?= rupiah($data['total_tarik']); ?></td>
                    <td class="text-right"><?= rupiah($data['saldo_akhir']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
             <tfoot>
                <tr><th colspan="4" class="text-right">Grand Total Setoran</th><th colspan="3" class="text-right"><?= rupiah($grand_total_setor); ?></th></tr>
                <tr><th colspan="4" class="text-right">Grand Total Penarikan</th><th colspan="3" class="text-right"><?= rupiah($grand_total_tarik); ?></th></tr>
                <tr><th colspan="4" class="text-right">Selisih Kas</th><th colspan="3" class="text-right"><?= rupiah($grand_total_setor - $grand_total_tarik); ?></th></tr>
            </tfoot>
        </table>
    <?php endif; ?>

    <br>
    <div class="no-print" style="text-align: center;">
        <button class="btn btn-success" onclick="window.print()">
            <i class="fa fa-print"></i> Cetak Halaman Ini
        </button>
    </div>
</div>
<?php 
    // Menutup statement hanya jika query dijalankan
    if (isset($stmt_detail)) $stmt_detail->close();
    endif; 
?>