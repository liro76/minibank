<?php
// -------------------------------------------------------------------
//  view_tabungan.php   –  Detail & Riwayat Tabungan Siswa
// -------------------------------------------------------------------

// >>> Pastikan koneksi & helper rupiah sudah di-include di entry-point
// include 'inc/koneksi.php';
// include 'inc/rupiah.php';

// -------------------------------------------------------------------
// 1. Validasi & sanitasi parameter NIS
// -------------------------------------------------------------------
if (isset($_GET['nis']) && ctype_digit($_GET['nis'])) {
    $nis = intval($_GET['nis']);

    // -----------------------------------------------------------------
    // 2. Ambil data siswa + kelas + saldo
    // -----------------------------------------------------------------
    $sql_cek_siswa = "
        SELECT s.nis, s.nama_siswa, k.kelas, s.saldo
        FROM tb_siswa s
        JOIN tb_kelas k ON s.id_kelas = k.id_kelas
        WHERE s.nis = $nis";
    $query_cek_siswa = mysqli_query($koneksi, $sql_cek_siswa);

    if (!$query_cek_siswa) {
        echo "<div class='alert alert-danger'>Query error: " . mysqli_error($koneksi) . "</div>";
        return;
    }

    $data_siswa = mysqli_fetch_assoc($query_cek_siswa);
    if (!$data_siswa) {
        echo "<div class='alert alert-danger'>Data siswa dengan NIS <strong>$nis</strong> tidak ditemukan.</div>";
        return;
    }
} else {
    echo "<div class='alert alert-warning'>Parameter NIS siswa tidak disediakan atau tidak valid.</div>";
    return;
}
?>

<!-- =============================================================== -->
<!--           KARTU IDENTITAS SISWA & SALDO SAAT INI               -->
<!-- =============================================================== -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Buku Tabungan: <?= htmlspecialchars($data_siswa['nama_siswa']); ?></h3>
        <div class="box-tools pull-right">
            <a href="report/cetak-tabungan.php?nis=<?= $data_siswa['nis']; ?>" target="_blank"
               class="btn btn-info btn-sm">
                <i class="fa fa-print"></i> Cetak Buku Tabungan
            </a>
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>

    <div class="box-body">
        <div class="row">
            <!-- Kolom kiri -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">NIS</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($data_siswa['nis']); ?>" readonly>
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Siswa</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($data_siswa['nama_siswa']); ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- Kolom kanan -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Kelas</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($data_siswa['kelas']); ?>" readonly>
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Saldo Saat Ini</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control"
                               value="<?= rupiah($data_siswa['saldo']); ?>"
                               readonly style="font-weight:bold;color:#008d4c;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =============================================================== -->
<!--                      TABEL RIWAYAT TRANSAKSI                    -->
<!-- =============================================================== -->
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Riwayat Transaksi</h3>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Saldo Awal</th>
                        <th>Setoran</th>
                        <th>Penarikan</th>
                        <th>Saldo Akhir</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
<?php
$no            = 1;
$total_setor   = 0;
$total_tarik   = 0;

// -----------------------------------------------------------------
// 3. Ambil transaksi & hitung total
// -----------------------------------------------------------------
$sql_transaksi = mysqli_query(
    $koneksi,
    "SELECT tgl, saldo_awal, setor, tarik, saldo_akhir, petugas
     FROM tb_tabungan
     WHERE nis = $nis
     ORDER BY tgl ASC, id_tabungan ASC"
);

if ($sql_transaksi && mysqli_num_rows($sql_transaksi) > 0) {
    while ($tr = mysqli_fetch_assoc($sql_transaksi)) {
        $total_setor += $tr['setor'];
        $total_tarik += $tr['tarik'];
?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= date('d M Y', strtotime($tr['tgl'])); ?></td>
                        <td align="right"><?= rupiah($tr['saldo_awal']); ?></td>
                        <td align="right"><?= rupiah($tr['setor']); ?></td>
                        <td align="right"><?= rupiah($tr['tarik']); ?></td>
                        <td align="right"><b><?= rupiah($tr['saldo_akhir']); ?></b></td>
                        <td><?= htmlspecialchars($tr['petugas']); ?></td>
                    </tr>
<?php
    }
    // -------------------------------------------------------------
    // 4. Baris TOTAL — 7 sel, tidak pakai colspan agar cocok kolom
    // -------------------------------------------------------------
?>
                    <tr class="bg-total" style="font-weight:bold;background:#f9f9f9;">
                        <td align="center">TOTAL</td> <!-- kolom 0 -->
                        <td></td>                      <!-- kolom 1 -->
                        <td></td>                      <!-- kolom 2 -->
                        <td align="right"><?= rupiah($total_setor); ?></td>  <!-- kolom 3 -->
                        <td align="right"><?= rupiah($total_tarik); ?></td>  <!-- kolom 4 -->
                        <td></td>                      <!-- kolom 5 -->
                        <td></td>                      <!-- kolom 6 -->
                    </tr>
<?php
} else {
    // -------------------------------------------------------------
    // 5. Tidak ada transaksi — tampilkan pesan, isi 7 sel
    // -------------------------------------------------------------
?>
                    <tr class="text-center text-muted">
                        <td colspan="7">Belum ada transaksi untuk siswa ini.</td>
                    </tr>
<?php
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
