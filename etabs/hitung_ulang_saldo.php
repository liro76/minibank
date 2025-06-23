<?php
// hitung_ulang_saldo.php

// Sertakan file koneksi dan rupiah
include 'inc/koneksi.php';
include 'inc/rupiah.php';

// Atur agar script tidak timeout untuk data yang banyak
set_time_limit(300); 

echo "<!DOCTYPE html><html><head><title>Proses Hitung Ulang Saldo</title>";
echo "<style>body{font-family: sans-serif; line-height: 1.6;} hr{border: 1px solid #ddd;}</style>";
echo "</head><body>";
echo "<h1>Memulai Proses Perhitungan Ulang Saldo...</h1>";

// 1. Dapatkan semua NIS unik yang pernah bertransaksi
$sql_siswa = "SELECT DISTINCT nis FROM tb_tabungan";
$query_siswa = mysqli_query($koneksi, $sql_siswa);

if (!$query_siswa) {
    die("FATAL ERROR: Tidak bisa mengambil daftar siswa. " . mysqli_error($koneksi));
}

$total_siswa_diproses = 0;

// 2. Loop untuk setiap siswa
while ($data_siswa = mysqli_fetch_assoc($query_siswa)) {
    $nis = $data_siswa['nis'];
    echo "<h2>Memproses Siswa NIS: $nis</h2>";
    
    // Inisialisasi saldo berjalan
    $saldo_berjalan = 0;

    // 3. Ambil semua transaksi siswa, urutkan berdasarkan tanggal dan ID
    $sql_transaksi = "SELECT id_tabungan, tgl, setor, tarik FROM tb_tabungan WHERE nis = '$nis' ORDER BY tgl ASC, id_tabungan ASC";
    $query_transaksi = mysqli_query($koneksi, $sql_transaksi);

    if (!$query_transaksi) {
        echo "<p style='color:red;'>Error mendapatkan transaksi untuk NIS $nis: " . mysqli_error($koneksi) . "</p>";
        continue;
    }

    // 4. Loop untuk setiap transaksi dan update saldonya
    while ($data_transaksi = mysqli_fetch_assoc($query_transaksi)) {
        $id_tabungan = $data_transaksi['id_tabungan'];
        $setor = (int)$data_transaksi['setor'];
        $tarik = (int)$data_transaksi['tarik'];

        $saldo_awal = $saldo_berjalan;
        $saldo_akhir = $saldo_awal + $setor - $tarik;

        $sql_update = "UPDATE tb_tabungan SET saldo_awal = $saldo_awal, saldo_akhir = $saldo_akhir WHERE id_tabungan = $id_tabungan";
        $query_update = mysqli_query($koneksi, $sql_update);

        if ($query_update) {
            echo "ID: $id_tabungan | Tgl: {$data_transaksi['tgl']} | Awal: " . rupiah($saldo_awal) . " | Setor: " . rupiah($setor) . " | Tarik: " . rupiah($tarik) . " | Akhir: " . rupiah($saldo_akhir) . " (OK)<br>";
        } else {
            echo "<p style='color:red;'>GAGAL update ID Tabungan: $id_tabungan</p>";
        }
        
        // Update saldo berjalan untuk iterasi berikutnya
        $saldo_berjalan = $saldo_akhir;
    }
    
    // 5. Sinkronkan saldo master di tabel tb_siswa agar 100% akurat
    $sql_sync_siswa = "UPDATE tb_siswa SET saldo = $saldo_berjalan WHERE nis = '$nis'";
    mysqli_query($koneksi, $sql_sync_siswa);
    echo "<p style='color:blue;'><b>Saldo Master untuk NIS $nis disinkronkan menjadi: " . rupiah($saldo_berjalan) . "</b></p>";
    echo "<hr>";
    $total_siswa_diproses++;
}

echo "<h1>Proses Selesai. Total $total_siswa_diproses siswa telah diproses.</h1>";
echo "</body></html>";
?>
