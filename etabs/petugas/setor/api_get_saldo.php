<?php
// Pastikan path ke file koneksi.php sudah benar
// Sesuaikan '../inc/koneksi.php' dengan struktur folder Anda.
include '../inc/koneksi.php'; 

// Set header sebagai JSON karena JavaScript mengharapkan response JSON
header('Content-Type: application/json');

// Pastikan ada permintaan 'action' dan 'nis_siswa' yang dikirim melalui POST
if (isset($_POST['action']) && $_POST['action'] == 'get_saldo_siswa' && isset($_POST['nis_siswa'])) {
    
    // Ambil dan bersihkan NIS siswa
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis_siswa']);
    
    // Query untuk mengambil saldo dari siswa yang dipilih
    $sql = "SELECT saldo FROM tb_siswa WHERE nis = '$nis'";
    $query = mysqli_query($koneksi, $sql);
    
    if ($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $saldo = $data['saldo'];
        
        // Kirim response sukses dalam format JSON
        echo json_encode(['status' => 'success', 'saldo' => $saldo]);
    } else {
        // Kirim response error jika siswa tidak ditemukan
        echo json_encode(['status' => 'error', 'message' => 'Siswa tidak ditemukan.', 'saldo' => 0]);
    }
} else {
    // Kirim response error jika permintaan tidak valid
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']);
}

// Hentikan eksekusi script setelah mengirim JSON
exit;
?>