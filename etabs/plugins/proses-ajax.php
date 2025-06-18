<?php
// Pastikan path ke koneksi.php benar relatif dari lokasi file ini
include '../inc/koneksi.php';
// Fungsi rupiah.php tidak perlu di-include di sini karena formatting akan dilakukan di JavaScript

// Memastikan bahwa ini adalah permintaan AJAX yang kita harapkan
if (isset($_POST['action']) && $_POST['action'] == 'get_saldo_siswa') {
    // Memastikan parameter nis_siswa dikirim
    if (isset($_POST['nis_siswa'])) {
        $nis_siswa = mysqli_real_escape_string($koneksi, $_POST['nis_siswa']); // Ambil NIS

        // Query untuk mengambil saldo dari tabel tb_siswa
        // Diasumsikan kolom 'saldo' di tb_siswa menyimpan saldo terkini
        $sql_get_saldo = "SELECT saldo FROM tb_siswa WHERE nis = '$nis_siswa'";
        $query_get_saldo = mysqli_query($koneksi, $sql_get_saldo);

        if ($query_get_saldo && mysqli_num_rows($query_get_saldo) > 0) {
            $data_saldo = mysqli_fetch_assoc($query_get_saldo);
            // Mengembalikan saldo sebagai angka mentah (integer) dalam format JSON
            echo json_encode(['status' => 'success', 'saldo' => (int)$data_saldo['saldo']]);
        } else {
            // Jika siswa tidak ditemukan atau saldo tidak ada
            echo json_encode(['status' => 'error', 'message' => 'Siswa tidak ditemukan atau saldo tidak ada.']);
        }
    } else {
        // Jika parameter nis_siswa tidak dikirim
        echo json_encode(['status' => 'error', 'message' => 'Parameter NIS siswa tidak ditemukan.']);
    }
    exit; // Penting untuk menghentikan eksekusi script setelah mengirim respons JSON
} else {
    // Jika 'action' tidak diset atau tidak sesuai
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid.']);
    exit;
}
?>